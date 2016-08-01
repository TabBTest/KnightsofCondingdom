<?php

use yii\db\Migration;

class m160801_121005_add_user_cim_token extends Migration
{
    public function up()
    {
        $this->execute("
            alter TABLE user add column cimToken varchar(250) null;
        
			");
    }

    public function down()
    {
        echo "m160801_121005_add_user_cim_token cannot be reverted.\n";

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
