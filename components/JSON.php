<?php

namespace app\components;

use Yii;
use yii\httpclient\Client;
use app\models\Repos;

Yii::setAlias('@pathFile', 'Tags.json');

class JSON
{
    const URLToID = 'projects';
    const URLAfterIDTags = 'repository/tags';
    const URLAfterIDComposer = 'repository/files/composer.json/raw?ref=';

    private $url;
    private $api;


    function __construct()
    {
        $this->url = Yii::$app->params['URLgitlab'];
        $this->api = Yii::$app->params['API_Vgitlab'];
    }

    public function GetFile($ref)
    {
        $repos = Repos::find()
            ->orderBy('id')
            ->all();
        $fileObject = [];

        foreach ($repos as $repo) {
            $fileObject += [
                $repo->name =>
                    $this->getJsonTags(
                        $this->newClientApiGitlab(
                            $this->constructURL(
                                $this->url,
                                $this->api,
                                self::URLToID,
                                $repo->project,
                                self::URLAfterIDTags
                            )
                        ),
                        $this->newClientApiGitlab(
                            $this->constructURL(
                                $this->url,
                                $this->api,
                                self::URLToID,
                                $repo->project,
                                self::URLAfterIDComposer . $ref
                            )
                        ),
                        $repo->name
                    )
            ];
        }
        return json_encode($fileObject);
    }

    private function getJsonTags($allTagsRepo, $contentConfig, $nameRepos)
    {
        $resultJsonInOneRepos = [];
        foreach ($allTagsRepo as $tagRepo) {
            $resultJsonInOneRepos += [
                $tagRepo['name'] => [
                    'name' => $nameRepos,
                    'description' => $tagRepo['release']['description'],
                    'type' => $contentConfig['type'],
                    'require' => $contentConfig['require'],
                    'version' => $tagRepo['name'],
                    'extra' => $contentConfig['extra'],
                    'source' => [
                        'url' => $tagRepo['commit']['web_url'],
                        'type' => 'git',
                        'reference' => $tagRepo['name'],
                    ],
                    'autoload' => $contentConfig['autoload'],
                ],
            ];
        }
        return $resultJsonInOneRepos;
    }

    public function newClientApiGitlab($url)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setUrl($url)
            ->setFormat(Client::FORMAT_JSON)
            ->send();
        return ($response->data);
    }

    public function constructURL($url, $apiVer, $to, $id, $after)
    {
        return $url . $apiVer . $to . '/' . $id . '/' . $after;
    }
}