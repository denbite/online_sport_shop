<?php

namespace app\modules\user\models\forms;

use app\modules\user\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;

/**
 * Password reset form
 */
class PasswordResetForm extends Model
{
    
    public $password;
    
    public $password_repeat;
    
    /**
     * @var \common\models\User
     */
    private $_user;
    
    
    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     *
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $email, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Пустой токен.');
        }
    
        $this->_user = User::findByPasswordResetTokenAndEmail($token, $email);
        
        if (!$this->_user) {
            throw new InvalidParamException('Не удалось найти такие данные.');
        }
    
        parent::__construct($config);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'password', 'password_repeat' ], 'required', 'message' => 'Заполните поле' ],
    
            [ [ 'password', 'password_repeat' ], 'string', 'min' => 6, 'tooShort' => 'Пароль должен быть длиной не меньше 6 символов' ],
    
            [ 'password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли должны совпадать' ],
        ];
    }
    
    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        
        return $user->save(false);
    }
    
    public function attributeLabels()
    {
        return [
            'password' => 'Новый пароль',
            'password_repeat' => 'Повторите пароль',
        ];
    }
}
