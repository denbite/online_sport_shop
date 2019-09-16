<?php

namespace app\modules\user\models\forms;

use app\modules\user\models\User;
use Yii;
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
                'targetClass' => '\app\modules\user\models\User',
                'filter' => [ 'status' => User::STATUS_ACTIVE ],
                'message' => 'Такого пользователя не существует.',
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
                return Yii::$app->mailer->compose('@app/modules/user/mails/reset-password-request', [ 'user' => $user ])
                                        ->setFrom([ Yii::$app->params['senderEmail'] => Yii::$app->params['senderName'] ])
                                        ->setTo($this->email)
                                        ->setSubject($user['name'] . ', вы можете легко восстановить доступ на Aquista')
                                        ->send();
            }
        }
        
        return false;
    }
}
