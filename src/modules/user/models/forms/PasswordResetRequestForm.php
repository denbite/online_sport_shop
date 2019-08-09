<?php

namespace app\modules\user\models\forms;

use app\modules\user\models\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    
    public $email;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ 'email', 'filter', 'filter' => 'trim' ],
            [ 'email', 'required' ],
            [ 'email', 'email' ],
            [ 'email', 'exist',
                //'targetClass' => '\common\models\User',
                'targetClass' => '\app\modules\user\models\User',
                'filter' => [ 'status' => User::STATUS_ACTIVE ],
                'message' => 'There is no user with such email.',
            ],
        ];
    }
    
    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
                                  'status' => User::STATUS_ACTIVE,
                                  'email' => $this->email,
                              ]);
        
        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }
            
            if ($user->save()) {
                //return \Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                return \Yii::$app->mailer->compose('@app/modules/user/mails/passwordReset', [ 'user' => $user ])
                                         ->setFrom([ \Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot' ])
                                         ->setTo($this->email)
                                         ->setSubject('Password reset for ' . \Yii::$app->name)
                                         ->send();
            }
        }
        
        return false;
    }
}
