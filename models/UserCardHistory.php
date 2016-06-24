<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_card_history".
 *
 * @property integer $id
 * @property integer $userId
 * @property string $cardLast4
 * @property string $cardExpiry
 * @property string $date_created
 */
class UserCardHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_card_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId'], 'required'],
            [['userId'], 'integer'],
            [['date_created'], 'safe'],
            [['cardLast4', 'cardExpiry'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'cardLast4' => 'Card Last4',
            'cardExpiry' => 'Card Expiry',
            'date_created' => 'Date Created',
        ];
    }
}
