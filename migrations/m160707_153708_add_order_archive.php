<?php

use yii\db\Migration;

class m160707_153708_add_order_archive extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE orders add column isArchived int(11) null default 0;");
    }

    public function down()
    {
        echo "m160707_153708_add_order_archive cannot be reverted.\n";

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
