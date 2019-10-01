<?php

namespace app\modules\main\controllers;

use app\components\helpers\SeoHelper;
use app\components\helpers\ValueHelper;
use app\components\models\NovaPoshta;
use app\components\models\Status;
use app\models\ItemColorSize;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;

/**
 * Controller for work with cart
 */
class CartController
    extends Controller
{
    
    public function beforeAction($action)
    {
        SeoHelper::putOpenGraphTags([
                                        'og:site_name' => 'Интернет-магазин Aquista',
                                    ]);
        
        return parent::beforeAction($action);
    }
    
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
     * Processing all slugs
     *
     * Renders the index view for the module
     * @return string
     */
    public function actionAddToCart()
    {
        if (Yii::$app->request->isPost and Yii::$app->request->isAjax) {
            $result = [];
            $cart = Yii::$app->cart;
            
            if ($post = Yii::$app->request->post() and array_key_exists('color', $post) and array_key_exists('size',
                                                                                                             $post) and array_key_exists('quantity',
                                                                                                                                         $post)) {
                $result['success'] = false;
                $color = ValueHelper::decryptValue(Html::encode($post['color']));
                $size = ValueHelper::decryptValue(Html::encode($post['size']));
                $quantity = Html::encode($post['quantity']);
                
                $item = ItemColorSize::find()
                    ->from(ItemColorSize::tableName() . ' size')
                    ->joinWith([ 'color color' => function ($query)
                    {
                        $query->joinWith([ 'item item' => function ($query)
                        {
                            $query->with([ 'promotion' => function ($query)
                            {
                                $query->andWhere([ 'status' => Status::STATUS_ACTIVE ]);
                            }, 'category' => function ($query)
                            {
                                $query->andWhere([ 'active' => Status::STATUS_ACTIVE ]);
                            } ]);
                        }, 'mainImage image' ]);
                    } ])
                    ->where([
                                'color.id' => $color,
                                'size.id' => $size,
                                'size.status' => Status::STATUS_ACTIVE,
                                'color.status' => Status::STATUS_ACTIVE,
                                'item.status' => Status::STATUS_ACTIVE,
                            ])
                    ->andWhere([ '>=', 'size.quantity', $quantity ])
                    ->asArray()
                    ->one();
                
                if (!empty($item)) {
                    // check if this item already exists
                    $product = ItemColorSize::findOne([ 'id' => $size ]);
                    $cart_item = $cart->getItem($product->id);
                    
                    if (empty($cart_item)) {
                        $cart->add($product, $quantity);
                        $result['success'] = true;
                    } else {
                        if (( $cart_item->getQuantity() + $quantity ) <= $item['quantity']) {
                            $cart->plus($product->id, $quantity);
                            $result['success'] = true;
                        }
                    }
                }
            }
            unset($item);
            
            return $this->asJson($result);
        }
        
        throw new MethodNotAllowedHttpException('Only POST method allowed');
    }
    
    public function actionRemoveItem()
    {
        if (Yii::$app->request->isPost and Yii::$app->request->isAjax) {
            $result['success'] = false;
            $cart = Yii::$app->cart;
            
            if ($post = Yii::$app->request->post() and array_key_exists('product', $post)) {
                $id = ValueHelper::decryptValue(Html::encode($post['product']));
                
                if ($cart->getItem($id)) {
                    $cart->remove($id);
                    $result['totalCost'] = ValueHelper::addCurrency($cart->getTotalCost());
                    $result['success'] = true;
                }
            }
            
            return $this->asJson($result);
        }
        
        throw new MethodNotAllowedHttpException('Only POST method allowed');
    }
    
    public function actionClearCart()
    {
        // todo-cache: add cache on 5-10 sec
        if (Yii::$app->request->isPost and Yii::$app->request->isAjax) {
            $cart = Yii::$app->cart;
            $result['success'] = false;
            
            try {
                if (!empty($cart->getItems())) {
                    $cart->clear();
                    $result['extra']['totalCost'] = ValueHelper::addCurrency($cart->getTotalCost());
                    $result['success'] = true;
                }
            } catch (Exception $exception) {
                Yii::$app->errorHandler->logException($exception);
            }
            
            return $this->asJson($result);
        }
        
        throw new MethodNotAllowedHttpException('Only POST method allowed');
    }
    
    public function actionCart()
    {
        if (Yii::$app->request->isAjax) {
            
            $cart = Yii::$app->cart;
            
            $result['totalCount'] = $cart->getTotalCount();
            $result['totalCost'] = ValueHelper::addCurrency($cart->getTotalCost());
            $result['totalCostWithoutPromotion'] = ValueHelper::addCurrency($cart->getTotalCost(false, false));
            
            $tmp = [];
            
            foreach ($cart->getItems() as $index => $item) {
                
                $size = $item->getProduct();
                $tmp[$index]['size'] = ArrayHelper::toArray($size);
                $tmp[$index]['color'] = ArrayHelper::toArray($size->color);
                $tmp[$index]['image'] = ArrayHelper::toArray($size->color->mainImage);
                $tmp[$index]['item'] = ArrayHelper::toArray($size->color->item);
                $tmp[$index]['promotion'] = !empty($size->promotion) ? ArrayHelper::toArray($size->promotion) : null;
                $tmp[$index]['quantity'] = $item->getQuantity();
                $tmp[$index]['price'] = ValueHelper::addCurrency($item->getPrice());
                if (!empty($tmp[$index]['promotion'])) {
                    $tmp[$index]['priceWithoutPromotion'] = ValueHelper::addCurrency($item->getPrice(false));
                }
            }
            
            $result['items'] = $tmp;
            
            unset($tmp);
            unset($index);
            unset($item);
            
            $data['cart'] = $this->view->render('_cart', [
                'result' => $result,
            ], $this);
            
            $data['delivery'] = ValueHelper::getDelivery($cart->getTotalCost());
            $data['totalCost'] = $result['totalCost'];
            $data['totalCostWithoutPromotion'] = $result['totalCost'] != $result['totalCostWithoutPromotion'] ? $result['totalCostWithoutPromotion'] : null;
            unset($result);
            
            return $this->asJson($data);
            
        }
        
        throw new MethodNotAllowedHttpException('Only AJAX allowed');
    }
    
    public function actionIndex()
    {
        $cart = Yii::$app->cart;
        
        foreach ($cart->getItems() as $index => $item) {
            $size = $item->getProduct();
            $items[$index]['size'] = ArrayHelper::toArray($size);
            $items[$index]['color'] = ArrayHelper::toArray($size->color);
            $items[$index]['image'] = ArrayHelper::toArray($size->color->mainImage);
            $items[$index]['item'] = ArrayHelper::toArray($size->color->item);
            $items[$index]['promotion'] = !empty($size->promotion) ? ArrayHelper::toArray($size->promotion) : null;
            $items[$index]['quantity'] = $item->getQuantity();
            $items[$index]['price'] = ValueHelper::addCurrency($item->getPrice());
            $items[$index]['cost'] = ValueHelper::addCurrency($item->getCost());
            
            if (!empty($items[$index]['promotion'])) {
                $items[$index]['priceWithoutPromotion'] = ValueHelper::addCurrency($item->getPrice(false));
                $items[$index]['costWithoutPromotion'] = ValueHelper::addCurrency($item->getCost(false, false));
            }
        }
        
        $totalCost = ValueHelper::addCurrency($cart->getTotalCost());
        
        
        return $this->render('index', [
            'items' => !empty($items) ? $items : [],
            'totalCost' => $totalCost,
            'totalCostWithoutPromotion' => $totalCost != ValueHelper::addCurrency($cart->getTotalCost(false,
                                                                                                      false)) ? ValueHelper::addCurrency($cart->getTotalCost(false,
                                                                                                                                                             false)) : null,
        ]);
    }
    
    public function actionChangeQuantity()
    {
        if (Yii::$app->request->isPost and Yii::$app->request->isAjax) {
            $result['success'] = false;
            $cart = Yii::$app->cart;
            
            if ($post = Yii::$app->request->post() and array_key_exists('product',
                                                                        $post) and array_key_exists('quantity',
                                                                                                    $post)) {
                
                $id = ValueHelper::decryptValue((int) Html::encode($post['product']));
                $quantity = (int) Html::encode($post['quantity']);
                
                $item = $cart->getItem($id);
                
                if (!empty($item) and $item->getQuantity() != $quantity) {
                    $cart->change($id, $quantity);
                    
                    $result['extra']['id'] = ValueHelper::encryptValue($id);
                    $result['extra']['cost'] = ValueHelper::addCurrency($item->getCost());
                    $result['extra']['costWithoutPromotion'] = ( $result['extra']['cost'] != ValueHelper::addCurrency($item->getCost(false,
                                                                                                                                     false)) ) ? ValueHelper::addCurrency($item->getCost(false,
                                                                                                                                                                                         false)) : null;
                    //                    $result['extra']['totalCost'] = ValueHelper::addCurrency($cart->getTotalCost());
                    //                    $result['extra']['totalCostWithoutPromotion'] = ValueHelper::addCurrency($cart->getTotalCost(false,
                    //                                                                                                                 false));
                    $result['success'] = true;
                }
                
            }
            
            return $this->asJson($result);
            
        }
        
        throw new MethodNotAllowedHttpException('Only AJAX allowed');
    }
}
