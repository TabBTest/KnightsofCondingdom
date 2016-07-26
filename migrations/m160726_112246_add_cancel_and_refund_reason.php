<?php

use yii\db\Migration;

class m160726_112246_add_cancel_and_refund_reason extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE orders add column cancelReason varchar(2000) null;
                        alter TABLE orders add column refundReason varchar(2000) null;
                         alter TABLE orders add column cancelledByUserId int(11) null;
                         alter TABLE orders add column refundedByUserId int(11) null;
             alter TABLE orders add column refundTransactionId varchar(250) null;
            
			");
    }

    public function down()
    {
        echo "m160726_112246_add_cancel_and_refund_reason cannot be reverted.\n";

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
