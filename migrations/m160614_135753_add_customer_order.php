<?php

use yii\db\Migration;

class m160614_135753_add_customer_order extends Migration
{
    public function up()
    {
        $this->execute(" CREATE TABLE orders
			(
			  id int(11) NOT NULL auto_increment,
			  customerId int(11) not null,
			  vendorId int(11) not null,
              status int(11) not null,
              confirmedDateTime datetime default null,
              startDateTime datetime default null,
              pickedUpDateTime datetime default null,
              date_created datetime default null,
			  PRIMARY KEY (id)
			);
		 
			");
        
        $this->execute(" CREATE TABLE order_details
			(
			  id int(11) NOT NULL auto_increment,
			  orderId int(11) not null,
			  vendorMenuItemId int(11) not null,
              name varchar(250) not null,
              amount double not null,  
              quantity int(11) not null,
              totalAmount double not null,
              date_created datetime default null,
			  PRIMARY KEY (id)
			);
		
			");
    }

    public function down()
    {
        echo "m160614_135753_add_customer_order cannot be reverted.\n";

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
