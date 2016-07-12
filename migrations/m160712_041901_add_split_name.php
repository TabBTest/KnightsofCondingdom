<?php

use yii\db\Migration;

class m160712_041901_add_split_name extends Migration
{
    public function up()
    {
        $this->renameColumn('user', 'name', 'firstName');
        $this->addColumn('user', 'lastName', $this->string());
        
    }

    public function down()
    {
        echo "m160712_041901_add_split_name cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
