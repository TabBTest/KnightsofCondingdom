<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vendor_operating_hours".
 *
 * @property integer $id
 * @property integer $vendorId
 * @property integer $day
 * @property string $startTime
 * @property string $endTime
 * @property string $date_created
 */
class VendorOperatingHours extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vendor_operating_hours';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendorId', 'day', 'startTime', 'endTime'], 'required'],
            [['vendorId', 'day'], 'integer'],
            [['date_created'], 'safe'],
            [['startTime', 'endTime'], 'string', 'max' => 10],
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
            'day' => 'Day',
            'startTime' => 'Start Time',
            'endTime' => 'End Time',
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
    public static function getVendorOperatingHours($vendorId, $day){
        return VendorOperatingHours::findAll(['vendorId' => $vendorId, 'day' => $day]);
    }
}
