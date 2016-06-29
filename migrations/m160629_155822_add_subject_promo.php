<?php

use yii\db\Migration;

class m160629_155822_add_subject_promo extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE vendor_promotion add column subject varchar(250) not null;
			");
    }

    public function down()
    {
        echo "m160629_155822_add_subject_promo cannot be reverted.\n";

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
