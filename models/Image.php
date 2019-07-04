<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "image".
 *
 * @property int    $id
 * @property int    $type
 * @property int    $subject_id
 * @property string $url
 * @property int    $sort
 * @property int    $created_at
 */
class Image
    extends \yii\db\ActiveRecord
{
    
    const TYPE_ITEM = 0;
    
    const TYPE_CATEGORY = 1;
    
    const TYPE_PROMOTIONS = 2;
    
    public $attachment;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'type', 'subject_id', 'url', ], 'required' ],
            [ [ 'type', 'subject_id', 'sort' ], 'integer' ],
            [ [ 'sort' ], 'default', 'value' => function ($model)
            {
                return count(self::find()
                                 ->where([ 'type' => $model->type, 'subject_id' => $model->subject_id ])
                                 ->all());
            } ],
            [ [ 'url' ], 'string', 'max' => 255 ],
            [ [ 'attachment' ], 'file', 'skipOnEmpty' => false, 'maxFiles' => 10, 'extensions' => 'png, jpg, jpeg' ],
        ];
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип',
            'subject_id' => 'Объект ID',
            'url' => 'Путь',
            'sort' => 'Сортировка',
            'created_at' => 'Дата создания',
        ];
    }
    
    public function upload()
    {
        foreach ($this->attachment as $pic) {
            $subject = self::getTypes();
            if (array_key_exists($this->type, $subject)) {
                $subject = $subject[$this->type];
                $this->url = Yii::$app->security->generateRandomString(16) . '_' . time() . '.' . $pic->extension;
                $path = Yii::getAlias('@webroot') . '/files/' . $subject . '/' . $subject . '-' . $this->subject_id . '/';
                
                if (!file_exists($path)) {
                    FileHelper::createDirectory($path, 0777);
                }
                
                if ($this->validate()) {
                    if (!$pic->saveAs($path . $this->url) or !$this->save(false)) {
                        return false;
                    }
                }
            }
            
        }
        
        return true;
    }
    
    public static function getTypes()
    {
        return [
            self::TYPE_ITEM => 'Item',
            self::TYPE_CATEGORY => 'Category',
            self::TYPE_PROMOTIONS => 'Promotions',
        ];
    }
}
