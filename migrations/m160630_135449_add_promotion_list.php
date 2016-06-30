<?php

use yii\db\Migration;

class m160630_135449_add_promotion_list extends Migration
{
    public function up()
    {
        $this->execute(" CREATE TABLE promotion_user_status
			(
			  id int(11) NOT NULL auto_increment,
              vendorPromotionId int(11) not null,
			  userId int(11) not null,
              status int(11) null,
              date_created datetime default null,
			  PRIMARY KEY (id)
			);
        
			");
        
        $this->execute(" alter table vendor_promotion add column sendToType int(11) null;
        
			");
    }

    public function down()
    {
        echo "m160630_135449_add_promotion_list cannot be reverted.\n";

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
