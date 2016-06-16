<?php

use yii\db\Migration;

class m160616_121618_add_order_transaction_id extends Migration
{
    public function up()
    {
        $this->execute("alter table orders add column transactionId varchar(250) null;");
    }

    public function down()
    {
        echo "m160616_121618_add_order_transaction_id cannot be reverted.\n";

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
