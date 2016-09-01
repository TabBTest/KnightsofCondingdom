<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vendor_menu_item_add_ons".
 *
 * @property integer $id
 * @property integer $vendorMenuItemId
 * @property string $name
 * @property string $description
 * @property double $amount
 * @property string $date_created
 * @property string $date_updated
 * @property integer $isArchived
 */
class VendorMenuItemAddOns extends \yii\db\ActiveRecord
{
    const SPECIAL_TYPE_FULL = '1';
    const SPECIAL_TYPE_LEFT_HALF = '2';
    const SPECIAL_TYPE_RIGHT_HALF = '3';
    const SPECIAL_TYPE_ON_THE_SIDE = '4';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vendor_menu_item_add_ons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendorMenuItemId', 'name', 'amount'], 'required'],
            [['vendorMenuItemId', 'isArchived'], 'integer'],
            [['description'], 'string'],
            [['amount'], 'number'],
            [['isSpecialFull','isSpecialHalf','isSpecialSide', 'isSpecialOrder','amountFull','amountHalf','amountSide', 'date_created', 'date_updated', 'sorting', 'isExclusive', 'menuCategoryId'], 'safe'],
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
            'vendorMenuItemId' => 'Vendor Menu Item ID',
            'name' => 'Name',
            'description' => 'Description',
            'amount' => 'Amount',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'isArchived' => 'Is Archived',
        ];
    }
}
