<?php

use yii\db\Migration;

class m160613_173108_add_menu_item_is_deleted extends Migration
{
    public function up()
    {
        $this->execute(" 
		  alter TABLE vendor_menu_item add column isArchived int(11) null;
			");
    }

    public function down()
    {
        echo "m160613_173108_add_menu_item_is_deleted cannot be reverted.\n";

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
