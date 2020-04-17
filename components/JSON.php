<?php

namespace app\components;

use Yii;
use yii\httpclient\Client;
use yii\helpers\BaseJson;


/**
 * Class JSON отвечает за сборку и генерацию с
 * данных по тегам пользователя GitLab в формат .json
 * @package app\components
 */
class JSON
{
    /**
     * URLToID - часть адреса до ID проекта пользователя
     *
     * URLAfterIDTags - часть адреса после ID проекта пользователя
     * для получения тегов, путь к тегам
     *
     * URLAfterIDComposer - часть адреса после ID проекта пользователя
     * для получения файла composer.json
     */
    const URLToID = 'projects';
    const URLAfterIDTags = 'repository/tags';
    const URLAfterIDComposer = 'repository/files/composer.json/raw?ref=';

    /**
     * @var string $url - хронит адрес в классе
     * @var string $api - хронит версию API в классе
     */
    private $url;
    private $api;

    /**
     * JSON constructor.
     * @param string $url - принимает актуальный URL
     * @param string $api - принимает
     */
    function __construct($url, $api)
    {
        $this->url = $url;
        $this->api = $api;
    }

    /**
     * getFile - Генерирует строку тегов для записи в файл
     *
     * @param string $ref - параметр ref ссылки запроса получения файла
     * @return string - Все теги всех репозиториев пользователя в формате json
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function getTags($ref)
    {
        $repos = MySQL_construct::getList();
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
        return BaseJson::encode($fileObject);
    }

    /**
     * getJsonTags - возвращает массив всех тегоа одного репозитория
     * в заданном формате построения
     *
     * @param array $allTagsRepo - все теги репозитория
     * @param array $contentConfig - данные файла composer.json проеута
     * @param string $nameRepos - имя репозитория
     * @return array - массив полученных тегов
     */
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

    /**
     * newClientApiGitlab - клиент подключение
     *
     * @param string $url - адрес запроса
     * @return mixed - массив данных ответа от сервера в формате json
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function newClientApiGitlab($url)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setUrl($url)
            ->setFormat(Client::FORMAT_JSON)
            ->send();
        return ($response->data);
    }

    /**
     * constructURL - вспомогательная функция, создания строки запроса
     *
     * @param string $url - адрес сайта
     * @param string $apiVer - версия API
     * @param string $to - часть запроса до id пользователя или репозитория
     * @param string $id - id пользователя или репозитория
     * @param string $after - часть запроса после id пользователя или репозитория
     * @return string - строка запроса
     */

    public function constructURL($url, $apiVer, $to, $id, $after)
    {
        return $url . $apiVer . $to . '/' . $id . '/' . $after;
    }
}