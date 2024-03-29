<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use app\helpers\TenantHelper;
use app\models\User;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            //$this->setState('role', $user->role);
            Yii::$app->session->set('role',$user->role);
            Yii::$app->session->set('name',$user->getFullName());            
            
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        }
//         var_dump($this->_user);
//         var_dump($this->errors);
//         die;
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        
        //$isDefaultTenant = TenantHelper::isDefaultTenant();
        
        
        if ($this->_user === false) {
            //if($isDefaultTenant){
                $this->_user = User::findOne(['email' => $this->email, 'role' => User::ROLE_ADMIN]);
        
        }

        return $this->_user;
    }
}
