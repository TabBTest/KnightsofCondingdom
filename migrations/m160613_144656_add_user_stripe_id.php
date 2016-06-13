<?php

use yii\db\Migration;

class m160613_144656_add_user_stripe_id extends Migration
{
    public function up()
    {
        $this->execute("alter TABLE user add column stripeId varchar(250) null;");
    }

    public function down()
    {
        echo "m160613_144656_add_user_stripe_id cannot be reverted.\n";

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
