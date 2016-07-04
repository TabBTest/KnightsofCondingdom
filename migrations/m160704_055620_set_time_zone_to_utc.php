<?php

use yii\db\Migration;

class m160704_055620_set_time_zone_to_utc extends Migration
{
    public function up()
    {
        \Yii::$app->db->createCommand("set time_zone = '+00:00';")->execute();
    }

    public function down()
    {
        echo "m160704_055620_set_time_zone_to_utc cannot be reverted.\n";

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
