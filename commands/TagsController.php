<?php

namespace app\commands;

use Yii;
use app\migrations\m200331_064952_MigrTabl;
use yii\console\Controller;
use yii\console\ExitCode;
use app\components\JSON;
use app\components\MySQL_construct;

class TagsController extends Controller
{
    public function actionIndex()
    {
        echo 'hi';

        return ExitCode::OK;
    }

    public function actionJson()
    {
        $json = new JSON;
        $json->CreateFileJson();
        echo 'Database Creation Successful !!!';
        return ExitCode::OK;
    }

    public function actionCreateb()
    {
        $newTable = new m200331_064952_MigrTabl();
        $newTable->up();
        echo 'Database Creation Successful !!!';
        return ExitCode::OK;
    }

    public function actionDeleteb()
    {
        $newTable = new m200331_064952_MigrTabl();
        $newTable->down();
        echo 'Database deletion was successful !!!';
        return ExitCode::OK;
    }

    public function actionAddTagsInBase()
    {
        $newTable = new MySQL_construct();
        $newTable->GetTags(Yii::$app->params['userID']);
        echo 'Data collected successfully !!!';
        return ExitCode::OK;
    }

    public function actionDisplayb()
    {
        $newTable = new MySQL_construct();
        $newTable->Display();
        return ExitCode::OK;
    }
}
