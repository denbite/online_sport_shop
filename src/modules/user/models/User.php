<?php

namespace app\modules\user\models;

use app\components\helpers\TransactionHelper;
use app\models\AuthAssignment;
use Yii;
use yii\base\InvalidParamException;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * @property string auth_key
 * @property string name
 * @property string email
 * @property string phone
 * @property int    status
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    
    const STATUS_ACTIVE = 1;
    
    const STATUS_BLOCKED = 0;
    
    const STATUS_WAIT = 2;
    
    private $_newRoles = [];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
    
    public static function findIdentity($id)
    {
        return static::findOne([ 'id' => $id, 'status' => self::STATUS_ACTIVE ]);
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }
    
    /**
     * Finds user by email
     *
     * @param string $email
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
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetTokenAndEmail($token, $email)
    {
        if (!static::isPasswordResetTokenValid($token) or !is_string($email)) {
            throw new InvalidParamException('Время действия токена истекло');
        }
        
        return static::findOne([
                                   'password_reset_token' => $token,
                                   'email' => $email,
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
            [ 'name', 'string', 'min' => 2, 'max' => 32 ],
            
            [ [ 'email', 'phone' ], 'required' ],
            [ 'email', 'email' ],
            [ 'email', 'unique', 'targetClass' => self::className(), 'message' => 'Пользователь с такой почтой уже существует' ],
            [ 'email', 'string', 'max' => 255 ],
            
            [ 'phone', 'string' ],
            
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
            self::STATUS_BLOCKED => 'Заблокированный',
            self::STATUS_ACTIVE => 'Активный',
            self::STATUS_WAIT => 'Ожидание',
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
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
    
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
    public function getAuthKey()
    {
        return $this->auth_key;
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
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'name' => 'Имя',
            'email' => 'Почтовый ящик',
            'status' => 'Статус',
        ];
    }
    
    public function loadNewRoles(array $roles)
    {
        foreach (array_keys($roles) as $role) {
            if (!Yii::$app->authManager->getRole($role)) {
                return false;
            }
            $this->_newRoles[$role] = true;
        }
        
        return true;
    }
    
    public function save($runValidation = true, $attributeNames = null)
    {
        if (is_array($this->_newRoles)) {
            AuthAssignment::deleteRolesById($this->id);
            
            TransactionHelper::wrap(function ()
            {
                foreach (array_keys($this->_newRoles) as $role) {
                    if (!Yii::$app->authManager->assign(Yii::$app->authManager->getRole($role), $this->id)) {
                        throw new \yii\db\Exception('Не удалось сохранить роль для пользователя: ' . $this->name);
                    }
                }
            });
        }
        
        return parent::save($runValidation, $attributeNames);
    }
    
}
