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
    const CODE_DELIVERY_CHARGE = 'DELIVERY_CHARGE';
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
        $tenantCustom[self::CODE_SUBDOMAIN] = 'Subdomain';
        $tenantCustom[self::CODE_SUBDOMAIN_REDIRECT] = 'Subdomain Redirect';
        $tenantCustom[self::CODE_REDIRECT_URL] = 'Redirect URL';
        
        $tenantCustom[self::CODE_SALES_TAX] = 'Sales Tax (in percentage)';
        $tenantCustom[self::CODE_HAS_DELIVERY] = 'Has Delivery?';
        $tenantCustom[self::CODE_DELIVERY_CHARGE] = 'Delivery Charge Amount';

        return $tenantCustom;
    }
    
    public static function getCustomClasses($code){
        $tenantCustom = [];        
        $tenantCustom[self::CODE_SALES_TAX] = 'numeric';
        $tenantCustom[self::CODE_DELIVERY_CHARGE] = 'numeric';
        
        return isset($tenantCustom[$code]) ? $tenantCustom[$code] : '';
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
            $tenantInfo->val = str_replace(' ', '-', strtolower($user->name));
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
