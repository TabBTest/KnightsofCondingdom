<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vendor_menu_item".
 *
 * @property integer $id
 * @property integer $vendorMenuId
 * @property string $name
 * @property string $description
 * @property string $photo
 * @property double $amount
 * @property string $date_created
 * @property string $date_updated
 */
class VendorMenuItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vendor_menu_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendorMenuId', 'name', 'amount'], 'required'],
            [['vendorMenuId'], 'integer'],
            [['description', 'photo'], 'string'],
            [['amount'], 'number'],
            [['date_created', 'date_updated','isArchived', 'menuCategoryId', 'sorting'], 'safe'],
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
            'vendorMenuId' => 'Vendor Menu ID',
            'name' => 'Name',
            'description' => 'Description',
            'photo' => 'Photo',
            'amount' => 'Amount',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }
    
    public function getPhotoPath(){
        return md5($this->vendorMenuId).'/'.md5($this->id);
    }
    public function hasPhoto(){
        $imagePath = '/menu-images/'.$this->getPhotoPath();
        $targetPath = realpath(Yii::$app->basePath).'/web' . $imagePath;
        if(is_file($targetPath)){
          return true;
        }
        return false;
    }
    
    public function getAddOns(){
        return VendorMenuItemAddOns::find()->where('vendorMenuItemId = ' . $this->id . ' and isArchived = 0 order by sorting asc')->all();
    }
}
