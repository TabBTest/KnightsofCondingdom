<?php

use yii\db\Migration;

class m160901_143315_add_addons_custom_1 extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE vendor_menu_item_add_ons add column isSpecialFull int(11) null default 0;");
        $this->execute(" alter TABLE vendor_menu_item_add_ons add column isSpecialHalf int(11) null default 0;");
        $this->execute(" alter TABLE vendor_menu_item_add_ons add column isSpecialSide int(11) null default 0;");
        
    }

    public function down()
    {
        echo "m160901_143315_add_addons_custom_1 cannot be reverted.\n";

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
