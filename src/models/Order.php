<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "order".
 *
 * @property int    $id
 * @property int    $user_id
 * @property string $invoice
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $city
 * @property string $department
 * @property int    $status
 * @property int    $delivery
 * @property int    $sum
 * @property int    $buy_sum
 * @property int    $phone_status
 * @property string $comment
 * @property int    $updated_at
 * @property int    $created_at
 */
class Order
    extends \yii\db\ActiveRecord
{
    
    const ORDER_STATUS_NEW = 1;
    
    const ORDER_STATUS_CONFIRMED = 2;
    
    const ORDER_STATUS_DELIVERY = 3;
    
    const ORDER_STATUS_COMPLETED = 4;
    
    const ORDER_STATUS_CANCELED = 5;
    
    const PHONE_STATUS_WAITING = 1;
    
    const PHONE_STATUS_NOT_DISTURB = 2;
    
    const PHONE_STATUS_IGNORE = 3;
    
    const PHONE_STATUS_CANCEL = 4;
    
    const PHONE_STATUS_CALLBACK = 5;
    
    const ORDER_DELIVERY_NOVAPOSHTA = 1;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'user_id', 'delivery' ], 'required' ],
            [ [ 'user_id', 'status', 'delivery', 'sum', 'buy_sum', 'phone_status' ], 'integer' ],
            [ [ 'invoice', ], 'string', 'max' => 32 ],
            [ [ 'name', 'phone', 'email', 'city', 'department' ], 'string' ],
        ];
    }
    
    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            // Возможно добавить статус, когда товар передан в службу доставки
            self::ORDER_STATUS_NEW => 'Новый',
            self::ORDER_STATUS_CONFIRMED => 'Обработан',
            self::ORDER_STATUS_DELIVERY => 'Передан в службу доставки',
            self::ORDER_STATUS_COMPLETED => 'Выполнен',
            self::ORDER_STATUS_CANCELED => 'Отменен',
        ];
    }
    
    /**
     * @return array
     */
    public static function getStatusCssClass()
    {
        return [
            self::ORDER_STATUS_NEW => 'green',
            self::ORDER_STATUS_CONFIRMED => 'info',
            self::ORDER_STATUS_DELIVERY => 'primary',
            self::ORDER_STATUS_COMPLETED => 'success',
            self::ORDER_STATUS_CANCELED => 'danger',
        ];
    }
    
    /**
     * @return array
     */
    public static function getPhoneStatuses()
    {
        return [
            self::PHONE_STATUS_WAITING => 'Ждет звонка',
            self::PHONE_STATUS_NOT_DISTURB => 'Не звонить',
            self::PHONE_STATUS_IGNORE => 'Не взял',
            self::PHONE_STATUS_CANCEL => 'Отбился',
            self::PHONE_STATUS_CALLBACK => 'Нужно перезвонить',
        ];
    }
    
    /**
     * @return array
     */
    public static function getPhoneStatusCssClass()
    {
        return [
            self::PHONE_STATUS_WAITING => 'info',
            self::PHONE_STATUS_NOT_DISTURB => 'yellow',
            self::PHONE_STATUS_IGNORE => 'secondary',
            self::PHONE_STATUS_CANCEL => 'danger',
            self::PHONE_STATUS_CALLBACK => 'brown',
        ];
    }
    
    /**
     * @return array
     */
    public static function getDeliveries()
    {
        return [
            self::ORDER_DELIVERY_NOVAPOSHTA => [
                'name' => 'Новая почта',
                'description' => 'Оплата происходит по номеру накладной, которая создается на вас при оформлении заказа.',
            ],
        ];
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'phone_status' => 'Статус телефона',
            'city' => 'Город',
            'department' => 'Отделение НП',
            'invoice' => 'Номер накладной',
            'sum' => 'Сумма заказа',
            'buy_sum' => 'Закупочная сумма',
            'status' => 'Статус',
            'delivery' => 'Тип доставки',
            'comment' => 'Комментарий к заказу',
            'updated_at' => 'Дата обновления',
            'created_at' => 'Дата создания',
        ];
    }
}
