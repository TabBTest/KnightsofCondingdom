<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_fax".
 *
 * @property integer $id
 * @property integer $orderId
 * @property integer $faxJobId
 * @property integer $isFaxSent
 * @property string $date_created
 */
class OrderFax extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_fax';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderId'], 'required'],
            [['orderId', 'faxJobId', 'isFaxSent'], 'integer'],
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
            'orderId' => 'Order ID',
            'faxJobId' => 'Fax Job ID',
            'isFaxSent' => 'Is Fax Sent',
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
