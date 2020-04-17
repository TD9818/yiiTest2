<?php


namespace app\components;

use app\models\Repos;

/**
 * Class MySQL_construct - работа с таблицей ID репозиториев базы данных
 * @package app\components
 */
class MySQL_construct
{
    /**
     * URLToID - часть адреса до ID пользователя
     *
     * URLAfterIDTags - часть адреса после ID пользователя
     * для получения id репозиториев
     */
    const URLToID = 'users';
    const URLAfterID = 'projects';

    /**
     * @var string $url - хронит адрес в классе
     * @var string $api - хронит версию API в классе
     */
    private $url;
    private $api;

    /**
     * MySQL_construct constructor.
     * @param string $url - принимает актуальный URL
     * @param string $api - принимает
     */
    function __construct($url, $api)
    {
        $this->url = $url;
        $this->api = $api;
    }

    /**
     * writeIdRepos - запись данных о проектах пользователя в базу данных
     *
     * @param $nameID - id пользователя
     * @return bool - Были получены данные или нет
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function writeIdRepos($nameID)
    {
        $i = 1;
        $json = new JSON(
            $this->url,
            $this->api
        );


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

    /**
     * getList - возвращает содердание таблицы в виде массива данных отсортированного по id репозитория
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getList()
    {
        return Repos::find()
            ->orderBy('id')
            ->all();
    }

    /**
     * display - вывод в консоли содержимого таблицы из БД
     */
    public function display()
    {
        $repos = Repos::find()
            ->orderBy('id')
            ->all();

        foreach ($repos as $repo) {
            echo $repo->name . ': ' . $repo->project . "\n";
        }
    }
}

