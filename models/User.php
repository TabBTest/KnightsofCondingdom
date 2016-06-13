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
 * @property string $address
 * @property string $phoneNumber
 * @property string $billingName
 * @property string $billingAddress
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
            [['date_created', 'date_updated', 'isPasswordReset'], 'safe'],
            [['email', 'password', 'name', 'address', 'phoneNumber', 'billingName', 'billingAddress', 'stripeId'], 'string', 'max' => 250],
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
            'address' => 'Address',
            'phoneNumber' => 'Phone Number',
            'billingName' => 'Billing Name',
            'billingAddress' => 'Billing Address',
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
}
