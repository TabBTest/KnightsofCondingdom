<?php

use yii\db\Migration;

class m160708_000150_add_app_config extends Migration
{
    public function up()
    {
        $this->execute("
            
            CREATE TABLE app_config
			(
			  id int(11) NOT NULL auto_increment,
              code varchar(255) not null,
        	  name varchar(255) not null,
              val varchar(255) not null,
		      date_created datetime default null,
			  date_updated datetime default null,
			  PRIMARY KEY (id)
			);
         ");
    }

    public function down()
    {
        echo "m160708_000150_add_app_config cannot be reverted.\n";

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
