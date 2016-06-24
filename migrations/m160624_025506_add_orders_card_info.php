<?php

use yii\db\Migration;

class m160624_025506_add_orders_card_info extends Migration
{
    public function up()
    {
        $this->execute("  alter table orders add column cardLast4 varchar(25) null;
			");
    }

    public function down()
    {
        echo "m160624_025506_add_orders_card_info cannot be reverted.\n";

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
