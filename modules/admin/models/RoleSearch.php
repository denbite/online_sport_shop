<?php

namespace app\modules\admin\models;

use app\models\AuthItem;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ItemSearch represents the model behind the search form of `app\models\Item`.
 */
class RoleSearch
    extends AuthItem
{
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'type', 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'name', 'description' ], 'string' ],
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
        $query = AuthItem::find()->where([ 'type' => 1 ]);
        
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
        
        $query->filterWhere([ 'like', 'name', $this->name ])
              ->andFilterWhere([ 'like', 'description', $this->description ]);
        
        return $dataProvider;
    }
}
