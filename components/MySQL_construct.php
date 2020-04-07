<?php


namespace app\components;

use Yii;
use app\models\Repos;

class MySQL_construct
{
    const URLToID = 'users';
    const URLAfterID = 'projects';

    private $url;
    private $api;

    function __construct()
    {
        $this->url = Yii::$app->params['URLgitlab'];
        $this->api = Yii::$app->params['API_Vgitlab'];
    }

    public function getTags($nameID)
    {
        $i = 1;
        $json = new JSON();


        foreach (
            $json->newClientApiGitlab(
                $json->constructURL(
                    $this->url,
                    $this->api,
                    self::URLToID,
                    $nameID,
                    self::URLAfterID
                )
            )
            as
            $repo
        ) {
            $customer = new Repos(
                [
                    'id' => $i++,
                    'project' => $repo['id'],
                    'name' => $repo['name']
                ]
            );
            if (!$customer->save()) {
                return false;
            }
        }
        return true;
    }

    public function Display()
    {
        $repos = Repos::find()
            ->orderBy('id')
            ->all();

        foreach ($repos as $repo) {
            echo $repo->name . ': ' . $repo->project . "\n";
        }
    }
}

