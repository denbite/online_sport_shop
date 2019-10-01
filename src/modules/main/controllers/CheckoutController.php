<?php

namespace app\modules\main\controllers;

use app\components\helpers\SeoHelper;
use app\components\helpers\TransactionHelper;
use app\components\helpers\ValueHelper;
use app\components\models\NovaPoshta;
use app\models\CheckoutForm;
use app\modules\user\models\forms\LoginForm;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;

/**
 * Controller for work with cart
 */
class CheckoutController
    extends Controller
{
    
    public function beforeAction($action)
    {
        SeoHelper::putOpenGraphTags([
                                        'og:site_name' => 'Интернет-магазин Aquista',
                                        'og:image' => Yii::$app->params['host'] . '/images/logo-admin-icon.png',
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
    
    
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            $loginForm = new LoginForm();
            
            if (Yii::$app->request->isPost and $loginForm->load(Yii::$app->request->post()) and $loginForm->login()) {
                return $this->refresh();
            }
        }
        
        $checkoutForm = new CheckoutForm();
        
        $cart = Yii::$app->cart;
        
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            if ($checkoutForm->load($post) and $checkoutForm->validate()) {
                $order = [];
                
                try {
                    if (!empty($checkoutForm->signup)) {
                        $checkoutForm->signup->setBooleanSignup($checkoutForm->booleanSignup);
                        $checkoutForm->signup->load($post);
                        $checkoutForm->signup->validate();
                    }
                    
                    
                    TransactionHelper::wrap(function () use ($checkoutForm, &$order)
                    {
                        $order = $checkoutForm->registerOrder();
                    });
                    
                } catch (\Exception $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
                
                if (!empty($order)) {
                    
                    Yii::$app->cart->clear();
                    
                    return $this->render('thanks', [
                        'order' => $order,
                    ]);
                    
                }
                
            }
            
        }
        
        foreach ($cart->getItems() as $index => $item) {
            $size = $item->getProduct();
            $items[$index]['name'] = $size->color->item->firm . ' ' . $size->color->item->model . ' ' . $size->size;
            $items[$index]['quantity'] = $item->getQuantity();
            $items[$index]['cost'] = ValueHelper::addCurrency($item->getCost());
        }
        
        return $this->render('checkout', [
            'items' => !empty($items) ? $items : [],
            'totalCost' => ValueHelper::addCurrency($cart->getTotalCost()),
            'totalCostWithoutPromotion' => ValueHelper::addCurrency($cart->getTotalCost()) != ValueHelper::addCurrency($cart->getTotalCost(false,
                                                                                                                                           false)) ? ValueHelper::addCurrency($cart->getTotalCost(false,
                                                                                                                                                                                                  false)) : null,
            'delivery' => ValueHelper::getDelivery($cart->getTotalCost()),
            'loginForm' => !empty($loginForm) ? $loginForm : null,
            'checkoutForm' => !empty($checkoutForm) ? $checkoutForm : null,
            'np' => Yii::$app->novaposhta,
        ]);
    }
    
    public function actionGetDepartment()
    {
        // todo-cache: add cache by query on 5 mins
        if (Yii::$app->request->isPost and Yii::$app->request->isAjax) {
            if ($post = Yii::$app->request->post() and array_key_exists('city_ref', $post)) {
                $city_ref = Html::encode($post['city_ref']);
                
                $result = [];
                
                foreach (Yii::$app->novaposhta->getWarehouses($city_ref) as $city) {
                    $result[$city['Ref']] = $city['DescriptionRu'];
                }
                
                return $this->asJson($result);
            }
        }
        
        throw new MethodNotAllowedHttpException('Only AJAX queries allowed');
    }
    
    public function actionGetCity($q = null)
    {
        // todo-cache: add cache by query on 5 mins
        if (Yii::$app->request->isGet and Yii::$app->request->isAjax) {
            $city_name = Html::encode($q);
            
            if (!is_null($city_name)) {
                
                $out = [];
                
                foreach (Yii::$app->novaposhta->getCitiesArray($city_name) as $ref => $city) {
                    $out['results'][] = [ 'id' => $ref, 'text' => $city ];
                }
                
                return $this->asJson($out);
            }
            
            throw new InvalidParamException('Invalid param giver');
        }
        
        throw new MethodNotAllowedHttpException('Only AJAX queries allowed');
    }
    
}
