<?php

namespace app\models;

/**
 * This is the model class for table "promotion_item".
 *
 * @property int $item_id
 * @property int $promotion_id
 */
class PromotionItem
    extends \yii\db\ActiveRecord
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion_item';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'item_id', 'promotion_id' ], 'required' ],
            [ [ 'item_id', 'promotion_id' ], 'integer' ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item ID',
            'promotion_id' => 'Promotion ID',
        ];
    }
}
