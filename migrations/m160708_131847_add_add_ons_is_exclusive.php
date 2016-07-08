<?php

use yii\db\Migration;

class m160708_131847_add_add_ons_is_exclusive extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE vendor_menu_item_add_ons add column isExclusive int(11) null default 0;");
        $this->execute(" alter TABLE vendor_menu_item_add_ons add column menuCategoryId int(11) null");
    }

    public function down()
    {
        echo "m160708_131847_add_add_ons_is_exclusive cannot be reverted.\n";

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
