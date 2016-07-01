<?php

use yii\db\Migration;

class m160701_090331_add_vendor_operating_hours extends Migration
{
    public function up()
    {
        $this->execute(" CREATE TABLE vendor_operating_hours
			(
			  id int(11) NOT NULL auto_increment,
              vendorId int(11) not null,
			  day int(11) not null,
              startTime varchar(10) not null,
              endTime varchar(10) not null,
              date_created datetime default null,
			  PRIMARY KEY (id)
			);
        
			");
    }

    public function down()
    {
        echo "m160701_090331_add_vendor_operating_hours cannot be reverted.\n";

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
