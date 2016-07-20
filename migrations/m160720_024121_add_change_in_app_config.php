<?php

use yii\db\Migration;

class m160720_024121_add_change_in_app_config extends Migration
{
    public function up()
    {
        $this->execute(" update app_config set name = 'Web Fee' where code = 'ADMIN_FEE'");
        
    }

    public function down()
    {
        echo "m160720_024121_add_change_in_app_config cannot be reverted.\n";

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
