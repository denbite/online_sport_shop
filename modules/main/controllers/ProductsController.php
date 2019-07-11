<?php

namespace app\modules\main\controllers;

use app\components\helpers\ValueHelper;
use app\components\models\Status;
use app\models\Category;
use app\models\Item;
use app\models\ItemColor;
use app\models\ItemColorSize;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Default controller for the `main` module
 */
class ProductsController
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
     * Processing all requests like category/{slug}
     *
     * @param bool $slug
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionCategory($slug = false)
    {
        $slug = (int) preg_replace("[\D+]", "", $slug);
        
        // if empty after regexp check
        if (empty($slug)) {
            // get all nodes lvl=1
            
            $current = Category::findOne([ 'lvl' => 0, 'root' => 1 ]);
            
            $children = $current->children(1)->with('image')->andWhere([ 'active' => Status::STATUS_ACTIVE ])->all();
            
            return $this->render('category', [
                'children' => $children,
                'parents' => [],
                'current' => $current,
            ]);
        } elseif ($current = Category::findOne([ 'id' => ValueHelper::decryptValue($slug), 'active' => Status::STATUS_ACTIVE, ])) {
            $activeParents = $current->parents()->andWhere([ 'active' => Status::STATUS_ACTIVE ])->all();
            
            if (count($activeParents) == $current['lvl']) {
                $children = $current->children(1)->andWhere([ 'active' => Status::STATUS_ACTIVE ])->all();
                
                if (!empty($children)) {
                    return $this->render('category', [
                        'children' => $children,
                        'parents' => $activeParents,
                        'current' => $current,
                    ]);
                } else {
                    return $this->redirect([ 'catalog', 'slug' => ValueHelper::encryptValue($current['id']) ]);
                }
            }
            
        }
        
        throw new NotFoundHttpException('Страница не найдена');
    }
    
    public function actionCatalog($slug = false)
    {
        $slug = (int) preg_replace("[\D+]", "", $slug);
        
        if (empty($slug)) {
            
            // all items, order by rate descending
            
            return $this->render('catalog', [
                'parents' => [],
                'current' => [],
                'items' => [],
            ]);
        } elseif ($current = Category::findOne([ 'id' => ValueHelper::decryptValue($slug), 'active' => Status::STATUS_ACTIVE ])) {
            if ($current['rgt'] != $current['lft'] + 1) {
                return $this->redirect([ 'category', 'slug' => ValueHelper::encryptValue($current['id']) ]);
            } else {
                $activeParents = $current->parents()->andWhere([ 'active' => Status::STATUS_ACTIVE ])->all();
                
                if (count($activeParents) == $current['lvl']) {
                    
                    $query = Item::find()
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
                                 ->orderBy([ 'rate' => SORT_DESC ]);
                    
                    $pages = new Pagination([
                            'totalCount' => $query->count(),
                            'pageSize' => 18,
                            'defaultPageSize' => 18,
                            'forcePageParam' => false,
                        ]
                    );
                    
                    $items = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
                    
                    return $this->render('catalog', [
                        'items' => $items,
                        'parents' => $activeParents,
                        'current' => $current,
                        'pages' => $pages,
                    ]);
                }
            }
        }
        
        throw new NotFoundHttpException('Страница не найдена');
    }
    
    public function actionProduct($slug)
    {
        $slug = (int) preg_replace("[\D+]", "", $slug);
        
        if (!empty($slug)) {
            // let's find item
            // check if exists that category and him parents
            $item = Item::find()
                        ->from(Item::tableName() . ' item')
                        ->joinWith([ 'allColors colors' => function ($query)
                        {
                            $query->joinWith([ 'allSizes sizes', 'allImages' ]);
                        }, ])
                        ->where([
                            'item.id' => ValueHelper::decryptValue($slug),
                            'item.status' => Status::STATUS_ACTIVE,
                            'colors.status' => Status::STATUS_ACTIVE,
                            'sizes.status' => Status::STATUS_ACTIVE,
                        ])
                        ->asArray()
                        ->one();
            
            if (!empty($item)) {
                
                $current = Category::findOne([ 'id' => $item['category_id'], 'active' => Status::STATUS_ACTIVE ]);
                
                $parents = ArrayHelper::toArray(array_merge($current->parents()->all(), [ $current ]));
                
                unset($current);
    
                // check if exists this item in any promotions
                
                return $this->render('product', [
                    'item' => $item,
                    'parents' => $parents,
                ]);
            }
            
        }
        
        throw new NotFoundHttpException('Страница не найдена');
    }
    
    public function actionQuery()
    {
        $result = [];
        
        if (Yii::$app->request->isPost and Yii::$app->request->isAjax and $post = Yii::$app->request->post()) {
            switch ($post['query']) {
                case 'changeColor':
                    // todo-cache: add cache (5 min or more)
                    $result = ItemColor::find()
                                       ->from(ItemColor::tableName() . ' color')
                                       ->joinWith([ 'allSizes sizes' ])
                                       ->where([
                                           'color.id' => ValueHelper::decryptValue($post['data']),
                                           'color.status' => Status::STATUS_ACTIVE,
                                           'sizes.status' => Status::STATUS_ACTIVE,
                                       ])
                                       ->asArray()
                                       ->one();
                    
                    foreach ($result['allSizes'] as &$size) {
                        $size['new_price'] = ValueHelper::formatPrice($size['price']);
                    }
                    
                    break;
                case 'changeSize':
                    $result = ItemColorSize::find()
                                           ->where([
                                               'id' => $post['data'],
                                               'status' => Status::STATUS_ACTIVE,
                                           ])
                                           ->asArray()
                                           ->one();
                    
                    break;
                default:
                    $result = null;
                    break;
            }
        } else {
            exit('only POST method allow');
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        return $result;
        
    }
}
