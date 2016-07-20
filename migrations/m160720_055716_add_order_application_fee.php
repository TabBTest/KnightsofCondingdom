<?php

use yii\db\Migration;

class m160720_055716_add_order_application_fee extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE orders add column paymentGatewayFee double null default 0;
		
			");
    }

    public function down()
    {
        echo "m160720_055716_add_order_application_fee cannot be reverted.\n";

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
