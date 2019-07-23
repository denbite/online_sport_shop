<?php

namespace app\models;

use app\components\models\Status;
use creocoder\nestedsets\NestedSetsBehavior;
use kartik\tree\models\Tree;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property int    $id
 * @property int    $lft
 * @property int    $rgt
 * @property int    $lvl
 * @property string $name
 */
class Category extends Tree
{
    
    public function rules()
    {
        return array_merge(parent::rules(), [
            [ 'description', 'string' ],
        ]);
    }
    
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
            'lvl' => 'Глубина',
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
     * @param int $root
     *
     * @return null|array
     */
    public static function getAllCategoriesByRoot($root = 1)
    {
        // todo-cache: add cache
        
        return self::findOne([ 'root' => $root, 'lvl' => 0 ])->leaves()->indexBy('id')->all();
    }
    
    /**
     * Выводит всю цепочку от дерева до листьев
     *
     * @param int $root Номер дерева
     *
     * @return array|mixed
     */
    public static function getCategoriesIndexNameWithParents($root = 1)
    {
        $data = self::getAllCategoriesByRoot($root);
        
        if (is_array($data) and !empty($data)) {
            $result = [];
            
            foreach ($data as $one) {
                $path = array_column($one->parents()->all(), 'name');
                
                array_push($path, $one->id);
                
                ArrayHelper::setValue($result, $path, $one->name);
            }
            
            //todo-cache: add cache
            return $result;
        }
        
        return [];
    }
    
    public function getImage()
    {
        return $this->hasOne(Image::className(), [ 'subject_id' => 'id' ])
                    ->andWhere([ 'sort' => 0, 'type' => Image::TYPE_CATEGORY ]);
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->active != $this->oldAttributes['active']) {
                
                if (count($this->parents()->andWhere([ 'active' => Status::STATUS_ACTIVE ])->all()) == $this->lvl) {
                    self::updateAll([ 'active' => $this->active ],
                        [ 'in', 'id', array_column($this->children()->all(), 'id') ]);
                    
                } else {
                    $this->active = $this->oldAttributes['active'];
                }
                
            }
            
            
            return true;
        } else {
            return false;
        }
    }
}
