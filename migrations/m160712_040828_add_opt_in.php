<?php

use yii\db\Migration;

class m160712_040828_add_opt_in extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE user add column isOptIn int(11) null default 1;");
    }

    public function down()
    {
        echo "m160712_040828_add_opt_in cannot be reverted.\n";

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
