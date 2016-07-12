<?php

use yii\db\Migration;

class m160712_052553_add_phone_fields extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'phoneAreaCode', $this->string());
        $this->addColumn('user', 'phone3', $this->string());
        $this->addColumn('user', 'phone4', $this->string());
        
        $this->addColumn('user', 'billingPhoneAreaCode', $this->string());
        $this->addColumn('user', 'billingPhone3', $this->string());
        $this->addColumn('user', 'billingPhone4', $this->string());
    }

    public function down()
    {
        echo "m160712_052553_add_phone_fields cannot be reverted.\n";

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
