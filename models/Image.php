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
    
    const TYPE_ITEM = 1;
    
    const TYPE_CATEGORY = 2;
    
    const TYPE_PROMOTIONS = 3;
    
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
    
    public static function getTypes()
    {
        return [
            self::TYPE_ITEM => 'Item',
            self::TYPE_CATEGORY => 'Category',
            self::TYPE_PROMOTIONS => 'Promotions',
        ];
    }
}
