<?php

use yii\db\Migration;
use app\models\User;

class m160712_042553_add_business_name extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'businessName', $this->string());
        $this->execute(" update user set businessName = firstName where role = ".User::ROLE_VENDOR);
        $this->execute(" update user set firstName = '' where role = ".User::ROLE_VENDOR);
    }

    public function down()
    {
        echo "m160712_042553_add_business_name cannot be reverted.\n";

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
