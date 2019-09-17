<?php

namespace app\modules\user\models\forms;

use app\components\helpers\Permission;
use app\modules\user\models\User;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm
    extends Model
{
    
    public $name;
    
    public $surname;
    
    public $email;
    
    public $password;
    
    public $verifyCode;
    
    public $phone;
    
    private $_extra;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            [ [ 'email', 'phone', 'name' ], 'required', 'message' => 'Поле не может быть пустым' ],
            
            [ 'email', 'filter', 'filter' => 'trim' ],
            [ 'email', 'email', 'message' => 'Введите корректный почтовый адрес' ],
            [ 'email', 'unique', 'targetClass' => User::className(), 'message' => 'Пользователь с таким почтовым ящиком уже зарегистрирован' ],
            
            [ [ 'name', 'surname' ], 'string', 'max' => 64, 'tooLong' => 'Данное поле должно быть длиной меньше 64 символов' ],
            
            [ 'phone', 'unique', 'targetClass' => User::className(), 'message' => 'Пользователь с таким номером телефона уже зарегистрирован' ],
            
            [ 'password', 'required', 'when' => function ($model)
            {
                return $model->getBooleanSignup();
            }, 'whenClient' => 'function (attribute, value) {
    return $("input#checkoutform-booleansignup[type=\'checkbox\']").is(":checked") === true;
}', 'message' => 'Поле не может быть пустым' ],
            
            [ 'password', 'string', 'skipOnEmpty' => true, 'min' => 6, 'tooShort' => 'Пароль должен быть длиной не меньше 6 символов' ],
            
            //            [ 'verifyCode', 'captcha', 'captchaAction' => '/user/default/captcha' ],
        ];
    }
    
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate() and !empty($this->password)) {
            $user = new User();
            $user->name = $this->name . ' ' . $this->surname;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->setPassword($this->password);
            // todo: change status to WAIT after add mailer
            $user->status = User::STATUS_ACTIVE;
            $user->generateAuthKey();
            $user->generateEmailConfirmToken();
            
            if ($user->save()) {
                // todo: add mailer
                //                Yii::$app->mailer->compose('@app/modules/user/mails/emailConfirm', [ 'user' => $user ])
                //                                 ->setFrom([ Yii::$app->params['supportEmail'] => Yii::$app->name ])
                //                                 ->setTo($this->email)
                //                                 ->setSubject('Email confirmation for ' . Yii::$app->name)
                //                                 ->send();
                
                Yii::$app->authManager->assign(Yii::$app->authManager->getRole('user'), $user->id);
                
                // doesn't work
                //                Yii::$app->user->login($user, 3600 * 24);
                return !Permission::can('admin_user_create') ? Yii::$app->user->login($user, 3600 * 24 * 7) : true;
            }
            
        }
        
        return null;
    }
    
    
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'email' => 'Почтовый ящик',
            'phone' => 'Номер телефона',
            'password' => 'Пароль',
            'verifyCode' => 'Капча',
        ];
    }
    
    public function setBooleanSignup($value = false)
    {
        $this->_extra['booleanSignup'] = $value;
    }
    
    public function getBooleanSignup()
    {
        if (is_array($this->_extra) and array_key_exists('booleanSignup',
                                                         $this->_extra) and $this->_extra['booleanSignup']) {
            return true;
        }
        
        return false;
    }
}
