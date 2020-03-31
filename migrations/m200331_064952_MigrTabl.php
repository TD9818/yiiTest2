<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class m200331_064952_MigrTabl
 */
class m200331_064952_MigrTabl extends Migration{
    public function up()
    {
        $this->createTable('repos', [
            'id' => $this->integer(5)->notNull(),
            'project' => $this->integer(15)->notNull(),
            'name' => $this->char(50)->notNull(),
            'PRIMARY KEY(id)',
        ]);
    }

    public function down()
    {
        $this->dropTable('repos');
    }

}
