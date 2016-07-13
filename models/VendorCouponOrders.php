<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vendor_coupon_orders".
 *
 * @property integer $id
 * @property integer $vendorCouponId
 * @property integer $orderId
 * @property string $date_created
 */
class VendorCouponOrders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vendor_coupon_orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendorCouponId', 'orderId'], 'required'],
            [['vendorCouponId', 'orderId'], 'integer'],
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
            'vendorCouponId' => 'Vendor Coupon ID',
            'orderId' => 'Order ID',
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
