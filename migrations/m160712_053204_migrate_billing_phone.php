<?php

use yii\db\Migration;
use app\models\User;

class m160712_053204_migrate_billing_phone extends Migration
{
    public function up()
    { 

        
        $allUsers = User::find()->where('')->all();
        foreach($allUsers as $user){
            $numbers = explode('-', $user->billingPhoneNumber);
            if(isset($numbers[0]))
                $user->billingPhoneAreaCode = $numbers[0];
            if(isset($numbers[1]))
                $user->billingPhone3 = $numbers[1];
            if(isset($numbers[2]))
                $user->billingPhone4 = $numbers[2];
            $user->save();
        }
        //$this->execute(" alter table user drop column billingPhoneNumber");
    }

    public function down()
    {
        echo "m160712_053204_migrate_billing_phone cannot be reverted.\n";

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
