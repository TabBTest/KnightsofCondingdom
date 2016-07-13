<?php

use yii\db\Migration;

/**
 * Handles adding time to table `pickup`.
 */
class m160712_160850_add_time_to_pickup extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        //$this->execute(" alter table user add column timeToPickUp int(11) null default 1;");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
    }
}
