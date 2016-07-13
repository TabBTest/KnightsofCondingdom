<?php

use yii\db\Migration;
use app\models\User;

class m160712_052817_migrate_phone extends Migration
{
    public function up()
    {
        $this->execute(" alter table user add column timeToPickUp int(11) null default 1;");
        $this->execute(" alter table user add column isStoreOpen int(11) null default 1;");
        $this->execute(" alter table user add column storeCloseReason text null;");
        
        $allUsers = User::find()->where('')->all();
        foreach($allUsers as $user){
            $numbers = explode('-', $user->phoneNumber);
            if(isset($numbers[0]))
                $user->phoneAreaCode = $numbers[0];
            if(isset($numbers[1]))
                $user->phone3 = $numbers[1];
            if(isset($numbers[2]))
                $user->phone4 = $numbers[2];
            $user->save();
        }
    }

    public function down()
    {
        echo "m160712_052817_migrate_phone cannot be reverted.\n";

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
