<?php

use yii\db\Migration;

class m160725_164751_add_default_time extends Migration
{
    public function up()
    {
        $this->execute(" update vendor_menu set startTime = '00:00', endTime = '24:00' where startTime is null;
			");
    }

    public function down()
    {
        echo "m160725_164751_add_default_time cannot be reverted.\n";

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
