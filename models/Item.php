<?php

namespace app\models;

use app\components\models\Status;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "item".
 *
 * @property int $id
 * @property int $category_id
 * @property string $firm
 * @property string $model
 * @property string $collection
 * @property string $code
 * @property int $sale
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Item extends \yii\db\ActiveRecord
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
    }
    
    public static function getItemsIdName()
    {
        $data = self::getAllItems();
        
        if (is_array($data) and !empty($data)) {
            return ArrayHelper::map($data, 'id', 'model', 'firm');
        }
        
        return [];
    }
    
    public static function getAllItems()
    {
        //todo-cache: add cache
        return self::find()->asArray()->indexBy('id')->all();
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'category_id', 'code' ], 'required' ],
            [ [ 'category_id', 'status' ], 'integer' ],
            [ [ 'firm', 'model' ], 'string', 'max' => 128 ],
            [ [ 'collection', 'code' ], 'string', 'max' => 64 ],
            [ [ 'code' ], 'unique', 'message' => 'Товар с таким артикулом \'{value}\' уже существует' ],
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
            'category_id' => 'Категория',
            'firm' => 'Фирма',
            'model' => 'Модель',
            'collection' => 'Коллекция',
            'code' => 'Артикул',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
    
    public function getEnableColors()
    {
        return $this->hasMany(ItemColor::className(), [ 'item_id' => 'id' ])
                    ->where([ 'status' => Status::STATUS_ACTIVE ]);
    }
    
    public function getAllColors()
    {
        return $this->hasMany(ItemColor::className(), [ 'item_id' => 'id' ]);
    }
}
