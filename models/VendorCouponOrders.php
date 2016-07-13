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
    public function getOrder(){
        return Orders::findOne($this->orderId);
    }
    public static function getOrders($id, $resultsPerPage, $page, $filters){
    
        $extraSQL = '';
        /*
        if(isset($filters['showCompleted'])){
            if( $filters['showCompleted'] == 0){
                $extraSQL .= ' and status != '.self::STATUS_PROCESSED;
            }
        }else{
            $extraSQL .= ' and status != '.self::STATUS_PROCESSED;
        }
    
        if(isset($filters['firstName']) && $filters['firstName'] != ''){
            $extraSQL .= " and customerId in (select id from user where firstName like '%".mysql_escape_string($filters['firstName'])."%')";
        }
        if(isset($filters['lastName']) && $filters['lastName'] != ''){
            $extraSQL .= " and customerId in (select id from user where lastName like '%".mysql_escape_string($filters['lastName'])."%')";
        }
    
        if(isset($filters['orderId']) && $filters['orderId'] != ''){
            $extraSQL .= " and (id + ".self::MAGIC_NUMBER.") =  '".$filters['orderId']."'";
        }
        if(isset($filters['isDelivery']) && $filters['isDelivery'] != ''){
            $extraSQL .= " and isDelivery = ".$filters['isDelivery'];
        }
        */
        $resp = array();
        $resp['list'] = VendorCouponOrders::find()->where('vendorCouponId = '.$id.' '.$extraSQL.' order by id desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = VendorCouponOrders::find()->where('vendorCouponId = '.$id.' '.$extraSQL)->count();
        return $resp;
    }
}
