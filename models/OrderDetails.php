<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_details".
 *
 * @property integer $id
 * @property integer $orderId
 * @property integer $vendorMenuItemId
 * @property string $name
 * @property double $amount
 * @property integer $quantity
 * @property double $totalAmount
 * @property string $date_created
 */
class OrderDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderId', 'vendorMenuItemId', 'name', 'amount', 'quantity', 'totalAmount'], 'required'],
            [['orderId', 'vendorMenuItemId', 'quantity'], 'integer'],
            [['amount', 'totalAmount'], 'number'],
            [['date_created'], 'safe'],
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
            'orderId' => 'Order ID',
            'vendorMenuItemId' => 'Vendor Menu Item ID',
            'name' => 'Name',
            'amount' => 'Amount',
            'quantity' => 'Quantity',
            'totalAmount' => 'Total Amount',
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
