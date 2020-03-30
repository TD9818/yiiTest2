<?php


namespace app\components;

use Yii;
//use app\components\JSON;
use app\models\Repos;

class MySQL_construct
{
    public function GetTags($nameID)
    {
        $i = 0;
        $json = new JSON();
        foreach (
            $json->ClientApiGitlab($json->ConstructURL(
                Yii::$app->params['URLgitlab'],
                Yii::$app->params['API_Vgitlab'],
                'users',
                $nameID,
                'projects'
            )
            )
            as
            $repo
        ) {
            $customer = new Repos();
            $customer->id = $i++;
            $customer->progect = $repo['id'];
            $customer->name = $repo['name'];
            $customer->save();

        }
    }

    public function Display()
    {
        $repos = Repos::find()
            ->orderBy('id')
            ->all();

        foreach ($repos as $repo) {
            echo $repo->name . ': ';
            var_dump($repo->progect);
        }
    }
}

