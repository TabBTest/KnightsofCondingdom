<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vendor_promotion".
 *
 * @property integer $id
 * @property integer $vendorId
 * @property string $html
 * @property string $date_created
 */
class VendorPromotion extends \yii\db\ActiveRecord
{
    const TYPE_EMAIL = 1;
    const TYPE_SMS = 2;
    
    const SEND_TO_SELF = 0;
    const SEND_TO_CUSTOMERS = 1;
    const SEND_TO_VENDORS = 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vendor_promotion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendorId', 'subject'], 'required'],
            [['vendorId'], 'integer'],
            [['html'], 'string'],
            [['date_created', 'promoType', 'sendToType', 'isAdmin'], 'safe'],
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->date_created=date('Y-m-d H:i:s', strtotime('now'));
            return true;
        }else{
            return false;
        }
    }
    
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vendorId' => 'Vendor ID',
            'html' => 'Html',
            'date_created' => 'Date Created',
        ];
    }
    
    public static function getPromoEmails($userId, $resultsPerPage, $page){
        $resp = array();
        $resp['list'] = VendorPromotion::find()->where('promoType = '.self::TYPE_EMAIL.' and vendorId = '.$userId.' order by id desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = VendorPromotion::find()->where('promoType = '.self::TYPE_EMAIL.' and vendorId = '.$userId)->count();
         
        return $resp;
    }
    
    public static function getPromoSms($userId, $resultsPerPage, $page){
        $resp = array();
        $resp['list'] = VendorPromotion::find()->where('promoType = '.self::TYPE_SMS.' and vendorId = '.$userId.' order by id desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = VendorPromotion::find()->where('promoType = '.self::TYPE_SMS.' and vendorId = '.$userId)->count();
         
        return $resp;
    }
}
