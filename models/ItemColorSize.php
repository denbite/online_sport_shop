<?php

namespace app\models;

/**
 * This is the model class for table "item_color_size".
 *
 * @property int    $id
 * @property int    $color_id
 * @property string $size
 * @property int    $quantity
 * @property int    $status
 */
class ItemColorSize extends \yii\db\ActiveRecord
{
    
    const WITHOUT_SIZE = '-';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_color_size';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'color_id', 'price' ], 'required' ],
            [ [ 'color_id', 'quantity', 'price', 'status', ], 'integer' ],
            [ [ 'size' ], 'string', 'max' => 32 ],
            [ [ 'size' ], 'default', 'value' => self::WITHOUT_SIZE ],
            [ [ 'size' ], 'unique', 'targetAttribute' => [ 'color_id', 'size' ], 'message' => 'Для данной расцветки товара уже существует такой размер \'{value}\'' ],
            [ [ 'quantity', 'price' ], 'integer', 'min' => 0 ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color_id' => 'Цвет',
            'size' => 'Размер',
            'quantity' => 'Количество',
            'price' => 'Цена',
            'status' => 'Статус',
        ];
    }
    
    public function getColor()
    {
        return $this->hasOne(ItemColor::className(), [ 'id' => 'color_id' ]);
    }
}
