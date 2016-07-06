<?php

use yii\db\Migration;

class m160706_145748_add_item_add_ons extends Migration
{
    public function up()
    {
        $this->execute("
		  CREATE TABLE vendor_menu_item_add_ons
			(
			  id int(11) NOT NULL auto_increment,
			  vendorMenuItemId int(11) NOT NULL,
			  name varchar(250) not null,
              description text null,
              amount double not null,
		      date_created datetime default null,
			  date_updated datetime default null,
			  PRIMARY KEY (id)
			);
			");
    }

    public function down()
    {
        echo "m160706_145748_add_item_add_ons cannot be reverted.\n";

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
