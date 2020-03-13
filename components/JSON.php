<?php

namespace app\components;

use yii\httpclient\Client;

define('URL', 'https://gitlab.com/');
define('APIV', 'api/v4/');

class JSON
{
    public function CreateFileJson($nameID)
    {
        $filename = '../components/Tags.json';
        file_put_contents($filename, $this->GetFile($nameID));
    }

    private function GetFile($nameID)
    {
        $fileObject = [];
        foreach ($this->ClientApiGitlab('users', $nameID, 'projects') as $repo) {
            $fileObject += [
                $repo["name"] => $this->JsonTags($this->ClientApiGitlab('projects', $repo["id"], 'repository/tags'),
                    $repo["name"])
            ];
        }
        return json_encode($fileObject);
    }

    private function JsonTags($allTagsRepo, $nameRepos)
    {
        $resultJsonInOneRepos = [];
        foreach ($allTagsRepo as $tagRepo) {
            $resultJsonInOneRepos += [
                $tagRepo["name"] => [
                    "name" => $nameRepos,
                    "description" => $tagRepo["release"]["description"],
                    "type" => null,
                    "require" => null,
                    "version" => $tagRepo["name"],
                    "extra" => null,
                    "source" => [
                        "url" => $tagRepo["commit"]["web_url"],
                        "type" => 'git',
                        "reference" => $tagRepo["name"],
                    ],
                    "autoload" => [
                        "psr-4" => [
                            //тут не понял принцип выборки, да и впринципе
                            // не понял autoload (как и то что задал константами)
                        ],
                    ],
                ],
            ];
        }
        return $resultJsonInOneRepos;
    }

    public function ClientApiGitlab($param0, $nameID, $param1)
    {
        //$param0 - projects or users
        //$param1 - repository/tags (if $param0 = 'projects') or projects (if $param0 = 'users')
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl(URL . APIV . $param0 . '/' . $nameID . '/' . $param1 . '/')
            ->setFormat(Client::FORMAT_JSON)
            ->send();
        return ($response->data);
    }
}