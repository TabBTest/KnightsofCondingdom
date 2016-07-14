<?php

use yii\db\Migration;

class m160714_050420_add_admin_promos extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE vendor_promotion add column isAdmin int(11) null default 0;");
    }

    public function down()
    {
        echo "m160714_050420_add_admin_promos cannot be reverted.\n";

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
