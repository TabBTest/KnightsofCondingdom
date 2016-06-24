<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vendor_membership".
 *
 * @property integer $id
 * @property integer $vendorId
 * @property string $startDate
 * @property string $endDate
 * @property string $transactionId
 * @property double $amount
 * @property string $cardLast4
 */
class VendorMembership extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vendor_membership';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendorId', 'startDate', 'endDate', 'transactionId', 'amount'], 'required'],
            [['vendorId'], 'integer'],
            [['startDate', 'endDate'], 'safe'],
            [['amount'], 'number'],
            [['transactionId'], 'string', 'max' => 500],
            [['cardLast4'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vendorId' => 'Vendor ID',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
            'transactionId' => 'Transaction ID',
            'amount' => 'Amount',
            'cardLast4' => 'Card Last4',
        ];
    }
    
    public static function getActiveMembership($userId){
    
        $memberShips = VendorMembership::find()->where("vendorId = ".$userId." and startDate <= '".date('Y-m-d', strtotime('now'))."' and endDate >= '".date('Y-m-d', strtotime('now'))."'")->orderBy('id desc')->all();
        if($memberShips != null){
            return $memberShips[0];
        }
        return false;
    
    }
    public static function getLastActiveMembership($userId){
    
        $memberShips = VendorMembership::find()->where("vendorId = ".$userId." and endDate >= '".date('Y-m-d', strtotime('now'))."'")->orderBy('id desc')->all();
        if($memberShips != null){
            return $memberShips[0];
        }
        return false;
    
    }
    
    public static function getVendorMemberships($userId, $resultsPerPage, $page){
        $resp = array();
        $resp['list'] = VendorMembership::find()->where('vendorId = '.$userId.' order by id desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = VendorMembership::find()->where('vendorId = '.$userId)->count();
        return $resp;
    }
}
