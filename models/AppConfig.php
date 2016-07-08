<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "app_config".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $val
 * @property string $date_created
 * @property string $date_updated
 */
class AppConfig extends \yii\db\ActiveRecord
{
    const ADMIN_FEE = 'ADMIN_FEE';
    const MONTHLY_MEMBERSHIP_FEE = 'MONTHLY_MEMBERSHIP_FEE';
   
    
    
    public function getInputOptions(){
        /*
        if($this->code == self::UNSIGNED_EMAIL_RECIPIENT || $this->code == self::ADMIN_NEW_CANDIDATES_EMAIL_RECIPIENT){
            return ['type'=>'text', 'width'=>''];
        }
        */
        return ['type'=>'number', 'width'=>'width: 85px;'];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'val'], 'required'],
            [['date_created', 'date_updated'], 'safe'],
            [['code', 'name', 'val'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'val' => 'Val',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }
    
    
}
