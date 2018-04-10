<?php

use yii\db\Migration;

/**
 * Handles the creation of table `modules`.
 */
class m180410_150418_create_modules_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('modules', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'installed' => $this->tinyInteger()->defaultValue(false),
            'enabled' => $this->tinyInteger()->defaultValue(false)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('modules');
    }
}
