<?php

namespace app\modules\admin\models;

use app\models\Banner;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BannerSearch represents the model behind the search form of `app\models\Banner`.
 */
class BannerSearch
    extends Banner
{
    
    public $date_to;
    
    public $date_from;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'id', 'status', 'publish_from', 'publish_to', 'updated_at', 'created_at' ], 'integer' ],
            [ [ 'title', 'description', 'link', 'link_title' ], 'safe' ],
            [ [ 'date_from', 'date_to' ], 'date', 'format' => 'php:d-m-Y' ],
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
        $query = Banner::find()->orderBy([ 'publish_from' => SORT_DESC ]);
        
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
            'status' => $this->status,
            'publish_from' => $this->publish_from,
            'publish_to' => $this->publish_to,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);
        
        $query->andFilterWhere([ 'like', 'title', $this->title ])
              ->andFilterWhere([ 'like', 'description', $this->description ])
              ->andFilterWhere([ 'like', 'link', $this->link ])
              ->andFilterWhere([ 'like', 'link_title', $this->link_title ]);
        
        $query->andFilterWhere(
            [
                'or',
                [ 'or',
                    [ 'and', [ '>=', 'publish_to', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null ], [ '<=', 'publish_from', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null ] ],
                    [ 'and', [ '>=', 'publish_to', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null ], [ '<=', 'publish_from', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null ] ],
                ],
                [ 'or',
                    [ 'and', [ '>=', 'publish_to', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null ], [ '<=', 'publish_to', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null ] ],
                    [ 'and', [ '>=', 'publish_from', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null ], [ '<=', 'publish_from', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null ] ],
                ],
            ]
        );
        
        return $dataProvider;
    }
}
