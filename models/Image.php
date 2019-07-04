<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

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
            'type' => 'Type',
            'subject_id' => 'Subject ID',
            'url' => 'Url',
            'sort' => 'Sort',
            'created_at' => 'Created At',
        ];
    }
    
    public function upload($type, $subject_id)
    {
        foreach ($this->attachment as $pic) {
            echo '<pre>';
            var_dump($pic);
            echo '</pre>';
            die;
            // path, save image, load attrs, validate, save()
            
        }
        
        return false;
    }
}
