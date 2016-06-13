<?php

use yii\db\Migration;

class m160613_154703_add_password_reset extends Migration
{
    public function up()
    {
        $this->execute("alter TABLE user add column isPasswordReset int(11) null;");
    }

    public function down()
    {
        echo "m160613_154703_add_password_reset cannot be reverted.\n";

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
