<?php

use yii\db\Migration;

class m160708_001427_add_app_configurations extends Migration
{
    public function up()
    {
        $this->execute("
            insert into app_config (code, name, val) values ('ADMIN_FEE', 'Admin Fee', '10');
            insert into app_config (code, name, val) values ('MONTHLY_MEMBERSHIP_FEE', 'Monthly Membership Fee', '34.99');
         ");
    }

    public function down()
    {
        echo "m160708_001427_add_app_configurations cannot be reverted.\n";

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
