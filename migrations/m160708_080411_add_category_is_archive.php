<?php

use yii\db\Migration;

class m160708_080411_add_category_is_archive extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE menu_categories add column isArchived int(11) null default 0;");
    }

    public function down()
    {
        echo "m160708_080411_add_category_is_archive cannot be reverted.\n";

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
