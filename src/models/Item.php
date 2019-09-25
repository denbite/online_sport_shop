<?php

namespace app\models;

use app\components\models\Status;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "item".
 *
 * @property int    $id
 * @property int    $category_id
 * @property string $firm
 * @property string $model
 * @property string $collection
 * @property string $code
 * @property int    $sale
 * @property int    $status
 * @property int    $created_at
 * @property int    $updated_at
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
            [ [ 'category_id' ], 'required' ],
            [ [ 'category_id', 'status' ], 'integer' ],
            [ [ 'rate' ], 'integer', 'min' => 0, 'max' => 100 ],
            [ [ 'firm', 'model' ], 'string', 'max' => 128 ],
            [ [ 'collection' ], 'string', 'max' => 64 ],
            [ [ 'model' ], 'unique', 'targetAttribute' => [ 'category_id', 'firm', 'model', 'collection' ], 'message' => 'Такая модель \'{value}\' уже существует' ],
        
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
            'rate' => 'Рейтинг',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
    
    public function getAllColors()
    {
        return $this->hasMany(ItemColor::className(), [ 'item_id' => 'id' ]);
    }
    
    public function getDescription()
    {
        return $this->hasOne(ItemDescription::className(), [ 'item_id' => 'id' ]);
    }
    
    public function getPromotion()
    {
        return $this->hasOne(Promotion::className(), [ 'id' => 'promotion_id' ])
                    ->viaTable(PromotionItem::tableName(), [ 'item_id' => 'id' ])
                    ->andWhere([ 'and', [ '<', 'publish_from', time() ], [ '>', 'publish_to', time() ] ])
                    ->andWhere([ 'status' => Status::STATUS_ACTIVE ])
                    ->orderBy([ 'publish_from' => SORT_DESC ]);
    }
    
    public function getCategory()
    {
        return $this->hasOne(Category::className(), [ 'id' => 'category_id' ]);
    }
}
