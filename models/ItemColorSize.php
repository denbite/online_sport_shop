<?php

namespace app\models;

use app\components\models\Status;

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
    
    public static function getAllEnableItemColorSize()
    {
        
        // todo-cache: add cache (10 min)
        return Item::find()
                   ->from(Item::tableName() . ' item')
                   ->joinWith([ 'allColors colors' => function ($query)
                   {
                       $query->joinWith([ 'allSizes sizes' ]);
                   }, ])
                   ->where([
                               'item.status' => Status::STATUS_ACTIVE,
                               'colors.status' => Status::STATUS_ACTIVE,
                               'sizes.status' => Status::STATUS_ACTIVE,
                           ])
                   ->orderBy([ 'item.rate' => SORT_DESC ])
                   ->asArray()
                   ->all();
    }
    
    public static function getAllEnableItemColorSizeNames()
    {
        
        // todo-cache: add cache (5-10 min)
        $data = self::getAllEnableItemColorSize();
        
        $result = [];
        
        foreach ($data as $item) {
            foreach ($item['allColors'] as $color) {
                foreach ($color['allSizes'] as $size) {
                    $result[$size['id']] = $item['firm'] . ' ' . $item['model'] . ' ' . $color['color'] . ' ' . $size['size'];
                }
            }
        }
        
        return $result;
        
    }
    
    public function getColor()
    {
        return $this->hasOne(ItemColor::className(), [ 'id' => 'color_id' ]);
    }
}
