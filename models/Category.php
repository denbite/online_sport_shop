<?php

namespace app\models;

use creocoder\nestedsets\NestedSetsBehavior;
use kartik\tree\models\Tree;
use yii\helpers\ArrayHelper;

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
    
    /**
     * Возвращает все конечные узлы определенного дерева
     *
     * @param $name
     *
     * @return null|array
     */
    public static function getAllCategoriesByRoot($name = 'Каталог')
    {
        // todo-cache: add cache
        
        if (is_string($name)) {
            return self::findOne([ 'name' => $name ])->leaves()->indexBy('id')->all();
        }
        
        return [];
    }
    
    /**
     * Выводит всю цепочку от дерева до листьев
     *
     * @param string $name Имя дерева
     * @param bool $root Вывод с названием дерева или нет
     *
     * @return array|mixed
     */
    public static function getCategoriesIndexNameWithParents($name = 'Каталог', $root = false)
    {
        $data = self::getAllCategoriesByRoot($name);
        
        if (is_array($data) and !empty($data)) {
            $result = [];
            
            foreach ($data as $one) {
                $path = array_column($one->parents()->all(), 'name');
                
                array_push($path, $one->id);
                
                ArrayHelper::setValue($result, $path, $one->name);
            }
            
            //todo-cache: add cache
            return $root ? $result : $result[$name];
        }
        
        return [];
    }
}
