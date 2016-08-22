<?php

use yii\db\Migration;

class m160822_120238_add_order_fax_job_id extends Migration
{
    public function up()
    {
        $this->execute("
            alter TABLE orders add column faxJobId int(11 ) null default 0;
			");
    }

    public function down()
    {
        echo "m160822_120238_add_order_fax_job_id cannot be reverted.\n";

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
