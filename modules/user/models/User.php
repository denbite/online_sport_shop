<?php

namespace app\modules\user\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * @property string auth_key
 * @property string username
 * @property string email
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    
    const STATUS_ACTIVE = 1;
    
    const STATUS_BLOCKED = 0;
    
    const STATUS_WAIT = 2;
    
    private static $_myRoles = false;
    
    protected $role_name;
    
    public static function findIdentity($id)
    {
        return static::findOne([ 'id' => $id, 'status' => self::STATUS_ACTIVE ]);
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }
    
    /**
     * Finds user by username
     *
     * @param string $username
     *
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne([ 'username' => $username ]);
    }
    
    /**
     * Finds user by username
     *
     * @param string $username
     *
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne([ 'email' => $email ]);
    }
    
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        
        return static::findOne([
                                   'password_reset_token' => $token,
                                   'status' => self::STATUS_ACTIVE,
                               ]);
    }
    
    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        
        return $timestamp + $expire >= time();
    }
    
    /**
     * @param string $email_confirm_token
     *
     * @return static|null
     */
    public static function findByEmailConfirmToken($email_confirm_token)
    {
        return static::findOne([ 'email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_WAIT ]);
    }
    
    /**
     * @return array
     */
    public static function getStatusCssClass()
    {
        return [
            self::STATUS_BLOCKED => 'danger',
            self::STATUS_ACTIVE => 'success',
            self::STATUS_WAIT => 'default',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ 'username', 'required' ],
            [ 'username', 'match', 'pattern' => '#^[\w_-]+$#i' ],
            [ 'username', 'unique', 'targetClass' => self::className(), 'message' => 'Пользователь с таким нийнеймом уже существует' ],
            [ 'username', 'string', 'min' => 2, 'max' => 255 ],
            
            [ 'email', 'required' ],
            [ 'email', 'email' ],
            [ 'email', 'unique', 'targetClass' => self::className(), 'message' => 'Пользователь с такой почтой уже существует' ],
            [ 'email', 'string', 'max' => 255 ],
            
            [ 'status', 'integer' ],
            [ 'status', 'default', 'value' => self::STATUS_ACTIVE ],
            [ 'status', 'in', 'range' => array_keys(self::getStatusesArray()) ],
        ];
    }
    
    /**
     * @return array
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_BLOCKED => Yii::t('app', '_STATUS_BLOCKED'),
            self::STATUS_ACTIVE => Yii::t('app', '_STATUS_ACTIVE'),
            self::STATUS_WAIT => Yii::t('app', '_STATUS_WAIT'),
        ];
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    
    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        if (empty(self::$_myRoles)) {
            /* @var CheckAccessInterface */
            $authManager = Yii::$app->authManager;
            $allRoles = $authManager->getChildren($this->getRuleName());
            foreach ($allRoles as $roleName => $role) {
                self::$_myRoles[] = $roleName;
            }
        }
        if (is_array(self::$_myRoles)) {
            return in_array($permissionName, self::$_myRoles, false);
        }
        
        return false;
    }
    
    public function getRuleName()
    {
        $roleName = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
        if ($roleName) {
            return array_shift($roleName)->name;
        }
        
        return 'user';
    }
    
    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    
    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
            }
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    
    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    
    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    /**
     * Generates email confirmation token
     */
    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }
    
    /**
     * Removes email confirmation token
     */
    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }
    
    /**
     * @return mixed
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }
    
    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'username' => 'Никнейм',
            'email' => 'e-mail',
            'status' => 'Статус',
        ];
    }
    
}
