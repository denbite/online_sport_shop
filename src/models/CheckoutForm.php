<?php

namespace app\models;

use app\modules\user\models\forms\SignupForm;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\helpers\Html;

/**
 * This is the model class for table "item".
 *
 * @property SignupForm $signup
 * @property bool       $booleanSignup
 * @property string     $city
 * @property string     $department
 * @property bool       $callBack
 * @property string     $comment
 */
class CheckoutForm
    extends Model
{
    
    public $signup;
    
    public $booleanSignup = false;
    
    public $city;
    
    public $department;
    
    public $callBack = true;
    
    public $comment;
    
    public function rules()
    {
        return [
            [ [ 'city', 'department' ], 'required', 'message' => 'Данное поле не может быть пустым' ],
            [ [ 'city', 'department', 'comment' ], 'string' ],
    
            [ [ 'booleanSignup', 'callBack' ], 'boolean' ],
            
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
            'callBack' => 'Перезвоните мне',
            'comment' => 'Комментарий',
        ];
    }
    
    public function registerOrder()
    {
        if ($this->validate() and ( empty($this->signup) or $this->signup->validate() )) {
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
            $order->buy_sum = $cart->getTotalCost(); // write function that calculate buy_sum without round
            $order->delivery = 1; // create table with delivery types
            $order->status = Order::ORDER_STATUS_NEW;
            $order->phone_status = !$this->callBack ? Order::PHONE_STATUS_NOT_DISTURB : Order::ORDER_STATUS_NEW;
            $order->comment = $this->comment;
            
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