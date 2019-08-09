<?php

namespace app\modules\user\models\forms;

use app\modules\user\models\User;
use Yii;
use yii\base\Model;

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
            [ [ 'email', 'password' ], 'required', 'message' => 'Поле не может быть пустым' ],
    
            // email verify
            [ 'email', 'email', 'message' => 'Введите корректный почтовый адрес' ],
            
            // rememberMe must be a boolean value
            [ 'rememberMe', 'boolean' ],
    
            // password length
            [ [ 'password' ], 'string', 'min' => 6, 'tooShort' => 'Пароль должен быть длиной не меньше 6 символов' ],
            
            // password is validated by validatePassword()
            [ 'password', 'validatePassword' ],
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
    
            if (!$user) {
                $this->addError('email', 'Пользователя с таким почтовым ящиком не существует');
            } elseif (!$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Пароль введен неправильно');
            }
        }
    }
    
    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }
    
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }
        
        return $this->_user;
    }
    
    public function attributeLabels()
    {
        return [
            'email' => 'Почта',
            'password' => 'Пароль',
        ];
    }
}
