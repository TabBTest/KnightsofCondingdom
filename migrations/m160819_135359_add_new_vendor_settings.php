<?php

use yii\db\Migration;

class m160819_135359_add_new_vendor_settings extends Migration
{
    public function up()
    {
        $this->execute("
            alter TABLE orders add column isFaxOrder int(11 ) null default 0;
            alter TABLE orders add column isFaxSent int(11 ) null default 0;
            alter TABLE orders add column faxSentDate datetime null;
			");
    }

    public function down()
    {
        echo "m160819_135359_add_new_vendor_settings cannot be reverted.\n";

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
