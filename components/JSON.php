<?php

namespace app\components;

use Yii;
use yii\httpclient\Client;
use app\models\Repos;

Yii::setAlias('@pathFile', 'Tags.json');

class JSON
{

    public function CreateFileJson()
    {
        file_put_contents(Yii::getAlias('@pathFile'), $this->GetFile());
    }

    private function GetFile()
    {
        $repos = Repos::find()
            ->orderBy('id')
            ->all();
        $fileObject = [];

        foreach ($repos as $repo) {
            $fileObject += [
                $repo->name =>
                    $this->JsonTags(
                        $this->ClientApiGitlab($this->ConstructURL(
                            Yii::$app->params['URLgitlab'],
                            Yii::$app->params['API_Vgitlab'],
                            'projects',
                            $repo->project,
                            'repository/tags'
                        )),
                        $this->ClientApiGitlab($this->ConstructURL(
                            Yii::$app->params['URLgitlab'],
                            Yii::$app->params['API_Vgitlab'],
                            'projects',
                            $repo->project,
                            'repository/files/composer.json/raw?ref=master'
                        )),
                        $repo->name
                    )
            ];
        }
        return json_encode($fileObject);
    }

    private function JsonTags($allTagsRepo, $contentConfig, $nameRepos)
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

    public function ClientApiGitlab($url)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->setFormat(Client::FORMAT_JSON)
            ->send();
        return ($response->data);
    }

    public function ConstructURL($url, $apiVer, $param0, $id, $param1)
    {
        //$param0 - projects or users
        //$param1 - repository/tags  (if $param0 = 'projects')
        //          repository/files/composer.json/raw?ref=master  (if $param0 = 'projects')
        // or       projects (if $param0 = 'users')
        return $url . $apiVer . $param0 . '/' . $id . '/' . $param1;
    }
}