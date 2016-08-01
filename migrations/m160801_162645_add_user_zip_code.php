<?php

use yii\db\Migration;

class m160801_162645_add_user_zip_code extends Migration
{
    public function up()
    {
        $this->execute("
            alter TABLE user add column postalCode varchar(25) null;
        
			");
    }

    public function down()
    {
        echo "m160801_162645_add_user_zip_code cannot be reverted.\n";

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
