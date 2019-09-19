<?php

namespace app\models;

/**
 * This is the model class for table "order_size".
 *
 * @property int $order_id
 * @property int $size_id
 * @property int $quantity
 * @property int $cost
 */
class OrderSize
    extends \yii\db\ActiveRecord
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_size';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'order_id', 'size_id', 'quantity', 'cost' ], 'required' ],
            [ [ 'order_id', 'size_id', 'quantity', 'cost' ], 'integer' ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'size_id' => 'Size ID',
            'quantity' => 'Quantity',
            'cost' => 'Cost',
        ];
    }
    
    public function getSize()
    {
        return $this->hasOne(ItemColorSize::className(), [ 'id' => 'size_id' ]);
    }
}
