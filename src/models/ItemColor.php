<?php

namespace app\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "item_color".
 *
 * @property int    $id
 * @property int    $item_id
 * @property string $code
 * @property string $color
 * @property int    $status
 */
class ItemColor extends \yii\db\ActiveRecord
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_color';
    }
    
    public static function getColorById($id)
    {
        if (is_string($id) or is_integer($id)) {
            if (array_key_exists($id, self::getAllColors())) {
                return self::getAllColors()[$id];
            }
        }
        
        return [];
    }
    
    public static function getAllColors()
    {
        //todo-cache: add cache
        
        return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'color', 'item_id');
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'item_id', 'code' ], 'required' ],
            [ [ 'item_id', 'status' ], 'integer' ],
            [ [ 'code' ], 'string', 'max' => 64 ],
            [ [ 'color' ], 'string', 'max' => 128 ],
            [ [ 'html' ], 'string', 'length' => 7 ],
            [ [ 'code' ], 'unique', 'targetAttribute' => [ 'item_id', 'code' ], 'message' => 'У данного товара уже существует такой код цвета \'{value}\' ' ],
            [ [ 'color' ], 'unique', 'targetAttribute' => [ 'item_id', 'color' ], 'message' => 'У данного товара уже существует такой цвет \'{value}\' ' ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Товар',
            'code' => 'Артикул',
            'color' => 'Цвет',
            'html' => 'HTML-код цвета',
            'status' => 'Статус',
        ];
    }
    
    public function getAllSizes()
    {
        return $this->hasMany(ItemColorSize::className(), [ 'color_id' => 'id' ]);
    }
    
    public function getAllImages()
    {
        return $this->hasMany(Image::className(), [ 'subject_id' => 'id' ])
                    ->andWhere([ 'type' => Image::TYPE_ITEM ])
                    ->orderBy([ 'sort' => SORT_ASC ]);
    }
    
    public function getMainImage()
    {
        return $this->hasOne(Image::className(), [ 'subject_id' => 'id' ])
                    ->andWhere([ 'type' => Image::TYPE_ITEM, 'sort' => 0 ]);
    }
    
    public function getItem()
    {
        return $this->hasOne(Item::className(), [ 'id' => 'item_id' ]);
    }
}
