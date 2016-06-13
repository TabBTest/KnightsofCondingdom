<?php

use yii\db\Migration;

class m160613_162233_add_vendor_menu extends Migration
{
    public function up()
    {
        $this->execute(" CREATE TABLE vendor_menu
			(
			  id int(11) NOT NULL auto_increment,
			  name varchar(250) NOT NULL,
			  isDefault int(11) not null,
              vendorId int(11) not null,                              
		      date_created datetime default null,
			  date_updated datetime default null,
			  PRIMARY KEY (id)
			);
		  CREATE TABLE vendor_menu_item
			(
			  id int(11) NOT NULL auto_increment,
			  vendorMenuId int(11) NOT NULL,
			  name varchar(250) not null,
              description text null,
              photo text null,
              amount double not null,                                            
		      date_created datetime default null,
			  date_updated datetime default null,
			  PRIMARY KEY (id)
			);
			");
    }

    public function down()
    {
        echo "m160613_162233_add_vendor_menu cannot be reverted.\n";

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
