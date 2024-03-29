<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tenant_info".
 *
 * @property integer $id
 * @property string $code
 * @property integer $userId
 * @property string $val
 * @property string $date_created
 */
class TenantInfo extends \yii\db\ActiveRecord
{
    const CODE_SUBDOMAIN = 'SUBDOMAIN';
    const CODE_SUBDOMAIN_REDIRECT = 'SUBDOMAIN_REDIRECT';
    const CODE_REDIRECT_URL = 'REDIRECT_URL';
    const CODE_SALES_TAX = 'SALES_TAX';
    const CODE_HAS_DELIVERY = 'HAS_DELIVERY';
    const CODE_DELIVERY_MINIMUM_AMOUNT = 'DELIVERY_MINIMUM_AMOUNT';
    const CODE_DELIVERY_CHARGE = 'DELIVERY_CHARGE';
    
    const CODE_SEND_FAX_ON_ORDER = 'SEND_FAX_ON_ORDER';
    const CODE_FAX_CONFIRM_TIME_IS_NA = 'FAX_CONFIRM_TIME_IS_NA';
    const CODE_FAX_START_TIME_IS_NA = 'FAX_START_TIME_IS_NA';
    const CODE_FAX_PICKUP_TIME_IS_NA = 'FAX_PICKUP_TIME_IS_NA';
    const CODE_FAX_NUMBER= 'FAX_NUMBER';
    
    
    const CODE_TAG_LINE = 'TAG_LINE';
    const CODE_EXISTING_URL = 'EXISTING_URL';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tenant_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'userId'], 'required'],
            [['userId'], 'integer'],
            [['date_created'], 'safe'],
            [['code'], 'string', 'max' => 250],
            [['val'], 'string', 'max' => 500],
        ];
    }

    public static function getTenantCodes(){
        $tenantCustom = [];
        $tenantCustom[self::CODE_TAG_LINE] = 'Tag Line';
        $tenantCustom[self::CODE_EXISTING_URL] = 'Existing Website';
        
        $tenantCustom[self::CODE_SUBDOMAIN] = 'Subdomain';
        $tenantCustom[self::CODE_SUBDOMAIN_REDIRECT] = 'Subdomain Redirect';
        $tenantCustom[self::CODE_REDIRECT_URL] = 'Redirect URL';
        
        $tenantCustom[self::CODE_SALES_TAX] = 'Sales Tax (in percentage)';
        $tenantCustom[self::CODE_HAS_DELIVERY] = 'Do you have delivery?';
        $tenantCustom[self::CODE_DELIVERY_MINIMUM_AMOUNT] = 'Delivery Minimum Amount';
        $tenantCustom[self::CODE_DELIVERY_CHARGE] = 'Delivery Charge Amount';
        
        $tenantCustom[self::CODE_SEND_FAX_ON_ORDER] = 'Send Fax on New Order?';
        $tenantCustom[self::CODE_FAX_NUMBER] = 'Fax Number';
        $tenantCustom[self::CODE_FAX_CONFIRM_TIME_IS_NA] = 'Is Fax Confirm Time N/A?';
        $tenantCustom[self::CODE_FAX_START_TIME_IS_NA] = 'Is Fax Start Time N/A?';
        $tenantCustom[self::CODE_FAX_PICKUP_TIME_IS_NA] = 'Is Fax Pickup Time N/A?';
        
       

        return $tenantCustom;
    }
    
    public static function getCustomClasses($code){
        $tenantCustom = [];        
        $tenantCustom[self::CODE_SALES_TAX] = 'numeric xs-input';
        $tenantCustom[self::CODE_DELIVERY_CHARGE] = 'numeric short-input';
        $tenantCustom[self::CODE_DELIVERY_MINIMUM_AMOUNT] = 'numeric short-input';
        $tenantCustom[self::CODE_FAX_NUMBER] = 'short-input fax-number';
        
        $tenantCustom[self::CODE_SUBDOMAIN] = 'short-input';
        
        
        return isset($tenantCustom[$code]) ? $tenantCustom[$code] : 'long-input';
    }
    
    public static function isDollarAmount($code){
        $tenantCustom = [];        
        $tenantCustom[self::CODE_DELIVERY_CHARGE] = true;
        $tenantCustom[self::CODE_DELIVERY_MINIMUM_AMOUNT] = true;
    
    
        return isset($tenantCustom[$code]) ? $tenantCustom[$code] : false;
    }
    
    public static function isPercentage($code){
        $tenantCustom = [];
        $tenantCustom[self::CODE_SALES_TAX] = true;
    
    
        return isset($tenantCustom[$code]) ? $tenantCustom[$code] : false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'userId' => 'User ID',
            'val' => 'Val',
            'date_created' => 'Date Created',
        ];
    }

    public static function getTenantValue($userId, $code){
        $tenantInfo = TenantInfo::findOne(['userId' => $userId, 'code' => $code]);
        if($tenantInfo){
            return $tenantInfo->val;
        }
        return '';
    }

    public static function addCustomSubdomain($user){
        $tenantInfo = TenantInfo::findOne(['userId' => $user->id, 'code' => self::CODE_SUBDOMAIN]);
        if($tenantInfo == null){
            $tenantInfo = new TenantInfo();
            $tenantInfo->code = self::CODE_SUBDOMAIN;
            $tenantInfo->userId = $user->id;
            $tenantInfo->val = str_replace(' ', '-', strtolower($user->businessName));
            $tenantInfo->save();
        }
    }

    public static function isValidSubDomain($subDomain){
        $tenantInfo = TenantInfo::findOne(['code' => self::CODE_SUBDOMAIN, 'val' => $subDomain]);
        if($tenantInfo){
            return $tenantInfo;
        }
        return false;
    }

    public static function findOrCreate($userId, $code)
    {
        $tenantInfo = TenantInfo::findOne(['userId' => $userId, 'code' => $code]);
        return ($tenantInfo ? $tenantInfo : new TenantInfo());
    }

    public static function isUniqueSubdomain($userId, $subdomain)
    {
        $tenantInfo = TenantInfo::find()->where(['code' => self::CODE_SUBDOMAIN, 'val' => $subdomain])->andWhere(['not', ['userId' => $userId]])->one();
        return ($tenantInfo ? false : true);
    }
}
