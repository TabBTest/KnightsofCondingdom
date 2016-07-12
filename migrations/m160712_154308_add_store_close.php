<?php

use yii\db\Migration;

class m160712_154308_add_store_close extends Migration
{
    public function up()
    {
        $this->execute(" alter table user add column isStoreOpen int(11) null default 1;");
        $this->execute(" alter table user drop column isStoreClose;");
    }

    public function down()
    {
        echo "m160712_154308_add_store_close cannot be reverted.\n";

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
