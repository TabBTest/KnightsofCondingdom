<?php

use yii\db\Migration;

class m160720_030532_add_vendor_app_config_override extends Migration
{
    public function up()
    {
        $this->execute(" CREATE TABLE vendor_app_config_override
			(
			  id int(11) NOT NULL auto_increment,
              vendorId int(11) not null,
              code varchar(255) not null,
              val varchar(255) not null,
		      date_created datetime default null,
			  date_updated datetime default null,
			  PRIMARY KEY (id)
			);
			");
    }

    public function down()
    {
        echo "m160720_030532_add_vendor_app_config_override cannot be reverted.\n";

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
