<?php

namespace app\modules\user\models\forms;

use app\modules\user\models\User;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    
    public $name;
    
    public $email;
    
    public $password;
    
    public $verifyCode;
    
    public $phone;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ 'name', 'string', 'min' => 2, 'max' => 16, 'tooShort' => 'Имя должно быть длиной не меньше 2 символов', 'tooLong' => 'Имя должно быть длиной меньше 16 символов' ],
    
            //            [ 'email', 'filter', 'filter' => 'trim' ],
            [ 'email', 'required', 'message' => 'Поле не может быть пустым' ],
            [ 'email', 'email', 'message' => 'Введите корректный почтовый адрес' ],
            [ 'email', 'unique', 'targetClass' => User::className(), 'message' => 'Пользователь с таким почтовым ящиком уже зарегистрирован' ],
    
            [ 'phone', 'required', 'message' => 'Поле не может быть пустым' ],
    
            [ 'password', 'required', 'message' => 'Поле не может быть пустым' ],
            [ 'password', 'string', 'min' => 6, 'tooShort' => 'Пароль должен быть длиной не меньше 6 символов' ],
    
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
        if ($this->validate()) {
            $user = new User();
            $user->name = $this->name;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->setPassword($this->password);
            // todo: change to WAIT after add mailer
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
                return Yii::$app->user->login($user, 3600 * 24 * 7);
            }
    
        }
        
        return null;
    }
    
    
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'email' => 'Почтовый ящик',
            'phone' => 'Номер телефона',
            'password' => 'Пароль',
            'verifyCode' => 'Капча',
        ];
    }
}
