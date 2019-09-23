<?php

namespace app\modules\main\controllers;

use app\components\helpers\SeoHelper;
use app\components\helpers\ValueHelper;
use app\components\models\Status;
use app\models\Category;
use app\models\Image;
use app\models\Item;
use app\models\ItemDescription;
use Yii;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
        
        SeoHelper::putDefaultTags();
        
        // if empty after regexp check
        if (empty($slug)) {
            // get all nodes lvl=1
            
            $current = Category::findOne([ 'lvl' => 0, 'root' => 1 ]);
            
            $query = $current->children(1)->with('image')->andWhere([ 'active' => Status::STATUS_ACTIVE ]);
            
            $pages = new Pagination([
                                        'totalCount' => $query->count(),
                                        'pageSize' => 18,
                                        'defaultPageSize' => 18,
                                        'forcePageParam' => false,
                                    ]
            );
            
            $children = $query->offset($pages->offset)->limit($pages->limit)->all();
            
            return $this->render('category', [
                'children' => $children,
                'parents' => [],
                'current' => $current,
                'pages' => $pages,
            ]);
        } elseif ($current = Category::findOne([ 'id' => ValueHelper::decryptValue($slug), 'active' => Status::STATUS_ACTIVE, ])) {
    
            $query = $current->children(1)->andWhere([ 'active' => Status::STATUS_ACTIVE ]);
    
            $pages = new Pagination([
                                        'totalCount' => $query->count(),
                                        'pageSize' => 18,
                                        'defaultPageSize' => 18,
                                        'forcePageParam' => false,
                                    ]
            );
    
            $children = $query->offset($pages->offset)->limit($pages->limit)->all();
    
            if (!empty($children)) {
                return $this->render('category', [
                    'children' => $children,
                    'parents' => $current->parents()->all(),
                    'current' => $current,
                    'pages' => $pages,
                ]);
            } else {
                return $this->redirect([ 'catalog', 'slug' => ValueHelper::encryptValue($current['id']) ]);
            }
            
        }
        
        throw new NotFoundHttpException('Страница не найдена');
    }
    
    public function actionCatalog($slug = false)
    {
        $slug = (int) preg_replace("[\D+]", "", $slug);
        
        SeoHelper::putDefaultTags();
    
        $params = Yii::$app->request->getQueryParams();
        
        if (empty($slug)) {
            
            $query = Item::find()
                         ->select([ 'item.*' ])
                         ->from(Item::tableName() . ' item')
                         ->joinWith([ 'allColors colors' => function ($query)
                         {
                             $query->joinWith([ 'allSizes sizes', 'mainImage' ]);
                         }, 'description',
                                        'category' => function ($query)
                                        {
                                            $query->andWhere([ 'active' => Status::STATUS_ACTIVE ]);
                                        },
                                    ])
                         ->with([ 'promotion' => function ($query)
                         {
                             $query->andWhere([ 'status' => Status::STATUS_ACTIVE ]);
                         } ])
                         ->andWhere([
                                        'item.status' => Status::STATUS_ACTIVE,
                                        'colors.status' => Status::STATUS_ACTIVE,
                                        'sizes.status' => Status::STATUS_ACTIVE,
                                    ])
                         ->groupBy([ 'id' ])
                         ->orderBy([
                              'rate' => SORT_DESC,
                          ]);
    
            $producerQuery = clone $query;
    
            $producers = ArrayHelper::map(( new Query() )->select('item.firm, COUNT(*) as count')
                                                         ->from(Item::tableName() . ' item')
                                                         ->where([ 'item.id' => $producerQuery->select('item.id')
                                                                                              ->groupBy(null) ])
                                                         ->groupBy('item.firm')
                                                         ->all(), 'firm', 'count');
    
            unset($producerQuery);
    
            if (!empty($params['producers'])) {
                $param = explode(',', Html::encode($params['producers']));
                $query->andWhere([ 'in', 'firm', $param ]);
            }
            
            $pages = new Pagination([
                                        'totalCount' => $query->count(),
                                        'pageSize' => 18,
                                        'defaultPageSize' => 18,
                                        'forcePageParam' => false,
                                    ]
            );
    
            $items = $query->offset($pages->offset)
                           ->limit($pages->limit)
                           ->asArray()
                           ->all();
    
    
            // all items, order by rate descending
            
            return $this->render('catalog', [
                'parents' => [],
                'current' => [ 'name' => 'Каталог' ],
                'items' => $items,
                'pages' => $pages,
                'producers' => $producers,
            ]);
        } elseif ($current = Category::findOne([ 'id' => ValueHelper::decryptValue($slug), 'active' => Status::STATUS_ACTIVE ])) {
            if ($current['rgt'] != $current['lft'] + 1) {
                return $this->redirect([ 'category', 'slug' => ValueHelper::encryptValue($current['id']) ]);
            } else {
    
                $query = Item::find()
                             ->select([ 'item.*', 'MIN(sizes.sell_price) as min_price' ])
                             ->from(Item::tableName() . ' item')
                             ->joinWith([ 'allColors colors' => function ($query)
                             {
                                 $query->joinWith([ 'allSizes sizes', 'mainImage' ]);
                             }, 'description',
                                            'category' => function ($query)
                                            {
                                                $query->andWhere([ 'active' => Status::STATUS_ACTIVE ]);
                                            } ])
                             ->with([ 'promotion' => function ($query)
                             {
                                 $query->andWhere([ 'status' => Status::STATUS_ACTIVE ]);
                             } ])
                             ->where([
                                         'item.category_id' => $current['id'],
                                         'item.status' => Status::STATUS_ACTIVE,
                                         'colors.status' => Status::STATUS_ACTIVE,
                                         'sizes.status' => Status::STATUS_ACTIVE,
                                     ])
                             ->groupBy([ 'id' ])
                             ->orderBy([ 'rate' => SORT_DESC ]);
    
                $producerQuery = clone $query;
    
                $producers = ArrayHelper::map(( new Query() )->select('item.firm, COUNT(*) as count')
                                                             ->from(Item::tableName() . ' item')
                                                             ->where([ 'item.id' => $producerQuery->select('item.id')
                                                                                                  ->groupBy(null) ])
                                                             ->groupBy('item.firm')
                                                             ->all(), 'firm', 'count');
    
                unset($producerQuery);
    
                if (!empty($params['producers'])) {
                    $param = explode(',', Html::encode($params['producers']));
                    $query->andWhere([ 'in', 'firm', $param ]);
                }
                
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
                    'parents' => $current->parents()->all(),
                    'current' => $current,
                    'pages' => $pages,
                    'producers' => $producers,
                ]);
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
                            $query->andWhere([ 'colors.status' => Status::STATUS_ACTIVE ])
                                  ->joinWith([ 'allSizes sizes' => function ($query)
                                  {
                                      $query->andWhere([ 'sizes.status' => Status::STATUS_ACTIVE ])
                                            ->orderBy([
                                                          'CONVERT(sizes.size, UNSIGNED)' => SORT_ASC,
                                                          'sizes.id' => SORT_ASC,
                                                      ]);
                                  }, 'allImages images' ]);
                        }, 'description',
                                       'category' => function ($query)
                                       {
                                           $query->andWhere([ 'active' => Status::STATUS_ACTIVE ]);
                                       } ])
                        ->with([ 'promotion' => function ($query)
                        {
                            $query->andWhere([ 'status' => Status::STATUS_ACTIVE ]);
                        } ])
                        ->where([
                                    'images.type' => Image::TYPE_ITEM,
                                    'item.id' => ValueHelper::decryptValue($slug),
                                    'item.status' => Status::STATUS_ACTIVE,
                                ])
                        ->asArray()
                        ->one();
    
            if (!empty($item)) {
    
                $current = $item['category'];
    
                $parents = ArrayHelper::toArray(array_merge(Category::findOne([ 'id' => $current['id'] ])
                                                                    ->parents()
                                                                    ->all(), [ $current ]));
                
                unset($current);
    
    
                // make seo-title
                $title = 'Купить ';
                
                foreach ($parents as $i => $parent) {
                    if ($i) {
                        $title .= "{$parent['name']} ";
                    }
                }
                
                $title .= $item['firm'] . ' ' . $item['model'] . ' ';
                
                foreach ($item['allColors'] as $color) {
                    $title .= $color['color'] . ' ' . $color['code'] . ' ';
                }
                
                $description = $title;
                
                foreach (explode(ItemDescription::ITEMS_SEPARATOR, $item['description']['small_list']) as $d) {
                    $description .= "{$d} ";
                }
                
                $title .= '| Киев | Цены' . ( !empty($item['promotion']) ? ' | Скидки' : '' );
                $description .= '| Киев | Цены' . ( !empty($item['promotion']) ? ' | Скидки' : '' );
                
                SeoHelper::putDefaultTags([
                                              'title' => $title,
                                              'description' => $description,
                                          ]);
    
                $relativeItems = Item::find()
                                     ->select([ 'item.*', 'MIN(sizes.sell_price) as min_price' ])
                                     ->from(Item::tableName() . ' item')
                    ->joinWith([ 'allColors colors' => function ($query)
                                     {
                                         $query->joinWith([ 'allSizes sizes', 'mainImage' ]);
                                     }, 'description',
                                                    'category' => function ($query)
                                                    {
                                                        $query->andWhere([ 'active' => Status::STATUS_ACTIVE ]);
                                                    } ])
                    ->with([ 'promotion' => function ($query)
                                     {
                                         $query->andWhere([ 'status' => Status::STATUS_ACTIVE ]);
                                     } ])
                    ->where([
                                                 'item.status' => Status::STATUS_ACTIVE,
                                                 'colors.status' => Status::STATUS_ACTIVE,
                                                 'sizes.status' => Status::STATUS_ACTIVE,
                                             ])
                    ->andWhere('sizes.quantity > 0 AND
                                     ((item.category_id = :category_id AND
                    item.firm = :firm) OR
                    item.category_id = :category_id)', [
                                         'category_id' => $item['category_id'],
                                         'firm' => $item['firm'],
                                     ])
                    ->andWhere('item.id != :id', [
                                         'id' => $item['id'],
                                     ])
                    ->groupBy([ 'id' ])
                    ->orderBy([ 'rate' => SORT_DESC ])
                    ->asArray()
                    ->limit(12)
                    ->all();
                
                // create OpenGraph meta-tags
                
                unset($title);
                unset($description);
                unset($parent);
                unset($i);
                unset($d);
                
                return $this->render('product', [
                    'item' => $item,
                    'relativeItems' => $relativeItems,
                    'parents' => $parents,
                ]);
            }
            
        }
        
        throw new NotFoundHttpException('Страница не найдена');
    }
}
