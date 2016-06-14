<?php

use yii\db\Migration;
use app\models\User;
use app\models\TenantInfo;

class m160614_120553_add_setup_for_customers extends Migration
{
    public function up()
    {
        $users = User::findAll(['role' => User::ROLE_VENDOR]);
        foreach($users as $user){
            TenantInfo::addCustomSubdomain($user);
        }
    }

    public function down()
    {
        echo "m160614_120553_add_setup_for_customers cannot be reverted.\n";

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
