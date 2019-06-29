<?php

namespace app\modules\admin\models;

use app\modules\user\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form of `app\modules\user\models\User`.
 */
class UserSearch extends User
{
    
    public $role;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'status' ], 'integer', ],
            [ [ 'role', 'username', 'email' ], 'string', ],
            [ [ 'status' ], 'in', 'range' => array_keys(User::getStatusesArray()) ],
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
        $query = User::find();
        
        // add conditions that should always apply here
        
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'forcePageParam' => false,
                    'pageSizeParam' => false,
                    'pageSize' => 20,
                ],
            ]
        );
        
        $this->load($params);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        // grid filtering conditions
        //        $query->andFilterWhere([
        //                                   'id' => $this->id,
        //                                   'autocenter_id' => $this->autocenter_id,
        //                                   'lang_id' => $this->lang_id,
        //                                   'type' => $this->type,
        //                                   'status' => $this->status,
        //                                   'updated_at' => $this->updated_at,
        //                                   'created_at' => $this->created_at,
        //                               ]);
        //
        //        $query->andFilterWhere([ 'like', 'name', $this->name ])
        //              ->andFilterWhere([ 'like', 'address', $this->address ])
        //              ->andFilterWhere([ 'like', 'phone', $this->phone ])
        //              ->andFilterWhere([ 'like', 'time', $this->time ])
        //              ->andFilterWhere([ 'like', 'email', $this->email ]);
        
        $query->andFilterWhere([
                                   'status' => $this->status,
                               ]);
        
        if (!empty($this->role)) {
            $role = Yii::$app->authManager->getUserIdsByRole($this->role);
            
            $query->andFilterWhere([
                                       'in', 'id', !empty($role) ? $role : 0,
                                   ]);
        }
        
        $query->andFilterWhere([
                                   'like', 'email', $this->email,
                               ])
              ->andFilterWhere([
                                   'like', 'username', $this->username,
                               ]);
        
        return $dataProvider;
    }
}

