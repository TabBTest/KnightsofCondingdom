<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vendor_app_config_override".
 *
 * @property integer $id
 * @property integer $vendorId
 * @property string $code
 * @property string $name
 * @property string $val
 * @property string $date_created
 * @property string $date_updated
 */
class VendorAppConfigOverride extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vendor_app_config_override';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendorId', 'code', 'val'], 'required'],
            [['vendorId'], 'integer'],
            [['date_created', 'date_updated'], 'safe'],
            [['code', 'val'], 'string', 'max' => 255],
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
            'code' => 'Code',
            'val' => 'Val',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
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
    
    public static function getVendorOverride($vendorId, $code){
        $vendorOverrideConfig = VendorAppConfigOverride::findOne(['vendorId' => $vendorId, 'code' => $code]);
        if($vendorOverrideConfig){
            return $vendorOverrideConfig->val;
        }
        //else
        $appConfig = AppConfig::findOne(['code' => $code]);
        return $appConfig->val;
    }
}
