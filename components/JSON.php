<?php

namespace app\components;

use yii\httpclient\Client;

class JSON
{

    public function CreateFileJson($nameUser)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://gitlab.com/api/v4/projects/' . $nameUser . '/repository/tags/')
            ->setFormat(Client::FORMAT_JSON)
            ->send();
        var_dump($response);
/*
        $filename = 'Tags.json';
        file_put_contents($filename, $response);
*/
    }
}