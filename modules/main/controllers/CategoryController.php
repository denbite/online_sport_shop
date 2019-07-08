<?php

namespace app\modules\main\controllers;

use app\components\helpers\ValueHelper;
use app\components\models\Status;
use app\models\Category;
use app\models\Item;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `main` module
 */
class CategoryController
    extends Controller
{
    
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    /**
     * Processing all slugs after /category/{$slug}
     *
     * @param bool $slug
     *
     * @return string
     */
    public function actionIndex($slug = false)
    {
        $slug = (int) preg_replace("[\D+]", "", $slug);
        
        // if empty after regexp check
        if (empty($slug)) {
            // get all nodes lvl=1
            
            $current = Category::findOne([ 'lvl' => 0, 'root' => 1 ]);
            
            $children = $current->children(1)->with('image')->andWhere([ 'active' => Status::STATUS_ACTIVE ])->all();
            
            return $this->render('index', [
                'children' => $children,
                'parents' => [],
                'current' => $current,
            ]);
        } elseif ($current = Category::findOne([ 'id' => ValueHelper::decryptValue($slug), 'active' => Status::STATUS_ACTIVE, ])) {
            $parents = $current->parents()->andWhere([ 'active' => Status::STATUS_ACTIVE ])->all();
            
            if (count($parents) == $current['lvl']) {
                $children = $current->children(1)->andWhere([ 'active' => Status::STATUS_ACTIVE ])->all();
                
                if (!empty($children)) {
                    return $this->render('index', [
                        'children' => $children,
                        'parents' => $parents,
                        'current' => $current,
                    ]);
                } else {
                    $items = Item::find()
                                 ->select([ 'item.*', 'MIN(sizes.price) as min_price' ])
                                 ->from(Item::tableName() . ' item')
                                 ->joinWith([ 'allColors colors' => function ($query)
                                 {
                                     $query->joinWith([ 'allSizes sizes', 'mainImage' ]);
                                 }, ])
                                 ->where([
                                     'item.category_id' => $current['id'],
                                     'item.status' => Status::STATUS_ACTIVE,
                                     'colors.status' => Status::STATUS_ACTIVE,
                                     'sizes.status' => Status::STATUS_ACTIVE,
                                 ])
                                 ->groupBy([ 'id' ])
                                 ->asArray()
                                 ->all();
                    
                    return $this->render('products', [
                        'items' => $items,
                        'parents' => $parents,
                        'current' => $current,
                    ]);
                    
                }
            }
            
        }
        
        throw new NotFoundHttpException('Страница не найдена');
    }
}
