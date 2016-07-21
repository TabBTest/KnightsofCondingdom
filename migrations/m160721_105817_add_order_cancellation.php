<?php

use yii\db\Migration;

class m160721_105817_add_order_cancellation extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE orders add column isCancelled int(11) null default 0;
            alter TABLE orders add column isRefunded int(11) null default 0;
        alter TABLE orders add column cancellation_date datetime null;
        alter TABLE orders add column refund_date datetime null;
			");
    }

    public function down()
    {
        echo "m160721_105817_add_order_cancellation cannot be reverted.\n";

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
