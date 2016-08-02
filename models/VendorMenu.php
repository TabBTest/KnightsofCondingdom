<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vendor_menu".
 *
 * @property integer $id
 * @property string $name
 * @property integer $isDefault
 * @property integer $vendorId
 * @property string $date_created
 * @property string $date_updated
 */
class VendorMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vendor_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'isDefault', 'vendorId'], 'required'],
            [['isDefault', 'vendorId'], 'integer'],
            [['date_created', 'date_updated', 'startTime', 'endTime'], 'safe'],
            [['name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'isDefault' => 'Is Default',
            'vendorId' => 'Vendor ID',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }
    public function displayMenuAvailabilityInCustomerTimezone(){
        //get the current day
        $user = User::findOne($this->vendorId);
        
        $date_time_zone = new \DateTimeZone($user->timezone );
        $date_time_current = new \DateTime('now', $date_time_zone);
        
        if($this->startTime == null || $this->startTime == ''){
            return false;
        }
        $date_time = new \DateTime('now', $date_time_zone);
        $timeComponent = explode(':', $this->startTime);
        $date_time->setTime($timeComponent[0], $timeComponent[1], 0);
        
        
        $date_time_close = new \DateTime('now', $date_time_zone);
        $timeComponent = explode(':', $this->endTime);
        $date_time_close->setTime($timeComponent[0], $timeComponent[1], 0);
        
        if($date_time->getTimestamp() <= $date_time_current->getTimestamp() && $date_time_current->getTimestamp() <= $date_time_close->getTimestamp()){
            return true;
        }
        
        
        return $date_time->format('g:i A').' to '.$date_time_close->format('g:i A');
    }
    public function isMenuOpenForOrder(){
    
        //get the current day
        $user = User::findOne($this->vendorId);
    
        $date_time_zone = new \DateTimeZone($user->timezone );
        $date_time_current = new \DateTime('now', $date_time_zone);
        
        if($this->startTime == null || $this->startTime == ''){
            return false;
        }
        $date_time = new \DateTime('now', $date_time_zone);
        $timeComponent = explode(':', $this->startTime);
        $date_time->setTime($timeComponent[0], $timeComponent[1], 0);

        $date_time_close = new \DateTime('now', $date_time_zone);
        $timeComponent = explode(':', $this->endTime);
        $date_time_close->setTime($timeComponent[0], $timeComponent[1], 0);

//         var_dump(date('H:i', $date_time->getTimestamp()) );

//         var_dump(date('H:i', $date_time_close->getTimestamp()) );

//         var_dump(date('H:i', $date_time_current->getTimestamp()) );
        
        if($date_time->getTimestamp() <= $date_time_current->getTimestamp() && $date_time_current->getTimestamp() <= $date_time_close->getTimestamp()){
            return true;
        }
            
    
        return false;
    }
}
