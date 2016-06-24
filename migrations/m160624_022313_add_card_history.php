<?php

use yii\db\Migration;

class m160624_022313_add_card_history extends Migration
{
    public function up()
    {
        $this->execute("  alter table user add column cardLast4 varchar(25) null;
                          alter table user add column cardExpiry varchar(25) null;        
			");
        
        $this->execute(" CREATE TABLE user_card_history
			(
			  id int(11) NOT NULL auto_increment,
			  userId int(11) not null,
              cardLast4 varchar(25) null,
              cardExpiry varchar(25) null,
              date_created datetime default null,
			  PRIMARY KEY (id)
			);
        
			");
                

        $this->execute("
           create table vendor_membership(
            id int(11) AUTO_INCREMENT NOT NULL,
            vendorId int(11) not null,
            startDate datetime not null,
            endDate datetime not null,
            transactionId varchar(500) not null,
            amount double not null,         
            cardLast4 varchar(25) null,   
            PRIMARY KEY (id)
            );
         ");
        
    }

    public function down()
    {
        echo "m160624_022313_add_card_history cannot be reverted.\n";

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
