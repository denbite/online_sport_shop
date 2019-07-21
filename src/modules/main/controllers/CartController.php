<?php

namespace app\modules\main\controllers;

use app\components\helpers\ValueHelper;
use app\components\models\Status;
use app\models\Image;
use app\models\ItemColorSize;
use Yii;
use yii\db\Exception;
use yii\helpers\Url;
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
                $color = ValueHelper::decryptValue($post['color']);
                $size = ValueHelper::decryptValue($post['size']);
                $quantity = $post['quantity'];
                
                $result = ItemColorSize::find()
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
                
                if (!empty($result)) {
                    // check if this item already exists
                    $product = ItemColorSize::findOne([ 'id' => $size ]);
                    if (empty($cart->getItem($product->id))) {
                        $cart->add($product, $quantity);
                        $result['extra']['image_src'] = Image::getLink($result['color']['mainImage']['id'],
                            Image::SIZE_90x90);
                        $result['extra']['image_alt'] = $result['color']['mainImage']['url'];
                        $result['extra']['link'] = Url::to([ '/main/products/product', 'slug' => ValueHelper::encryptValue($result['color']['item']['id']) ]);
                        $result['extra']['title'] = $result['color']['item']['model'] . ' ' . $result['size'];
                        $result['extra']['new'] = true;
                    } else {
                        $cart->plus($product->id, $quantity);
                        $result['extra']['new'] = false;
                    }
    
                    if ($result['quantity'] >= $cart->getItem($product->id)->getQuantity()) {
                        $result['success'] = true;
        
                    } else {
                        $cart->change($product->id, $result['quantity']);
                        $result['success'] = false;
                    }
    
                    $result['extra']['sum'] = $cart->getItem($product->id)
                                                   ->getQuantity() . ' x ' . ValueHelper::formatPrice($result['price']);
                    $result['id'] = ValueHelper::encryptValue($result['id']);
                } else {
                    $result['success'] = false;
                }
    
                $result['extra']['totalCount'] = $cart->getTotalCount();
                $result['extra']['totalCost'] = ValueHelper::formatPrice($cart->getTotalCost());
            }
    
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
                    $result['totalCost'] = ValueHelper::formatPrice($cart->getTotalCost());
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
}
