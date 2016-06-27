<?php

use yii\db\Migration;

class m160627_164811_add_user_is_active extends Migration
{
    public function up()
    {
        $this->execute("  alter table user add column isActive int(11) default 1;
			");
    }

    public function down()
    {
        echo "m160627_164811_add_user_is_active cannot be reverted.\n";

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
