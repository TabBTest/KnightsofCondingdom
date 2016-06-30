<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "promotion_user_status".
 *
 * @property integer $id
 * @property integer $vendorPromotionId
 * @property integer $userId
 * @property integer $status
 * @property string $date_created
 */
class PromotionUserStatus extends \yii\db\ActiveRecord
{
    const STATUS_IN_QUEUE = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_SENT = 3;
    const STATUS_FAILED = 4;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promotion_user_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendorPromotionId', 'userId'], 'required'],
            [['vendorPromotionId', 'userId', 'status'], 'integer'],
            [['date_created'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vendorPromotionId' => 'Vendor Promotion ID',
            'userId' => 'User ID',
            'status' => 'Status',
            'date_created' => 'Date Created',
        ];
    }
    
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if($this->isNewRecord)
                $this->date_created=date('Y-m-d H:i:s', strtotime('now'));
            return true;
        }else{
            return false;
        }
    }
    
}
