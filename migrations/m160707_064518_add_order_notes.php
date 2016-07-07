<?php

use yii\db\Migration;
use app\models\OrderDetails;

class m160707_064518_add_order_notes extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE orders add column notes text null;");
        $this->execute(" alter TABLE order_details add column notes text null;");
        $this->execute(" alter TABLE order_details add type int(11) null;");
        $this->execute(" update order_details set  type = ".OrderDetails::TYPE_MENU_ITEM);
    }

    public function down()
    {
        echo "m160707_064518_add_order_notes cannot be reverted.\n";

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
