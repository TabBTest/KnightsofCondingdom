<?php

use yii\db\Migration;

class m160613_131732_add_initial_tables extends Migration
{
    public function up()
    {
        $this->execute("DROP TABLE IF EXISTS user;
	    	CREATE TABLE user
			(
			  id int(11) NOT NULL auto_increment,
			  email varchar(250) NOT NULL,
			  password varchar(250) NOT NULL,
              role int(11) not null,
            
              name varchar(250) null,
              address varchar(250) null,
              phoneNumber varchar(250) null,
              
              billingName varchar(250) null,
              billingAddress varchar(250) null,
            
              vendorId int(11) null,
            
		      date_created datetime default null,
			  date_updated datetime default null,
			  PRIMARY KEY (id)
			);
		  	
			");
    }

    public function down()
    {
        echo "m160613_131732_add_initial_tables cannot be reverted.\n";

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
