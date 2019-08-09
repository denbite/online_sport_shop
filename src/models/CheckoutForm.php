<?php

namespace app\models;

use app\modules\user\models\forms\SignupForm;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\helpers\Html;

class CheckoutForm
    extends Model
{
    
    public $signup;
    
    public $booleanSignup = false;
    
    public $city;
    
    public $department;
    
    public function rules()
    {
        return [
            [ [ 'city', 'department' ], 'required', 'message' => 'Данное поле не может быть пустым' ],
            [ [ 'city', 'department' ], 'string', 'skipOnEmpty' => false ],
            
            [ 'booleanSignup', 'boolean' ],
            
            [ 'signup', 'safe' ],
        ];
    }
    
    public function init()
    {
        if (Yii::$app->user->isGuest) {
            $this->signup = new SignupForm();
        }
    }
    
    public function attributeLabels()
    {
        return [
            'booleanSignup' => 'Создать аккаунт?',
            'city' => 'Город',
            'department' => 'Отделение',
        ];
    }
    
    public function registerOrder()
    {
        
        if ($this->validate() and $this->signup->validate()) {
            if ($this->booleanSignup) {
                if (!$this->signup->signup()) {
                    throw new Exception('Не удалось зарегистрировать данного пользователя');
                }
            }
            
            $user = Yii::$app->user->identity;
            
            $cart = Yii::$app->cart;
            
            $order = new Order();
            
            $order->user_id = !empty($user) ? $user['id'] : 0;
            $order->name = !empty($user) ? $user['name'] : $this->signup->name . ' ' . $this->signup->surname;
            $order->phone = !empty($user) ? $user['phone'] : $this->signup->phone;
            $order->email = !empty($user) ? $user['email'] : $this->signup->email;
            $order->city = $this->city;
            $order->department = $this->department;
            $order->sum = $cart->getTotalCost();
            $order->buy_sum = $cart->getTotalCost();
            $order->delivery = 1; // create table with delivery types
            $order->status = Order::ORDER_STATUS_NEW;
            
            if (!$order->save()) {
                throw new Exception('Не удалось сохранить заказ');
            }
            
            $this->saveOrderedItems($order->id);
            
        }
    }
    
    public function saveOrderedItems($order_id)
    {
        $cart = Yii::$app->cart;
        
        $items = $cart->getItems();
        
        if (!empty($items) and is_array($items)) {
            foreach ($items as $item) {
                $model = new OrderSize();
                
                $model->order_id = Html::encode($order_id);
                $model->size_id = $item->getProduct()->id;
                $model->quantity = $item->getQuantity();
                $model->cost = $item->getCost();
                
                if (!$model->save()) {
                    throw new Exception('Не удалось сохранить товары для данного заказа');
                }
                
                unset($model);
            }
        }
    }
}