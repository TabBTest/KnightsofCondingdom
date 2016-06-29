<?php

use yii\db\Migration;

class m160629_155234_add_promo_table extends Migration
{
    public function up()
    {
        $this->execute(" CREATE TABLE vendor_promotion
			(
			  id int(11) NOT NULL auto_increment,
			  vendorId int(11) not null,
              html  text null,
              date_created datetime default null,
			  PRIMARY KEY (id)
			);
        
			");
    }

    public function down()
    {
        echo "m160629_155234_add_promo_table cannot be reverted.\n";

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
