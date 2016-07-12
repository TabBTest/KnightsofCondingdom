<?php

use yii\db\Migration;

class m160712_153553_add_store_close extends Migration
{
    public function up()
    {
        $this->execute(" alter table user add column isStoreClose int(11) null default 0;");
        $this->execute(" alter table user add column storeCloseReason text null;");
        
    }

    public function down()
    {
        echo "m160712_153553_add_store_close cannot be reverted.\n";

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
