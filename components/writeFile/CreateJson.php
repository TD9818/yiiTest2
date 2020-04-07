<?php


namespace app\components\writeFile;

use Yii;
use app\components\JSON;

class CreateJson implements IWrite
{
    private $path;

    function __construct()
    {
        $this->path = Yii::$app->params['pathFileJson'];
    }

    public function saveFags()
    {
        // TODO: Implement saveFags() method.

        $json = new JSON;
        file_put_contents($this->path, $json->GetFile('master'));
    }
}