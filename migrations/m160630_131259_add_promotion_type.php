<?php

use yii\db\Migration;

class m160630_131259_add_promotion_type extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE vendor_promotion add column promoType int(11) null;
			");
        
        $this->execute(" update vendor_promotion set promoType = 1;
			");
    }

    public function down()
    {
        echo "m160630_131259_add_promotion_type cannot be reverted.\n";

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
