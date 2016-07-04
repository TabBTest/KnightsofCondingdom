<?php

use yii\db\Migration;

class m160704_061211_add_user_timezone extends Migration
{
    public function up()
    {
        $this->execute(" alter table user add column timezone varchar(250) null;        
			");
        
        $this->execute(" update user set  timezone = 'UTC';
			");
    }

    public function down()
    {
        echo "m160704_061211_add_user_timezone cannot be reverted.\n";

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
