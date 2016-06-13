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
            [['date_created', 'date_updated'], 'safe'],
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
}
