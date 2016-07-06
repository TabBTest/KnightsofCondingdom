<?php

use yii\db\Migration;

class m160706_085019_add_vendor_order_now_button extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'orderButtonImage', $this->string());
    }

    public function down()
    {
        echo "m160706_085019_add_vendor_order_now_button cannot be reverted.\n";

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
