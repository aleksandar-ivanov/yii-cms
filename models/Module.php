<?php

namespace app\models;

use yii\db\ActiveRecord;

class Module extends ActiveRecord
{
    protected $entryClass;

    public static function tableName()
    {
        return '{{modules}}';
    }

    public function setEntryClass(string $class)
    {
        $this->entryClass = $class;
    }

    public function getEntryClass()
    {
        return $this->entryClass;
    }
}
