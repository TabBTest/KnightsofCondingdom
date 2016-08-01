<?php

namespace app\models;

use Yii;
use app\helpers\UtilityHelper;
use app\helpers\TenantHelper;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property integer $role
 * @property string $name
 * @property string $imgFile
 * @property string $orderButtonImage
 * @property string $streetAddress
 * @property string $city
 * @property string $state
 * @property string $phoneNumber
 * @property string $billingName
 * @property string $billingStreetAddress
 * @property string $billingCity
 * @property string $billingState
 * @property string $billingPhoneNumber
 * @property integer $vendorId
 * @property string $date_created
 * @property string $date_updated
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const ROLE_ADMIN = 0; //superadmin
    const ROLE_VENDOR = 1;
    const ROLE_CUSTOMER= 2;

    
    const CARD_STATE_NOT_EXISTING = 1;
    const CARD_STATE_NEAR_EXPIRE = 2;
    const CARD_STATE_EXPIRED = 3;
    const CARD_STATE_VALID = 4;
    
    const MEMBERSHIP_PRICE = 34.99;
    
    const MEMBERSHIP_EXPIRED = 1;
    const MEMBERSHIP_NEAR_EXPIRE = 2;
    const MEMBERSHIP_VALID = 3;
    
    public $confirmPassword;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password', 'role'], 'required'],
            [['role', 'vendorId'], 'integer'],
            [['cimToken', 'paymentProfileId', 'date_created', 'date_updated', 'isPasswordReset', 'cardLast4', 'cardExpiry', 'isActive', 'timezone', 'isOptIn', 'isStoreOpen', 'storeCloseReason', 'timeToPickUp'], 'safe'],
            [['email', 'password','businessName', 'firstName','lastName', 'streetAddress', 'city','phoneAreaCode','phone3','phone4','phoneNumber', 'billingName', 'billingStreetAddress', 'billingCity','billingPhoneAreaCode','billingPhone3','billingPhone4', 'billingPhoneNumber', 'stripeId', 'orderButtonImage'], 'string', 'max' => 250],
            [['state', 'billingState'], 'string', 'max' => 2],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'role' => 'Role',
            'Business Name' => 'Business Name',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'imageFile' => 'Image File',
            'streetAddress' => 'Street Address',
            'city' => 'City',
            'state' => 'State',
            'phoneNumber' => 'Phone Number',
            'billingName' => 'Billing Name',
            'billingAddress' => 'Billing Street Address',
            'billingCity' => 'Billing City',
            'billingState' => 'Billing State',
            'vendorId' => 'Vendor ID',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }
    
    public function getFullName(){
        return $this->firstName.' '.$this->lastName;
    }
    public function getFullAddress(){
        return $this->billingStreetAddress.', '.$this->billingCity.', '.$this->billingState;
    }
    public function getContactNumber(){
        //return $this->billingPhoneNumber;
        return '('.$this->phoneAreaCode.') '.$this->phone3.'-'.$this->phone4;
    }
    public function getBillingContactNumber(){
        //return $this->billingPhoneNumber;
        return '('.$this->billingPhoneAreaCode.') '.$this->billingPhone3.'-'.$this->billingPhone4;
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return User::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {


        return null;
    }
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if($this->password != '' && UtilityHelper::cryptPass($this->password) == UtilityHelper::cryptPass($this->confirmPassword)){
                $this->password=UtilityHelper::cryptPass($this->password);
            }
            if($this->isNewRecord){
                $this->timezone = 'UTC';
                $this->date_created=date('Y-m-d H:i:s', strtotime('now'));
            }
            return true;
        }else{
            return false;
        }
    }
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return "";
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return true;
    }
    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === UtilityHelper::cryptPass($password);
    }

    public function upload()
    {
        if ($this->validate()) {
            $newFileName = hash('md5', $this->email);
            $this->imageFile->saveAs(Yii::getAlias('@webroot') . '/images/users/' . $newFileName . '.' . $this->imageFile->extension);
            $this->imageFile = $newFileName . '.' . $this->imageFile->extension;
            $this->save();
            return true;
        } else {
            return false;
        }
    }

    public static function getVendorDefaultMenu($user){
        $vendorMenu = VendorMenu::findOne(['vendorId' => $user->id, 'isDefault' => 1]);
        if($vendorMenu)
            return $vendorMenu;
        return false;
    }

    public static function getVendorCustomers($userId, $resultsPerPage, $page, $filters){
        $extraSQL = '';
        if(isset($filters['firstName']) && $filters['firstName'] != ''){
            $extraSQL .= " and firstName like '%".mysql_escape_string($filters['firstName'])."%'";           
        }
        if(isset($filters['lastName']) && $filters['lastName'] != ''){
            $extraSQL .= " and lastName like '%".mysql_escape_string($filters['lastName'])."%'";
        }
        
        if(isset($filters['email']) && $filters['email'] != ''){
            $extraSQL .= " and email like '%".mysql_escape_string($filters['email'])."%'";
        }
        
        if(isset($filters['isActive']) && $filters['isActive'] != ''){
            $extraSQL .= " and isActive = ".$filters['isActive'];
        }
        
        if(isset($filters['isOptIn']) && $filters['isOptIn'] != ''){
            $extraSQL .= " and isOptIn = ".$filters['isOptIn'];
        }
        
        $resp = array();
        $resp['list'] = User::find()->where('vendorId = '.$userId.' '.$extraSQL.' order by id desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = User::find()->where('vendorId = '.$userId.' '.$extraSQL)->count();
        return $resp;
    }
    
    public static function getVendors($resultsPerPage, $page, $filters){
        $extraSQL = '';
        if(isset($filters['businessName']) && $filters['businessName'] != ''){
            $extraSQL .= " and businessName like '%".mysql_escape_string($filters['businessName'])."%'";
        }
        
        if(isset($filters['firstName']) && $filters['firstName'] != ''){
            $extraSQL .= " and firstName like '%".mysql_escape_string($filters['firstName'])."%'";
        }
        if(isset($filters['lastName']) && $filters['lastName'] != ''){
            $extraSQL .= " and lastName like '%".mysql_escape_string($filters['lastName'])."%'";
        }
    
        if(isset($filters['email']) && $filters['email'] != ''){
            $extraSQL .= " and email like '%".mysql_escape_string($filters['email'])."%'";
        }
        
        if(isset($filters['isActive']) && $filters['isActive'] != ''){
            $extraSQL .= " and isActive = ".$filters['isActive'];
        }
        
        if(isset($filters['isOptIn']) && $filters['isOptIn'] != ''){
            $extraSQL .= " and isOptIn = ".$filters['isOptIn'];
        }
    
        $resp = array();
        $resp['list'] = User::find()->where('role = '.User::ROLE_VENDOR.' '.$extraSQL.' order by id desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = User::find()->where('role = '.User::ROLE_VENDOR.' '.$extraSQL)->count();
        return $resp;
    }
    
    public function storeCCInfo($params = false){
        
        if($this->cimToken == null)
            return;
        if($this->paymentProfileId == null)
            return;
        
        // Common setup for API credentials (merchant)      
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(\Yii::$app->params['authorize.net.login.id']);
        $merchantAuthentication->setTransactionKey(\Yii::$app->params['authorize.net.transaction.key']);
        
        $refId = 'ref' . time();
        
        
        
        //request requires customerProfileId and customerPaymentProfileId
        $request = new AnetAPI\GetCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId( $refId);
        $request->setCustomerProfileId($this->cimToken);
        $request->setCustomerPaymentProfileId($this->paymentProfileId);
        
        $controller = new AnetController\GetCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
        if(($response != null)){
            if ($response->getMessages()->getResultCode() == "Ok")
            {
                //echo "GetCustomerPaymentProfile SUCCESS: " . "\n";
                //echo "Customer Payment Profile Id: " . $response->getPaymentProfile()->getCustomerPaymentProfileId() . "\n";
                //echo "Customer Payment Profile Billing Address: " . $response->getPaymentProfile()->getbillTo()->getAddress(). "\n";
                //echo "Customer Payment Profile Card Last 4 " . $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber(). "\n";
        
                $last4 = $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber();
                $this->cardLast4 = str_replace('XXXX', '', $last4);
                if($params !== false){
                    $this->cardExpiry = $params['ccYear'].'-'.sprintf("%02d", $params['ccMonth']).'-01';;
                }
                $this->save();
                
                $cardHistory = new UserCardHistory();
                $cardHistory->userId = $this->id;
                $cardHistory->cardLast4 = str_replace('XXXX', '', $last4);;
                if($params !== false){
                    $cardHistory->cardExpiry = $params['ccYear'].'-'.sprintf("%02d", $params['ccMonth']).'-01';;
                }
                $cardHistory->save();
//                 var_dump($response);
//                 die;
            }
//             else
//             {
//                 echo "GetCustomerPaymentProfile ERROR :  Invalid response\n";
//                 $errorMessages = $response->getMessages()->getMessage();
//                 echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
//             }
        }
//         else{
//             echo "NULL Response Error";
//         }
        //return $response;
        /*
        \Stripe\Stripe::setApiKey(\Yii::$app->params['stripe_secret_key']);
        $customer = \Stripe\Customer::retrieve($this->stripeId);
        
        $customInfo = $customer->__toArray(true);
        if($customInfo['object'] == 'customer'){
            //we get the card info to store
            $cardInfo = $customInfo['sources']['data'][0];
            $last4 = $cardInfo['last4'];
            $expiry = $cardInfo['exp_year'].'-'.sprintf("%02d", $cardInfo['exp_month']).'-01';
            $this->cardLast4 = $last4;
            $this->cardExpiry = $expiry;
            $this->save();
        
            $cardHistory = new UserCardHistory();
            $cardHistory->userId = $this->id;
            $cardHistory->cardLast4 = $last4;
            $cardHistory->cardExpiry = $expiry;
            $cardHistory->save();
        }
         */  
    }
    public function testCardState(){
        
        $this->cardExpiry = '';
        var_dump($this->getCardState());
        
        $this->cardExpiry = '2016-06-26';
        var_dump($this->getCardState());
        
        $this->cardExpiry = '2016-06-20';
        var_dump($this->getCardState());
        
        $this->cardExpiry = '2016-07-20';
        var_dump($this->getCardState());
    }
    public function getCardState(){
        
        if($this->cardExpiry != ''){
            $cardExpiryTime = strtotime($this->cardExpiry);
            $nowTime = strtotime('now');
            
            if($nowTime > $cardExpiryTime){
                return self::CARD_STATE_EXPIRED;
            }
            
            $expireNotification = strtotime('-14 days', $cardExpiryTime);
            //var_dump(date('Y-m-d', $expireNotification));
            if($expireNotification < $nowTime){
                return self::CARD_STATE_NEAR_EXPIRE;
            }
            
            return self::CARD_STATE_VALID;            
        }
        return self::CARD_STATE_NOT_EXISTING;        
    }
    
    public function getVendorMembershipState(){
        $membership = VendorMembership::getActiveMembership($this->id);
        if($membership === false){
            return self::MEMBERSHIP_EXPIRED;            
        }
            //has memberhsip
            
            $membershipEndTime = strtotime($membership->endDate);
            $nowTime = strtotime('now');
            
            if($nowTime > $membershipEndTime){
                return self::MEMBERSHIP_EXPIRED;
            }
            
            $expireNotification = strtotime('-14 days', $membershipEndTime);
            //var_dump(date('Y-m-d', $expireNotification));
            if($expireNotification < $nowTime){
                return self::MEMBERSHIP_NEAR_EXPIRE;
            }
            return self::MEMBERSHIP_VALID;            
        
    }
    
    public function isMembershipExpired(){
        if($this->getVendorMembershipState() == self::MEMBERSHIP_EXPIRED)
            return true;
        return false;
    }
    
    public function getVendorName(){
        $vendor = User::findOne($this->vendorId);
        return $vendor->name;
    }
    public function getTimezoneOffset(){
        //UtilityHelper::getAvailableTimezones();
        if($this->timezone != ''){
            $date_time_zone = new \DateTimeZone($this->timezone );
            $date_time = new \DateTime('now', $date_time_zone);
            return $date_time_zone->getOffset($date_time);
           
        }
        return 0;
        
    }
    public function showConvertedTime($date, $format = 'm-d-Y h:i a'){
        if($date != null && $date != ''){
            $date_time_zone = new \DateTimeZone($this->timezone );
            
            
            $date = new \DateTime(date('Y-m-d H:i:s +00', strtotime($date)));
            $date->setTimezone($date_time_zone); // +04
            
            return $date->format($format); // 2012-07-15 05:00:00
        }
        return $date;
        //var_dump(date('m-d-Y H:i a', strtotime('now', strtotime())));
    }
    /*
    $start=strtotime('00:00');
    $end=strtotime('24:00');
    $timeSlot = [];
    for ($i=$start;$i<=$end;$i = $i + 15*60)
    {
    
        //write your if conditions and implement your logic here
        $timeInfo = '';
        $timeDisplay = '';
        if($i == $end){
            $timeInfo =  '24:00';
            $timeDisplay = '12:00 am';
        }else{
            $timeInfo = date('H:i',$i);
            $timeDisplay = date('h:i a',$i);
    
        }
        $timeSlot[$timeInfo] = $timeDisplay;
    
    }
    return $timeSlot;
    */
    
    public function isVendorStoreOpen(){
        
        if($this->isStoreOpen == 0){
            return false;
        }
        //get the current day
        $vendorId = $this->id;
        
        $date_time_zone = new \DateTimeZone($this->timezone );
        $date_time_current = new \DateTime('now', $date_time_zone);
        $key = $date_time_current->format('w');
        //var_dump($date_time_current->format('w'));
        //var_dump($date_time_current->getTimestamp ());
        $operatingHours = VendorOperatingHours::getVendorOperatingHours($vendorId, $key);
        //var_dump($vendorId);
        if($operatingHours){
            
            foreach($operatingHours as $operatingHour){
                $date_time = new \DateTime('now', $date_time_zone);
                $timeComponent = explode(':', $operatingHour->startTime);
                $date_time->setTime($timeComponent[0], $timeComponent[1], 0);
                
                $date_time_close = new \DateTime('now', $date_time_zone);
                $timeComponent = explode(':', $operatingHour->endTime);
                $date_time_close->setTime($timeComponent[0], $timeComponent[1], 0);
                
                //var_dump($date_time->format('m-d-Y H:i').' to '.$date_time_close->format('m-d-Y H:i'));
                
                if($date_time->getTimestamp() <= $date_time_current->getTimestamp() && $date_time_current->getTimestamp() <= $date_time_close->getTimestamp()){
                    return true;
                }
            }
            
        }
        return false;
    }
    
    public function getTimeToPickUpDisplay(){
        return TenantHelper::getTimeToPickUp()[$this->timeToPickUp];
        
    }
    
    //from date and toDate == m-d-Y format, needs to change it
    public function getTotalPayableCost($fromDate, $toDate){
        //in perspective of admin
        // == receivable in perspective of vendor
        $fromDateReal = '';
        $toDateReal = '';
        $params = [];
        $params['vendorId'] = $this->id;
        if($fromDate != null && $fromDate != ''){
            $froms = explode('-',$fromDate);           
            $fromDateReal = $froms[2].'-'.$froms[0].'-'.$froms[1];
        }
        if($toDate != null && $toDate != ''){
            $tos = explode('-',$toDate);
            $toDateReal = $tos[2].'-'.$tos[0].'-'.$tos[1];
        }
        $params['fromDate'] = $fromDateReal;
        $params['toDate'] = $toDateReal;
        $orders = Orders::getSalesOrders('ALL', 1, $params);
        $totalAmount = 0;
        foreach($orders['list'] as $order){
            $totalAmount += $order->getTotalReceivableCost();
        }
        return $totalAmount;        
    }
    public function getTotalReceivableCost($fromDate, $toDate){
        //in the perspective of admin
        $fromDateReal = '';
        $toDateReal = '';
        $params = [];
        $params['vendorId'] = $this->id;
        if($fromDate != null && $fromDate != ''){
            $froms = explode('-',$fromDate);
            $fromDateReal = $froms[2].'-'.$froms[0].'-'.$froms[1];
        }
        if($toDate != null && $toDate != ''){
            $tos = explode('-',$toDate);
            $toDateReal = $tos[2].'-'.$tos[0].'-'.$tos[1];
        }
        $params['fromDate'] = $fromDateReal;
        $params['toDate'] = $toDateReal;
        $orders = Orders::getSalesOrders('ALL', 1, $params);
        $totalAmount = 0;
        foreach($orders['list'] as $order){
            $totalAmount = $order->getTotalAdminReceivableCost();
        }
        return $totalAmount;
    }
    
    public function createNewCustomerProfile(){
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(\Yii::$app->params['authorize.net.login.id']);
        $merchantAuthentication->setTransactionKey(\Yii::$app->params['authorize.net.transaction.key']);
        
        $refId = 'ref' . time();
        
        $customerprofile = new AnetAPI\CustomerProfileType();
        $customerprofile->setDescription("Customer ID# ".$this->id);
        $customerprofile->setMerchantCustomerId($this->id);
        $customerprofile->setEmail($this->email);
        //$customerprofile->setPaymentProfiles($paymentprofiles);
        $request = new AnetAPI\CreateCustomerProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId( $refId);
        $request->setProfile($customerprofile);
        $controller = new AnetController\CreateCustomerProfileController($request);
        $response = $controller->executeWithApiResponse( UtilityHelper::getAuthorizeNetMode());
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
        {
            $this->cimToken = $response->getCustomerProfileId();
            $this->save();
            return true;
            //$paymentProfiles = $response->getCustomerPaymentProfileIdList();
            //echo "SUCCESS: PAYMENT PROFILE ID : " . $paymentProfiles[0] . "\n";
        }
        else
        {
            echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
        }
        return $response;
    }
    
    public static function testCC($user){
        
    }
    
    public function createNewPaymentProfile(){
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(\Yii::$app->params['authorize.net.login.id']);
        $merchantAuthentication->setTransactionKey(\Yii::$app->params['authorize.net.transaction.key']);
        
        $refId = 'ref' . time();
        
       
        
      
         
        // Create a Customer Profile Request
        //  1. create a Payment Profile
        //  2. create a Customer Profile
        //  3. Submit a CreateCustomerProfile Request
        //  4. Validate Profiiel ID returned
        
         
        
        $customerprofile = new AnetAPI\CustomerProfileType();
        $customerprofile->setDescription($_SERVER['HTTP_HOST'].' - '." User ID# ".$this->id);
        $customerprofile->setMerchantCustomerId($this->id);
        $customerprofile->setEmail($this->email);
        //$customerprofile->setPaymentProfiles($paymentprofiles);
        $request = new AnetAPI\CreateCustomerProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId( $refId);
        $request->setProfile($customerprofile);
        //$request->setValidationMode(UtilityHelper::getAuthorizeNetValidationMode());
        $controller = new AnetController\CreateCustomerProfileController($request);
        $response = $controller->executeWithApiResponse( UtilityHelper::getAuthorizeNetMode());
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
        {
        
            $this->cimToken = $response->getCustomerProfileId();
            return true; 
        }else{
            var_dump($response);
            die;
        }
        return false;
    }
    
    public function saveCustomerPaymentProfile($params){
        
        // Common setup for API credentials
        if($this->cimToken == null || $this->cimToken == ''){
            return;
        }
            
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(\Yii::$app->params['authorize.net.login.id']);
        $merchantAuthentication->setTransactionKey(\Yii::$app->params['authorize.net.transaction.key']);
        $refId = 'ref' . time();
        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber(  $params['cc']);
        $creditCard->setExpirationDate(  $params['ccYear'].'-'. $params['ccMonth']);
        $creditCard->setCardCode($params['cvv']);
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);
       
        // Create a Customer Profile Request
        //  1. create a Payment Profile
        //  2. create a Customer Profile
        //  3. Submit a CreateCustomerProfile Request
        //  4. Validate Profiiel ID returned
        $paymentprofile = new AnetAPI\CustomerPaymentProfileType();
        $paymentprofile->setCustomerType('individual');
        $paymentprofile->setPayment($paymentCreditCard);
        $paymentprofiles[] = $paymentprofile;
      
        //Set profile ids of profile to be updated
        $isNewProfile = true;
        
        if($this->paymentProfileId != null && $this->paymentProfileId != ''){
            //we update
            $request = new AnetAPI\UpdateCustomerPaymentProfileRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setCustomerProfileId($this->cimToken);
            
            $paymentprofile = new AnetAPI\CustomerPaymentProfileExType();
            $paymentprofile->setCustomerPaymentProfileId($this->paymentProfileId);
            $paymentprofile->setPayment($paymentCreditCard);
            
            // Submit a UpdatePaymentProfileRequest
            $request->setPaymentProfile( $paymentprofile );
            $request->setValidationMode(UtilityHelper::getAuthorizeNetValidationMode());
            $controller = new AnetController\UpdateCustomerPaymentProfileController($request);
            $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
             
            $isNewProfile = false;
        }else{
            //we create a new profile
            // Submit a CreateCustomerPaymentProfileRequest to create a new Customer Payment Profile
            $paymentprofilerequest = new AnetAPI\CreateCustomerPaymentProfileRequest();
            $paymentprofilerequest->setMerchantAuthentication($merchantAuthentication);
            //Use an existing profile id
            $paymentprofilerequest->setCustomerProfileId( $this->cimToken );
            $paymentprofilerequest->setPaymentProfile( $paymentprofile );
            $paymentprofilerequest->setValidationMode(UtilityHelper::getAuthorizeNetValidationMode());
            $controller = new AnetController\CreateCustomerPaymentProfileController($paymentprofilerequest);
            $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
             
            
        }
        
        

	  // Create the Customer Payment Profile object
	  if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
	  {
	      if($isNewProfile){
    	      $this->paymentProfileId = $response->getCustomerPaymentProfileId();
    		  $this->save();
	      }
	   }
	  else
	  {
		  echo "Update Customer Payment Profile: ERROR Invalid response\n";
		  $errorMessages = $response->getMessages()->getMessage();
		  echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
	  }
    }
}
