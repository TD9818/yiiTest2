<?php

namespace app\models;

use yii\db\ActiveRecord;

class Repos extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%repos}}';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'project' => 'project'
        ];
    }

    public function rules()
    {
        return [
            [['id', 'name', 'project'], 'required'],
            ['name', 'string', 'max' => 50],
        ];
    }
}
