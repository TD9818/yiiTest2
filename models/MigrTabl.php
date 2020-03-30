<?php

namespace app\models;

use yii\db\Migration;

class MigrTabl extends Migration
{
    public function up()
    {
        $this->execute('CREATE TABLE repos (id INT(5) NOT NULL PRIMARY KEY, progect INT(20) NOT NULL, name CHAR(50) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
    }

    public function down()
    {
        $this->dropTable('repos');
    }
}