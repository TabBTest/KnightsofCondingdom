<?php

use yii\db\Migration;

class m160706_150218_add_item_add_ons_archive extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE vendor_menu_item_add_ons add column isArchived int(11) null default 0;");
    }

    public function down()
    {
        echo "m160706_150218_add_item_add_ons_archive cannot be reverted.\n";

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
