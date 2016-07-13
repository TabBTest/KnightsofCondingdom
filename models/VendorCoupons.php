<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vendor_coupons".
 *
 * @property integer $id
 * @property string $code
 * @property string $description
 * @property integer $vendorId
 * @property integer $isArchived
 * @property integer $discountType
 * @property double $discount
 * @property string $date_created
 */
class VendorCoupons extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vendor_coupons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'vendorId', 'discountType', 'discount'], 'required'],
            [['vendorId', 'isArchived', 'discountType'], 'integer'],
            [['discount'], 'number'],
            [['date_created'], 'safe'],
            [['code'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 2500],
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
            'description' => 'Description',
            'vendorId' => 'Vendor ID',
            'isArchived' => 'Is Archived',
            'discountType' => 'Discount Type',
            'discount' => 'Discount',
            'date_created' => 'Date Created',
        ];
    }
}
