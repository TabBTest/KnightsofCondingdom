<?php

use yii\db\Migration;

/**
 * Handles the dropping for table `user_phone`.
 */
class m160712_053041_drop_user_phone extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        //$this->execute(" alter table user drop column phoneNumber");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->createTable('user_phone', [
            'id' => $this->primaryKey(),
        ]);
    }
}
