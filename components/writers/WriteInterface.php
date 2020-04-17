<?php

namespace app\components\writers;

interface WriteInterface
{
    public function write(string $content);
}