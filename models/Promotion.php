<?php

namespace app\models;

use app\components\models\Status;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "promotion".
 *
 * @property int $id
 * @property int $type
 * @property int $sale
 * @property int $status
 * @property int $publish_from
 * @property int $publish_to
 * @property int $updated_at
 * @property int $created_at
 */
class Promotion
    extends \yii\db\ActiveRecord
{
    
    const TYPE_PERCENT = 1;
    
    const TYPE_VALUE = 2;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion';
    }
    
    /**
     * Возвращает все типы акций
     *
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_PERCENT => '%',
            self::TYPE_VALUE => '₴',
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //            [ [ 'type' ], 'required' ],
            [ [ 'type', 'sale', 'status' ], 'integer' ],
            [ [ 'publish_from', 'publish_to' ], 'safe' ],
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
            'sale' => 'Скидка',
            'status' => 'Статус',
            'publish_from' => 'Дата начала',
            'publish_to' => 'Дата конца',
            'updated_at' => 'Дата обновления',
            'created_at' => 'Дата создания',
        ];
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    public function getItems()
    {
        return $this->hasMany(Item::className(), [ 'id' => 'item_id' ])
                    ->viaTable(PromotionItem::tableName(), [ 'promotion_id' => 'id' ]);
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            
            $this->publish_from = strtotime($this->publish_from);
            $this->publish_to = strtotime($this->publish_to);
            
            if ($this->publish_from <= $this->publish_to) {
                return true;
            }
        }
        
        return false;
    }
    
    public static function getItemColorSizeNamesByPromotion($promotion_id)
    {
        // todo-cache: add cache by md5(promo_id)
        $sizes = Promotion::find()
                          ->from(Promotion::tableName() . ' promo')
                          ->joinWith([ 'sizes sizes' => function ($query)
                          {
                              $query->joinWith([ 'color color' => function ($query)
                              {
                                  $query->joinWith([ 'item item' ]);
                              } ]);
                          } ])
                          ->where([
                              'promo.status' => Status::STATUS_ACTIVE,
                              'promo.id' => $promotion_id,
                          ])
                          ->asArray()
                          ->one();
        
        return ( !empty($sizes) and array_key_exists('sizes', $sizes) ) ? $sizes['sizes'] : [];
        
    }
}
