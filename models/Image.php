<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

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
    
    public function getFilePath()
    {
        $class = self::getTypes()[$this->type];
        
        return "/files/{$class}/{$class}-{$this->subject_id}/";
    }
    
    public function getColor()
    {
        return $this->hasOne(ItemColor::className(), [ 'id' => 'subject_id' ]);
    }
    
    public static function getTypes()
    {
        return [
            self::TYPE_ITEM => 'Item',
            self::TYPE_CATEGORY => 'Category',
            self::TYPE_PROMOTIONS => 'Promotions',
        ];
    }
    
    public static function getUrlsByColor($color_id)
    {
        // todo-cache: add cache(30 sec)
        $data = ItemColor::find()
                         ->with('item')
                         ->where([
                                     'id' => $color_id,
                                 ])
                         ->asArray()
                         ->one();
        
        $urls = array_column(self::getImagesByColor($color_id), 'url');
        
        $class = self::getTypes()[self::TYPE_ITEM];
        
        $path = "/files/{$class}/{$class}-{$color_id}/";
        
        foreach ($urls as &$url) {
            $url = $path . $url;
        }
        
        return $urls;
    }
    
    public static function getImagesByColor($color_id)
    {
        // todo-cache: add cache(30 sec) and clear value before insert in query
        
        return self::find()
                   ->where([
                               'subject_id' => $color_id,
                               'type' => self::TYPE_ITEM,
                           ])
                   ->orderBy([ 'sort' => SORT_ASC ])
                   ->asArray(false)
                   ->all();
    }
    
    /**
     * @param $color_id
     *
     * @return array
     */
    public static function getInitialPreviewConfigByColor($color_id)
    {
        return ArrayHelper::toArray(self::getImagesByColor($color_id), [
                                                                         Image::className() => [
                                                                             'caption' => 'url',
                                                                             'key' => 'id',
                                                                         ],
                                                                     ]
        );
    }
}
