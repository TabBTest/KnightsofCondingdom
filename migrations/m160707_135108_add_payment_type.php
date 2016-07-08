<?php

use yii\db\Migration;
use app\models\Orders;

class m160707_135108_add_payment_type extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE orders add column paymentType int(11) null;");
        $this->execute(" alter TABLE orders add column isPaid int(11) null;");
        
        $this->execute(" update orders set isPaid = 1, paymentType = 1");
                
    }

    public function down()
    {
        echo "m160707_135108_add_payment_type cannot be reverted.\n";

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
