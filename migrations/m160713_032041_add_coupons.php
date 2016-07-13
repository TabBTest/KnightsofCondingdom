<?php

use yii\db\Migration;

class m160713_032041_add_coupons extends Migration
{
    public function up()
    {
        $this->execute(" CREATE TABLE vendor_coupons
			(
			  id int(11) NOT NULL auto_increment,
              code varchar(250) not null,
              description varchar(2500) null,
			  vendorId int(11) not null,
              isArchived int(11) null default 0,
              discountType int(11) not null,
              discount double not null,
		      date_created datetime default null,
			  PRIMARY KEY (id)
			);		  
			");
    }

    public function down()
    {
        echo "m160713_032041_add_coupons cannot be reverted.\n";

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
