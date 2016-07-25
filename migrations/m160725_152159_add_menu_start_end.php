<?php

use yii\db\Migration;

class m160725_152159_add_menu_start_end extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE vendor_menu add column startTime varchar(25) null;
          alter TABLE vendor_menu add column endTime varchar(25) null;
			");
    }

    public function down()
    {
        echo "m160725_152159_add_menu_start_end cannot be reverted.\n";

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
