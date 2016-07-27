<?php

use yii\db\Migration;

class m160727_125450_add_order_custom_order_billing extends Migration
{
    public function up()
    {
        $this->execute(" 
            alter TABLE orders add column customBillingName varchar(250) null;
            alter TABLE orders add column customBillingAddress varchar(250) null;
            alter TABLE orders add column customBillingCity varchar(250) null;
            alter TABLE orders add column customBillingState varchar(250) null;
            alter TABLE orders add column customBillingCardLast4 varchar(250) null;
            
			");
    }

    public function down()
    {
        echo "m160727_125450_add_order_custom_order_billing cannot be reverted.\n";

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
