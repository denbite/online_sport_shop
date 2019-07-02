<?php

namespace app\models;

use creocoder\nestedsets\NestedSetsBehavior;
use kartik\tree\models\Tree;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property int $lft
 * @property int $rgt
 * @property int $lvl
 * @property string $name
 */
class Category extends Tree
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }
    
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'root' => 'Корень',
            'lft' => 'Левый индекс',
            'rgt' => 'Правый индекс',
            'lvl' => 'Уровень',
            'name' => 'Название',
        ];
    }
    
    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'root',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'depthAttribute' => 'lvl',
            ],
        ];
    }
    
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
    
}
