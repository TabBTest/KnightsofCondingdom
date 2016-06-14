<?php

use yii\db\Migration;
use app\models\User;
use app\helpers\UtilityHelper;

class m160613_133523_add_admin_user extends Migration
{
    public function up()
    {     
        $user = new User();
        $user->role = User::ROLE_ADMIN;
        $user->email = 'admin';
        $user->password = UtilityHelper::cryptPass('adminpassword');
        $user->save(false);
    }

    public function down()
    {
        echo "m160613_133523_add_admin_user cannot be reverted.\n";

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
