<?php

namespace app\models;

use app\components\helpers\ValueHelper;

/**
 * This is the model class for table "item_color_size".
 *
 * @property int    $id
 * @property int    $color_id
 * @property string $size
 * @property int    $quantity
 * @property int    $base_price
 * @property int    $sell_price
 * @property int    $sale_price
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
            [ [ 'color_id', 'base_price' ], 'required' ],
            [ [ 'color_id', 'status', ], 'integer' ],
            [ [ 'size' ], 'string', 'max' => 32 ],
            [ [ 'size' ], 'default', 'value' => self::WITHOUT_SIZE ],
            [ [ 'size' ], 'unique', 'targetAttribute' => [ 'color_id', 'size' ], 'message' => 'Для данной расцветки товара уже существует такой размер \'{value}\'' ],
            [ [ 'quantity', 'base_price', 'sell_price', 'sale_price' ], 'integer', 'min' => 0 ],
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
            'base_price' => 'Базовая цена',
            'sell_price' => 'Цена закупки',
            'sale_price' => 'Цена при акциях',
            'status' => 'Статус',
        ];
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            
            $this->sell_price = ValueHelper::format($this->base_price);
            
            return true;
        } else {
            return false;
        }
    }
    
    public function getColor()
    {
        return $this->hasOne(ItemColor::className(), [ 'id' => 'color_id' ]);
    }
    
    public function getPromotion()
    {
        return $this->color->item->promotion;
    }
    
    //    public static function updateSalePrice($items = [], $type, $sale)
    //    {
    //        if (is_array($items) and !empty($items) and array_key_exists($type, Promotion::getTypes()) and !empty($sale)) {
    //            $sizes = ItemColorSize::find()
    //                                  ->joinWith([ 'color' => function ($query)
    //                                  {
    //                                      $query->joinWith([ 'item item' ]);
    //                                  } ])
    //                                  ->where([
    //                                              'in', 'item.id', $items,
    //                                          ])
    //                                  ->all();
    //
    //            if ($type == Promotion::TYPE_PERCENT and $sale > 0 and $sale < 100) {
    //                $coef = (float) ( 100 - $sale ) / 100;
    //
    //                foreach ($sizes as $size) {
    //                    $size->sale_price = ValueHelper::format($size->base_price * $coef);
    //                    if (!$size->save()) {
    //                        throw new Exception('Не удалось изменить акционные цены');
    //                    }
    //                }
    //
    //            } elseif ($type == Promotion::TYPE_VALUE and $sale > 0) {
    //                foreach ($sizes as $size) {
    //                    $size->sale_price = ValueHelper::format($size->base_price) - $sale;
    //                    if (!$size->save()) {
    //                        throw new Exception('Не удалось изменить акционные цены');
    //                    }
    //                }
    //            }
    //        }
    //    }
}
