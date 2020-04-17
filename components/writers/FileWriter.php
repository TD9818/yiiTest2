<?php


namespace app\components\writers;


/**
 * Class CreateJson - запись данных в файл в формате .json
 * @package app\components\writeFile
 */
class FileWriter implements WriteInterface
{
    private $path;
    private $url;
    private $api;

    /**
     * CreateJson constructor.
     * @param string $path - хранит путь к обрабатываемому файлу
     * @param string $url - хронит адрес в классе
     * @param string $api - хронит версию API в классе
     */
    function __construct($path, $url, $api)
    {
        $this->path = $path;
        $this->url = $url;
        $this->api = $api;
    }

    /**
     * write - осуществляет запись в файл
     *
     * @param string $content - текст записывавемый в файл
     * @return bool
     */
    public function write(string $content): bool
    {
        return file_put_contents($this->path, $content);
    }
}