<?php

use yii\db\Migration;

class m160623_135619_add_menu_groupings extends Migration
{
    public function up()
    {
        $this->execute(" CREATE TABLE menu_categories
			(
			  id int(11) NOT NULL auto_increment,
			  vendorId int(11) not null,
              name varchar(250) not null,
              description text null,
              sorting int(11) null,
              date_created datetime default null,
			  PRIMARY KEY (id)
			);
		
			");
        
        $this->execute("  alter table vendor_menu_item add column menuCategoryId int(11) null;
            alter table vendor_menu_item add column sorting int(11) null;
        
			");
    }

    public function down()
    {
        echo "m160623_135619_add_menu_groupings cannot be reverted.\n";

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
