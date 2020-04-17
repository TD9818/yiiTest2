<?php

namespace app\commands;

use app\components\JSON;
use Yii;
use app\migrations\m200331_064952_MigrTabl;
use yii\console\Controller;
use yii\console\ExitCode;
use app\components\writeFile\CreateJson;
use app\components\MySQL_construct;

/**
 * Class TagsController
 * @package app\commands
 */
class TagsController extends Controller
{
    /**
     * actionJson - Создание json файла
     *
     * @return int
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionJson()
    {
        $path = Yii::$app->params['pathFileJson'];
        $url = Yii::$app->params['URLgitlab'];
        $api = Yii::$app->params['API_Vgitlab'];

        $json = new JSON($url, $api);
        $file = new CreateJson($path,  $url, $api);

        if ($file->write($json->getTags('master'))) {
            echo ' -> JSON file Creation Successful';
        } else {
            echo ' -> JSON file is not Creation Successful !!!';
        }
        return ExitCode::OK;
    }

    /**
     * actionCreateBase - создаёт таблицу в БД
     *
     * @return int
     */
    public function actionCreateBase()
    {
        $newTable = new m200331_064952_MigrTabl();
        $newTable->up();
        echo ' -> Database Creation Successful';
        return ExitCode::OK;
    }

    /**
     * actionDeleteBase - Удаление таблицы из БД
     *
     * @return int
     */
    public function actionDeleteBase()
    {
        $newTable = new m200331_064952_MigrTabl();
        $newTable->down();
        echo ' -> Database deletion was successful';
        return ExitCode::OK;
    }

    /**
     * actionAddTagsInBase - Добавление информации о проектах пользователя в таблицу БД
     *
     * @return int
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionAddTagsInBase()
    {
        $newTable = new MySQL_construct(
            Yii::$app->params['URLgitlab'],
            Yii::$app->params['API_Vgitlab']
        );
        if ($newTable->writeIdRepos(Yii::$app->params['userID'])) {
            echo ' -> Data collected successfully';
        } else {
            echo ' -> Data was not saved !!!';
        }
        return ExitCode::OK;
    }

    /**
     * actionDisplayBase - Вывод таблицы БД в консоль
     *
     * @return int
     */
    public function actionDisplayBase()
    {
        $newTable = new MySQL_construct(
            Yii::$app->params['URLgitlab'],
            Yii::$app->params['API_Vgitlab']
        );
        $newTable->display();
        return ExitCode::OK;
    }
}
