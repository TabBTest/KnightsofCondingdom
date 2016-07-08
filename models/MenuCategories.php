<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu_categories".
 *
 * @property integer $id
 * @property integer $vendorId
 * @property string $name
 * @property string $description
 * @property integer $sorting
 * @property string $date_created
 */
class MenuCategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendorId', 'name'], 'required'],
            [['vendorId', 'sorting'], 'integer'],
            [['description'], 'string'],
            [['date_created', 'isArchived'], 'safe'],
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
            'vendorId' => 'Vendor ID',
            'name' => 'Name',
            'description' => 'Description',
            'sorting' => 'Sorting',
            'date_created' => 'Date Created',
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
}
