<?php

use yii\db\Migration;
use app\models\User;
use app\models\VendorMembership;
use app\helpers\TenantHelper;

class m160624_041829_add_initial_membership_setup extends Migration
{
    public function up()
    {
        $users = User::findAll(['role' => User::ROLE_VENDOR]);
        foreach($users as $user){
            if(VendorMembership::getActiveMembership($user->id) === false){
                TenantHelper::doMembershipPayment($user->id);
            }
        
        }
    }

    public function down()
    {
        echo "m160624_041829_add_initial_membership_setup cannot be reverted.\n";

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
