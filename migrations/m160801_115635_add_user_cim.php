<?php

use yii\db\Migration;

class m160801_115635_add_user_cim extends Migration
{
    public function up()
    {
        $this->execute("
            alter TABLE user add column paymentProfileId varchar(250) null;
        
			");
    }

    public function down()
    {
        echo "m160801_115635_add_user_cim cannot be reverted.\n";

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
