<?php

use yii\db\Migration;

class m160614_114942_add_tenant_info extends Migration
{
    public function up()
    {
         $this->execute(" CREATE TABLE tenant_info
			(
			  id int(11) NOT NULL auto_increment,
			  code varchar(250) NOT NULL,
			  userId int(11) not null,              
              val varchar(500) null,
              date_created datetime default null,			  
			  PRIMARY KEY (id)
			);
		  	
			");
    }

    public function down()
    {
        echo "m160614_114942_add_tenant_info cannot be reverted.\n";

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
