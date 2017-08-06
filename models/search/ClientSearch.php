<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Client;
use yii\db\Expression;

/**
 * ClientSearch represents the model behind the search form about `app\models\Client`.
 */
class ClientSearch extends Client
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['company_name', 'website', 'notes', 'created_at', 'updated_at', 'search_customer_count'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $countCustomerExp = new Expression("COUNT(c.id)");
        $query = Client::find();
        $query->select([
            'cl.*',
            'search_customer_count' => $countCustomerExp,
        ]);
        $query->alias('cl');
        $query->joinWith('customers c');
        $query->groupBy('cl.id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'company_name',
                    'website',
                    'updated_at',
                    'search_customer_count' => [
                        'asc' => [(string)$countCustomerExp => SORT_ASC],
                        'desc' => [(string)$countCustomerExp => SORT_DESC],
                    ]
                ],
                'defaultOrder' => [
                    'company_name' => SORT_ASC,
                    'search_customer_count' => SORT_DESC
                ]
            ]
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterHaving([(string)$countCustomerExp => $this->search_customer_count])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
