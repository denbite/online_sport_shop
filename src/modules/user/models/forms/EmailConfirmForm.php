<?php

namespace app\modules\user\models\forms;

use app\modules\user\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;

class EmailConfirmForm extends Model
{
    
    /**
     * @var User
     */
    private $_user;
    
    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config
     *
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException(Yii::t('user', '_ERROR_EMPTY_TOKEN'));
        }
        $this->_user = User::findByEmailConfirmToken($token);
        if (!$this->_user) {
            throw new InvalidParamException(Yii::t('user', '_ERROR_INVALID_TOKEN'));
        }
        parent::__construct($config);
    }
    
    /**
     * Confirm email.
     *
     * @return boolean if email was confirmed.
     */
    public function confirmEmail()
    {
        $user = $this->_user;
        $user->status = User::STATUS_ACTIVE;
        $user->removeEmailConfirmToken();
        
        return $user->save();
    }
}
