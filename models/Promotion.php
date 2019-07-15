<?php

namespace app\models;

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
    
    public function getSizes()
    {
        return $this->hasMany(ItemColorSize::className(), [ 'id' => 'size_id' ])
                    ->viaTable('{{%size_promotion}}', [ 'promotion_id' => 'id' ]);
    }
}
