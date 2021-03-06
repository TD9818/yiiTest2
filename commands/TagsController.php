<?php

namespace app\commands;

use app\components\JSON;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\components\writers\FileWriter;
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
        $file = new FileWriter($path, $url, $api);

        if ($file->write($json->getTags('master'))) {
            echo ' -> JSON file Creation Successful';
        } else {
            echo ' -> JSON file is not Creation Successful !!!';
        }
        return ExitCode::OK;
    }

    /**
     * actionDeleteBase - Удаление двнных таблицы из БД
     */
    public function actionDeleteBase()
    {
        if (MySQL_construct::deleteAll()) {
            echo ' -> Data deleted successfully';
        } else {
            echo ' -> Data was not delete !!!';
        }
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
        $json = new JSON(
            Yii::$app->params['URLgitlab'],
            $api = Yii::$app->params['API_Vgitlab']
        );
        $newTable = new MySQL_construct(
            $json,
            [
                'url' => Yii::$app->params['URLgitlab'],
                'api' => Yii::$app->params['API_Vgitlab']
            ]
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
        $json = new JSON(
            Yii::$app->params['URLgitlab'],
            $api = Yii::$app->params['API_Vgitlab']
        );
        $newTable = new MySQL_construct(
            $json,
            [
                'url' => Yii::$app->params['URLgitlab'],
                'api' => Yii::$app->params['API_Vgitlab']
            ]
        );
        $newTable->display();
        return ExitCode::OK;
    }
}
