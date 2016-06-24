<?php

namespace app\models;

use Yii;
use app\helpers\UtilityHelper;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property integer $role
 * @property string $name
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
            [['date_created', 'date_updated', 'isPasswordReset', 'cardLast4', 'cardExpiry'], 'safe'],
            [['email', 'password', 'name', 'streetAddress', 'city', 'phoneNumber', 'billingName', 'billingStreetAddress', 'billingCity', 'billingPhoneNumber', 'stripeId'], 'string', 'max' => 250],
            [['state', 'billingState'], 'string', 'max' => 2]
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
            'name' => 'Name',
            'streetAddress' => 'Street Address',
            'city' => 'City',
            'state' => 'State',
            'phoneNumber' => 'Phone Number',
            'billingName' => 'Billing Name',
            'billingAddress' => 'Billing Street Address',
            'billingAddress' => 'Billing City',
            'billingAddress' => 'Billing State',
            'vendorId' => 'Vendor ID',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
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
            if($this->isNewRecord)
                $this->date_created=date('Y-m-d H:i:s', strtotime('now'));
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

    public static function getVendorDefaultMenu($user){
        $vendorMenu = VendorMenu::findOne(['vendorId' => $user->id, 'isDefault' => 1]);
        if($vendorMenu)
            return $vendorMenu;
        return false;
    }

    public static function getVendorCustomers($userId, $resultsPerPage, $page){
        $resp = array();
        $resp['list'] = User::find()->where('vendorId = '.$userId.' order by id desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = User::find()->where('vendorId = '.$userId)->count();
        return $resp;
    }
    
    public function storeCCInfo(){
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
           
    }
}
