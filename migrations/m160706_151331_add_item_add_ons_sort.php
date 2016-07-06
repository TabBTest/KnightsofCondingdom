<?php

use yii\db\Migration;

class m160706_151331_add_item_add_ons_sort extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE vendor_menu_item_add_ons add column sorting int(11) null;");
    }

    public function down()
    {
        echo "m160706_151331_add_item_add_ons_sort cannot be reverted.\n";

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
