<?php

namespace app\models;

/**
 * This is the model class for table "order".
 *
 * @property int    $id
 * @property int    $user_id
 * @property string $invoice
 * @property int    $status
 * @property int    $delivery
 * @property int    $issue_date
 */
class Order
    extends \yii\db\ActiveRecord
{
    
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
            [ [ 'user_id', 'delivery', 'issue_date' ], 'required' ],
            [ [ 'user_id', 'status', 'delivery', 'issue_date' ], 'integer' ],
            [ [ 'invoice' ], 'string', 'max' => 32 ],
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
            'invoice' => 'Invoice',
            'status' => 'Status',
            'delivery' => 'Delivery',
            'issue_date' => 'Issue Date',
        ];
    }
}
