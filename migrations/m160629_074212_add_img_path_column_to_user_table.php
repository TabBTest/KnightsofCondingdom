<?php

use yii\db\Migration;

/**
 * Handles adding img_path_column to table `user_table`.
 */
class m160629_074212_add_img_path_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'imageFile', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
    }
}
