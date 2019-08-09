<?php

namespace app\modules\admin\models;

use app\models\Order;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form of `app\models\Order`.
 */
class OrderSearch
    extends Order
{
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'id', 'user_id', 'sum', 'buy_sum', 'status', 'delivery', 'phone_status', 'updated_at', 'created_at' ], 'integer' ],
            [ [ 'name', 'phone', 'email', 'city', 'department', 'invoice', 'comment' ], 'safe' ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Order::find()->orderBy([ 'created_at' => SORT_DESC ]);
        
        // add conditions that should always apply here
        
        $dataProvider = new ActiveDataProvider([
                                                   'query' => $query,
                                               ]);
        
        $this->load($params);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        // grid filtering conditions
        $query->andFilterWhere([
                                   'id' => $this->id,
                                   'user_id' => $this->user_id,
                                   'sum' => $this->sum,
                                   'buy_sum' => $this->buy_sum,
                                   'status' => $this->status,
                                   'delivery' => $this->delivery,
                                   'phone_status' => $this->phone_status,
                                   'updated_at' => $this->updated_at,
                                   'created_at' => $this->created_at,
                               ]);
        
        $query->andFilterWhere([ 'like', 'name', $this->name ])
              ->andFilterWhere([ 'like', 'phone', $this->phone ])
              ->andFilterWhere([ 'like', 'email', $this->email ])
              ->andFilterWhere([ 'like', 'city', $this->city ])
              ->andFilterWhere([ 'like', 'department', $this->department ])
              ->andFilterWhere([ 'like', 'invoice', $this->invoice ])
              ->andFilterWhere([ 'like', 'comment', $this->comment ]);
        
        return $dataProvider;
    }
}
