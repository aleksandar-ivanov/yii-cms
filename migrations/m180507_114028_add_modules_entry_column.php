<?php

use yii\db\Migration;

/**
 * Class m180507_114028_add_modules_entry_column
 */
class m180507_114028_add_modules_entry_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('modules', 'entry', 'VARCHAR(100)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('modules', 'entry');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180507_114028_add_modules_entry_column cannot be reverted.\n";

        return false;
    }
    */
}
