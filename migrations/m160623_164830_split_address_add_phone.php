<?php

use yii\db\Migration;

class m160623_164830_split_address_add_phone extends Migration
{
    public function up()
    {
        $this->renameColumn('user', 'address', 'streetAddress');
        $this->addColumn('user', 'city', $this->string());
        $this->addColumn('user', 'state', $this->string(2));

        $this->renameColumn('user', 'billingAddress', 'billingStreetAddress');
        $this->addColumn('user', 'billingCity', $this->string());
        $this->addColumn('user', 'billingState', $this->string(2));
        $this->addColumn('user', 'billingPhoneNumber', $this->string());
    }

    public function down()
    {
        echo "m160623_134657_split_address_street_city_state cannot be reverted.\n";

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
