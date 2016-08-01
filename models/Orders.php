<?php

namespace app\models;

use Yii;

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property integer $customerId
 * @property integer $vendorId
 * @property integer $status
 * @property string $confirmedDateTime
 * @property string $startDateTime
 * @property string $pickedUpDateTime
 * @property string $date_created
 */
class Orders extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 1;
    const STATUS_PENDING = 2;
    const STATUS_PROCESSED = 3;
    
    const PAYMENT_TYPE_CARD = 1;
    const PAYMENT_TYPE_CASH = 2;
    
    public $orderId = '';
    const MAGIC_NUMBER = 10000;
    
    private $_detailList = false;
    
    public function getOrderId(){
        $orderId = self::MAGIC_NUMBER + $this->id;
        return $orderId;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customerId', 'vendorId', 'status'], 'required'],
            [['customerId', 'vendorId', 'status'], 'integer'],
            [['isAdvanceOrder', 'advancePickupDeliveryTime', 'deliveryAddress','deliveryCity','deliveryState','customBillingName','customBillingAddress','customBillingState', 'customBillingCity','customBillingCardLast4', 'isDelivery','cancelReason','refundTransactionId', 'refundReason','cancelledByUserId','refundedByUserId', 'isCancelled','isRefunded','cancellation_date','refund_date', 'confirmedDateTime', 'startDateTime', 'pickedUpDateTime', 'date_created', 'transactionId', 'cardLast4', 'notes', 'paymentType', 'isPaid', 'isArchived', 'paymentGatewayFee'], 'safe'],
        ];
    }
        
    public function getDeliveryAddress(){
        if($this->deliveryAddress == null){
            $customerInfo = User::findOne($this->customerId);
            return  $customerInfo->getFullAddress();
        }
        return $this->deliveryAddress.', '.$this->deliveryCity.', '.$this->deliveryState;
    }
    public function getCancelledBy(){
        return User::findOne($this->cancelledByUserId);
    }
    public function getRefundedBy(){
        return User::findOne($this->refundedByUserId);
    }
    
    public function cancelOrder($reason, $userId){
        $this->cancelReason = $reason;
        $this->cancelledByUserId = $userId;
        $this->isCancelled = 1;
        $this->cancellation_date = date('Y-m-d H:i:s', strtotime('now'));
        $this->save();
    }
    public function refundOrder($reason, $userId){
        $doRefund = false;
        if($this->paymentType == self::PAYMENT_TYPE_CARD){
            //do stripe refund
            
            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
            $merchantAuthentication->setName(\Yii::$app->params['authorize.net.login.id']);
            $merchantAuthentication->setTransactionKey(\Yii::$app->params['authorize.net.transaction.key']);
            
            $refId = 'ref' . time();
            
            
            //create a transaction
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType( "voidTransaction");
            $transactionRequestType->setRefTransId($this->transactionId);
            
            $request = new AnetAPI\CreateTransactionRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId($refId);
            $request->setTransactionRequest( $transactionRequestType);
            $controller = new AnetController\CreateTransactionController($request);
            $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
            if ($response != null)
            {
                $tresponse = $response->getTransactionResponse();
                if (($tresponse != null) && ($tresponse->getResponseCode()== '1') )
                {
                    //echo "Void transaction SUCCESS AUTH CODE: " . $tresponse->getAuthCode() . "\n";
                    //echo "Void transaction SUCCESS TRANS ID  : " . $tresponse->getTransId() . "\n";
                    
                    if($this->refundTransactionId == null){
                        $this->refundTransactionId = $tresponse->getTransId();
                        $this->save();
                        $doRefund = true;
                    }
                    
                }
                else
                {
                    //echo  "void transaction ERROR : " . $tresponse->getResponseCode() . "\n";
                    //$errorMessages = $response->getMessages()->getMessage();
                    //echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
                    //use print_r to see whole $response which will have the specific error messages
                }
            }
            else
            {
                //echo  "Void transaction Null esponse returned";
            }
            //return $response;
            
            /*
            \Stripe\Stripe::setApiKey(\Yii::$app->params['stripe_secret_key']);
           
            $ch = \Stripe\Charge::retrieve($this->transactionId);
            $chargeObject = $ch->__toArray(true);
            $bookingAmount = floatval($chargeObject['amount'] / 100) - floatval($chargeObject['amount_refunded'] / 100);
            //$refundAmount = floatval($userBooking->amount) - $totalCancellationCost;
            $refundAmount = $bookingAmount;
            $refundAmount = $refundAmount * 100;
           
            $re = $ch->refunds->create(['amount'=>$refundAmount]);
            $chargeArray = $re->__toArray(true);
            
            
            if($this->refundTransactionId == null && $chargeArray['status'] == 'succeeded'){
                $this->refundTransactionId = $chargeArray['id'];
                $this->save();
                $doRefund = true;
            }
            */
        }else if($this->paymentType == self::PAYMENT_TYPE_CASH){
            $doRefund = true;
        }
        if($doRefund){
            $this->refundReason = $reason;
            $this->refundedByUserId = $userId;
            $this->isRefunded = 1;
            $this->refund_date = date('Y-m-d H:i:s', strtotime('now'));
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customerId' => 'Customer ID',
            'vendorId' => 'Vendor ID',
            'status' => 'Status',
            'confirmedDateTime' => 'Confirmed Date Time',
            'startDateTime' => 'Start Date Time',
            'pickedUpDateTime' => 'Picked Up Date Time',
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
        
    
    public static function getCustomerOrders($userId, $resultsPerPage, $page){
        $resp = array(); 
        $resp['list'] = Orders::find()->where('customerId = '.$userId.' order by id desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = Orders::find()->where('customerId = '.$userId)->count();
       
        return $resp;             
    }
    /*
    public function afterFind()
    {
    
        // I don't know code to change time zone, so I assume there is a function named "functionToChangeTimeZone"
        $this->date_created = \Yii::$app->user->identity->showConvertedTime($this->date_created );
        $this->confirmedDateTime = \Yii::$app->user->identity->showConvertedTime($this->confirmedDateTime);
        $this->startDateTime = \Yii::$app->user->identity->showConvertedTime($this->startDateTime );
        $this->pickedUpDateTime = \Yii::$app->user->identity->showConvertedTime($this->pickedUpDateTime );
        
        return parent::afterFind();
    }
    */
    public static function getSalesOrders($resultsPerPage, $page, $filters){
    
        $extraSQL = '';
//         if(isset($filters['showCompleted'])){
//             if( $filters['showCompleted'] == 0){
//                 $extraSQL .= ' and status != '.self::STATUS_PROCESSED;
//             }
//         }else{
//             $extraSQL .= ' and status != '.self::STATUS_PROCESSED;
//         }
    
//         if(isset($filters['firstName']) && $filters['firstName'] != ''){
//             $extraSQL .= " and customerId in (select id from user where firstName like '%".mysql_escape_string($filters['firstName'])."%')";
//         }
//         if(isset($filters['lastName']) && $filters['lastName'] != ''){
//             $extraSQL .= " and customerId in (select id from user where lastName like '%".mysql_escape_string($filters['lastName'])."%')";
//         }
    
//         if(isset($filters['orderId']) && $filters['orderId'] != ''){
//             $extraSQL .= " and (id + ".self::MAGIC_NUMBER.") =  '".$filters['orderId']."'";
//         }
//         if(isset($filters['isDelivery']) && $filters['isDelivery'] != ''){
//             $extraSQL .= " and isDelivery = ".$filters['isDelivery'];
//         }
    
        if(isset($filters['vendorId']) && $filters['vendorId'] != ''){
            $extraSQL .= " and vendorId = ".$filters['vendorId'];
        }
        if(isset($filters['fromDate']) && $filters['fromDate'] != ''){
            $extraSQL .= " and date(confirmedDateTime) >= '".$filters['fromDate']."'";
        }
        if(isset($filters['toDate']) && $filters['toDate'] != ''){
            $extraSQL .= " and date(confirmedDateTime) <= '".$filters['toDate']."'";
        }
    
        $resp = array();
        $limitSql = '';
        if($resultsPerPage != 'ALL' ){
            $limitSql = ' limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage);
        }
        
        $resp['list'] = Orders::find()->where('isRefunded = 0 and isPaid = 1 '.$extraSQL.' order by id desc '.$limitSql)->all();
        $resp['count'] = Orders::find()->where('isRefunded = 0 and isPaid = 1 '.$extraSQL)->count();
        return $resp;
    }
    public static function getVendorOrders($userId, $resultsPerPage, $page, $filters){
        
        $extraSQL = '';
        if(isset($filters['showCompleted'])){
            if( $filters['showCompleted'] == 0){
                $extraSQL .= ' and status != '.self::STATUS_PROCESSED;
            }            
        }else{
            $extraSQL .= ' and status != '.self::STATUS_PROCESSED;
        }
        
        if(isset($filters['firstName']) && $filters['firstName'] != ''){
            $extraSQL .= " and customerId in (select id from user where firstName like '%".mysql_escape_string($filters['firstName'])."%')";
        }
        if(isset($filters['lastName']) && $filters['lastName'] != ''){
            $extraSQL .= " and customerId in (select id from user where lastName like '%".mysql_escape_string($filters['lastName'])."%')";
        }
        
        if(isset($filters['orderId']) && $filters['orderId'] != ''){
            $extraSQL .= " and (id + ".self::MAGIC_NUMBER.") =  '".$filters['orderId']."'";
        }
        if(isset($filters['isDelivery']) && $filters['isDelivery'] != ''){
            $extraSQL .= " and isDelivery = ".$filters['isDelivery'];
        }
        if(isset($filters['phone']) && $filters['phone'] != ''){
            $extraSQL .= " and customerId in (select id from user where concat(phoneAreaCode, phone3, phone4) like  '". preg_replace('/\D/', '', $filters['phone'])."%')";
        }
        
        $resp = array();
        $resp['list'] = Orders::find()->where('isArchived = 0 and vendorId = '.$userId.' and TIMESTAMPDIFF(HOUR, date_created, now()) < 24 '.$extraSQL.' order by id desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = Orders::find()->where('isArchived = 0 and vendorId = '.$userId.' and TIMESTAMPDIFF(HOUR, date_created, now()) < 24 '.$extraSQL)->count();
        return $resp;
    }
    public static function getVendorArchivedOrders($userId, $resultsPerPage, $page, $filters){
        $extraSQL = '';
//         if(isset($filters['showCompleted'])){
//             if( $filters['showCompleted'] == 0){
//                 $extraSQL .= ' and status != '.self::STATUS_PROCESSED;
//             }
//         }else{
//             $extraSQL .= ' and status != '.self::STATUS_PROCESSED;
//         }
        
        if(isset($filters['firstName']) && $filters['firstName'] != ''){
            $extraSQL .= " and customerId in (select id from user where firstName like '%".mysql_escape_string($filters['firstName'])."%')";
        }
        if(isset($filters['lastName']) && $filters['lastName'] != ''){
            $extraSQL .= " and customerId in (select id from user where lastName like '%".mysql_escape_string($filters['lastName'])."%')";
        }
        
        if(isset($filters['orderId']) && $filters['orderId'] != ''){
            $extraSQL .= " and (id + ".self::MAGIC_NUMBER.") =  '".$filters['orderId']."'";
        }
        
        if(isset($filters['isDelivery']) && $filters['isDelivery'] != ''){
            $extraSQL .= " and isDelivery = ".$filters['isDelivery'];
        }
        if(isset($filters['phone']) && $filters['phone'] != ''){
            $extraSQL .= " and customerId in (select id from user where concat(phoneAreaCode, phone3, phone4) like  '". preg_replace('/\D/', '', $filters['phone'])."%')";
        }
        $resp = array();
        $resp['list'] = Orders::find()->where('vendorId = '.$userId.' and (isArchived = 1 or TIMESTAMPDIFF(HOUR, date_created, now()) >= 24) '.$extraSQL.' order by id desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = Orders::find()->where('vendorId = '.$userId.' and (isArchived = 1 or TIMESTAMPDIFF(HOUR, date_created, now()) >= 24) '.$extraSQL)->count();
        return $resp;
    }
    public function getTotalAmount(){
        $orderDetails = OrderDetails::findAll(['orderId' => $this->id]);
        $finalAmount = 0;
        foreach($orderDetails as $detail){
            if($detail->type == OrderDetails::TYPE_COUPON)
                $finalAmount -= $detail->totalAmount;
            else
                $finalAmount += $detail->totalAmount;
        }
        return $finalAmount;
    }
    public function getCustomerName(){
        $user = User::findOne($this->customerId);
        return $user->getFullName();   
    }
    public function getCustomer(){
        $user = User::findOne($this->customerId);
        return $user;
    }
    public function getPaymentType(){
        if($this->paymentType == self::PAYMENT_TYPE_CARD)
            return 'Credit Card';
        else if($this->paymentType == self::PAYMENT_TYPE_CASH)
            return 'Cash';
        return 'N/A';
    }
    public function getDetailList(){
        if($this->_detailList === false)
            $this->_detailList = OrderDetails::findAll(['orderId' => $this->id]);
        return $this->_detailList;
    }
    
    public function getFoodCost(){
        
        $list = $this->getDetailList();
        $amount = 0;
        foreach($list as $item){
            if($item->type == OrderDetails::TYPE_MENU_ITEM || 
                $item->type == OrderDetails::TYPE_MENU_ITEM_ADD_ON){
                $amount += $item->totalAmount;
            }
        }
        return $amount;
    }
    public function getSalesTax(){
        
        $list = $this->getDetailList();
        $amount = 0;
        foreach($list as $item){
            if($item->type == OrderDetails::TYPE_SALES_TAX ){
                $amount += $item->totalAmount;
            }
        }
        return $amount;
    }
    public function getDeliveryCharge(){
        
        $list = $this->getDetailList();
        $amount = 0;
        foreach($list as $item){
            if($item->type == OrderDetails::TYPE_DELIVERY_CHARGE ){
                $amount += $item->totalAmount;
            }
        }
        return $amount;
    }
    public function getWebFee(){        
        $list = $this->getDetailList();
        $amount = 0;
        foreach($list as $item){
            if($item->type == OrderDetails::TYPE_ADMIN_FEE ){
                $amount += $item->totalAmount;
            }
        }
        return $amount;
    }
    public function getCCFee(){
        return $this->paymentGatewayFee != null && $this->paymentGatewayFee > 0 ? $this->paymentGatewayFee : 0;
    }
    public function getDiscount(){       
        $list = $this->getDetailList();
        $amount = 0;
        foreach($list as $item){
            if($item->type == OrderDetails::TYPE_COUPON ){
                $amount += $item->totalAmount;
            }
        }
        return $amount;
        
    }
    public function getTotalReceivableCost(){
        //do we include the cc charge here
        //return $this->getFoodCost() + $this->getWebFee() + $this->getSalesTax() + $this->getDeliveryCharge() - $this->getDiscount();
        return $this->getFoodCost() +  $this->getSalesTax() + $this->getDeliveryCharge() - ($this->getDiscount() + $this->getCCFee() + $this->getWebFee());
    }
    
    public function getTotalAdminReceivableCost(){
        //do we include the cc charge here
        //return $this->getFoodCost() + $this->getWebFee() + $this->getSalesTax() + $this->getDeliveryCharge() - $this->getDiscount();
        return $this->getWebFee();
    }
}
