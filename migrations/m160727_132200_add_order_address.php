<?php

use yii\db\Migration;

class m160727_132200_add_order_address extends Migration
{
    public function up()
    {
        $this->execute("
            alter TABLE orders add column deliveryAddress varchar(250) null;
            alter TABLE orders add column deliveryCity varchar(250) null;
            alter TABLE orders add column deliveryState varchar(250) null;
        
			");
    }

    public function down()
    {
        echo "m160727_132200_add_order_address cannot be reverted.\n";

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
