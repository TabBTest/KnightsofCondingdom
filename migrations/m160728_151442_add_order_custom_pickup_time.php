<?php

use yii\db\Migration;

class m160728_151442_add_order_custom_pickup_time extends Migration
{
    public function up()
    {
        $this->execute("
            alter TABLE orders add column isAdvanceOrder int(11) null default 0;
            alter TABLE orders add column advancePickupDeliveryTime datetime null;
        
			");
    }

    public function down()
    {
        echo "m160728_151442_add_order_custom_pickup_time cannot be reverted.\n";

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
