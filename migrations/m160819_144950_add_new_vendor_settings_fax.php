<?php

use yii\db\Migration;

class m160819_144950_add_new_vendor_settings_fax extends Migration
{
    public function up()
    {
        $this->execute("
            alter TABLE orders add column faxStartTimeIsNA int(11 ) null default 0;
            alter TABLE orders add column faxConfirmTimeIsNA int(11 ) null default 0;
             alter TABLE orders add column faxPickupTimeIsNA int(11 ) null default 0;
			");
    }

    public function down()
    {
        echo "m160819_144950_add_new_vendor_settings_fax cannot be reverted.\n";

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
