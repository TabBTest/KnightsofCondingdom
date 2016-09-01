<?php

use yii\db\Migration;

class m160901_142522_add_addons_custom extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE vendor_menu_item_add_ons add column isSpecialOrder int(11) null default 0;");
        $this->execute(" alter TABLE vendor_menu_item_add_ons add column amountFull double null default 0");
        $this->execute(" alter TABLE vendor_menu_item_add_ons add column amountHalf double null default 0");
        $this->execute(" alter TABLE vendor_menu_item_add_ons add column amountSide double null default 0");
    }

    public function down()
    {
        echo "m160901_142522_add_addons_custom cannot be reverted.\n";

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
