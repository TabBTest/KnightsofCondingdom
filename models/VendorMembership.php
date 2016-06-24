<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vendor_membership".
 *
 * @property integer $id
 * @property integer $vendorId
 * @property string $startDate
 * @property string $endDate
 * @property string $transactionId
 * @property double $amount
 * @property string $cardLast4
 */
class VendorMembership extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vendor_membership';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendorId', 'startDate', 'endDate', 'transactionId', 'amount'], 'required'],
            [['vendorId'], 'integer'],
            [['startDate', 'endDate'], 'safe'],
            [['amount'], 'number'],
            [['transactionId'], 'string', 'max' => 500],
            [['cardLast4'], 'string', 'max' => 25],
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
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
            'transactionId' => 'Transaction ID',
            'amount' => 'Amount',
            'cardLast4' => 'Card Last4',
        ];
    }
}
