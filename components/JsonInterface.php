<?php


namespace app\components;


interface JsonInterface
{
    public function getTags($ref);

    public function newClientApiGitlab($url);

    public function constructURL($url, $apiVer, $to, $id, $after);
}