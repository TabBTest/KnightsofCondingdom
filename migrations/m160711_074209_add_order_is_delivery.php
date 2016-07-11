<?php

use yii\db\Migration;

class m160711_074209_add_order_is_delivery extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE orders add column isDelivery int(11) null default 0;");
    }

    public function down()
    {
        echo "m160711_074209_add_order_is_delivery cannot be reverted.\n";

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
