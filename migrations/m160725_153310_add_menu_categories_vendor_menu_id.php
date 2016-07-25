<?php

use yii\db\Migration;

class m160725_153310_add_menu_categories_vendor_menu_id extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE menu_categories add column vendorMenuId int(11) null;
			");
    }

    public function down()
    {
        echo "m160725_153310_add_menu_categories_vendor_menu_id cannot be reverted.\n";

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
