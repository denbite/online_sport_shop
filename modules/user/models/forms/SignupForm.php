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
    
    public $username;
    
    public $email;
    
    public $password;
    
    public $verifyCode;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ 'username', 'filter', 'filter' => 'trim' ],
            [ 'username', 'required' ],
            [ 'username', 'match', 'pattern' => '#^[\w_-]+$#i' ],
            [ 'username', 'unique', 'targetClass' => User::className(), 'message' => 'Пользователь с данным никнеймом уже существует' ],
            [ 'username', 'string', 'min' => 2, 'max' => 255 ],
            
            [ 'email', 'filter', 'filter' => 'trim' ],
            [ 'email', 'required' ],
            [ 'email', 'email' ],
            [ 'email', 'unique', 'targetClass' => User::className(), 'message' => 'Пользователь с таким почтовым ящиком уже зарегистрирован' ],
            
            [ 'password', 'required' ],
            [ 'password', 'string', 'min' => 6 ],
    
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
            $user->username = $this->username;
            $user->email = $this->email;
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
    
                Yii::$app->user->login($user, 3600 * 24);
            }
            
            return $user;
        }
        
        return null;
    }
    
    
    public function attributeLabels()
    {
        return [
            'username' => 'Никнейм',
            'email' => 'Почтовый ящик',
            'password' => 'Пароль',
            'verifyCode' => 'Капча',
        ];
    }
}
