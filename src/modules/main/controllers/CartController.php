<?php

namespace app\modules\main\controllers;

use app\components\helpers\ValueHelper;
use app\components\models\Status;
use app\models\ItemColorSize;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;

/**
 * Controller with only AJAX actions
 */
class CartController
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
                    $post) and array_key_exists('quantity', $post)) {
                $result['success'] = false;
                $color = ValueHelper::decryptValue($post['color']);
                $size = ValueHelper::decryptValue($post['size']);
                $quantity = $post['quantity'];
    
                $item = ItemColorSize::find()
                                     ->from(ItemColorSize::tableName() . ' size')
                                     ->joinWith([ 'color color' => function ($query)
                                       {
                                           $query->joinWith([ 'item item' => function ($query)
                                           {
                                               $query->with([ 'promotion' => function ($query)
                                               {
                                                   $query->andWhere([ 'status' => Status::STATUS_ACTIVE ]);
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
                    if (empty($cart->getItem($product->id))) {
                        $cart->add($product, $quantity);
                    } else {
                        $cart->plus($product->id, $quantity);
                    }
                    $result['success'] = true;
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
                $id = ValueHelper::decryptValue($post['product']);
                
                if ($cart->getItem($id)) {
                    $cart->remove($id);
                    $result['totalCount'] = $cart->getTotalCount();
                    $result['totalCost'] = ValueHelper::outPrice($cart->getTotalCost());
                    $result['id'] = ValueHelper::encryptValue($id);
                    $result['success'] = true;
                }
            }
    
            return $this->asJson($result);
        }
        
        throw new MethodNotAllowedHttpException('Only POST method allowed');
    }
    
    public function actionClearCart()
    {
        if (Yii::$app->request->isPost and Yii::$app->request->isAjax) {
            
            $result['success'] = false;
            
            try {
                Yii::$app->cart->clear();
                $result['success'] = true;
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
            $result['totalCost'] = ValueHelper::outPrice($cart->getTotalCost());
            
            $tmp = [];
            
            foreach ($cart->getItems() as $index => $item) {
    
                $size = $item->getProduct();
                $tmp[$index]['size'] = ArrayHelper::toArray($size);
                $tmp[$index]['color'] = ArrayHelper::toArray($size->color);
                $tmp[$index]['image'] = ArrayHelper::toArray($size->color->mainImage);
                $tmp[$index]['item'] = ArrayHelper::toArray($size->color->item);
                $tmp[$index]['promotion'] = ArrayHelper::toArray($size->promotion);
                $tmp[$index]['quantity'] = $item->getQuantity();
                $tmp[$index]['price'] = $item->getPrice();
            }
            
            $result['items'] = $tmp;
            
            unset($tmp);
            unset($index);
            
            return $this->view->render('_cart', [
                'result' => $result,
            ], $this);
            
        }
        
        throw new MethodNotAllowedHttpException('Only AJAX allowed');
    }
}
