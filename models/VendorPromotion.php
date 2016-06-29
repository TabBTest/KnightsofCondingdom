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
            [['date_created'], 'safe'],
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
}
