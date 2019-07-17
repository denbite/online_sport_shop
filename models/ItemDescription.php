<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "item_description".
 *
 * @property int    $id
 * @property int    $item_id
 * @property string $small_text
 * @property string $small_list
 * @property string $text
 * @property string $list serialized array
 * @property int    $updated_at
 * @property int    $created_at
 */
class ItemDescription
    extends \yii\db\ActiveRecord
{
    
    const ITEMS_SEPARATOR = ';';
    
    const PARTS_SEPARATOR = ':';
    
    public $small_list_array;
    
    public $list_array;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_description';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'item_id' ], 'required' ],
            [ [ 'item_id' ], 'integer' ],
            [ [ 'item_id' ], 'unique' ],
            [ [ 'small_text', 'small_list', 'text', 'list' ], 'string', 'max' => 255 ],
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
            'item_id' => 'Товар',
            'small_text' => 'Краткое описание',
            'small_list' => 'Краткий список',
            'small_list_array' => 'Преимущества',
            'text' => 'Описание',
            'list' => 'Таблица характеристик',
            'updated_at' => 'Дата обновления',
            'created_at' => 'Дата создания',
        ];
    }
}
