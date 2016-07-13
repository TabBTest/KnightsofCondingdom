<?php

use yii\db\Migration;

class m160713_061114_add_coupons_orders extends Migration
{
    public function up()
    {
        $this->execute(" CREATE TABLE vendor_coupon_orders
			(
			  id int(11) NOT NULL auto_increment,
              vendorCouponId int(11) not null,
              orderId int(11) not null,
		      date_created datetime default null,
			  PRIMARY KEY (id)
			);
			");
    }

    public function down()
    {
        echo "m160713_061114_add_coupons_orders cannot be reverted.\n";

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
