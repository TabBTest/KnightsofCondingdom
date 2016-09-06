<?php

use yii\db\Migration;

class m160906_020107_add_fax_attempts extends Migration
{
    public function up()
    {
        $this->execute(" CREATE TABLE order_fax
			(
			  id int(11) NOT NULL auto_increment,
              orderId int(11) not null,
              faxJobId int(11) null default 0,
              isFaxSent int(11 ) null default 0,
			  date_created datetime default null,
			  PRIMARY KEY (id)
			);
			");
    }

    public function down()
    {
        echo "m160906_020107_add_fax_attempts cannot be reverted.\n";

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
