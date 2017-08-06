<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Customer;
use yii\db\Expression;

/**
 * CustomerSearch represents the model behind the search form about `app\models\Customer`.
 */
class CustomerSearch extends Customer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [
                ['first_name', 'last_name', 'email', 'address', 'created_at', 'updated_at', 'search_full_name', 'search_client_company_name'],
                'safe'
            ],
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
        $fullNameExp = new Expression('CONCAT_WS(" ", first_name, last_name)');
        $companyNameExp = new Expression('CONCAT_WS(" - ", cl.company_name, cl.website)');
        $query = Customer::find();
        $query->select([
            'c.*',
            'search_full_name' => $fullNameExp,
            'search_client_company_name' => $companyNameExp,
        ]);
        $query->alias('c');
        $query->joinWith('client cl');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'email',
            'sort' => [
                'attributes' => [
                    'email',
                    'address',
                    'search_full_name' => [
                        'asc' => [(string)$fullNameExp => SORT_ASC],
                        'desc' => [(string)$fullNameExp => SORT_DESC],
                    ],
                    'search_client_company_name' => [
                        'asc' => [(string)$companyNameExp => SORT_ASC],
                        'desc' => [(string)$companyNameExp => SORT_DESC],
                    ]
                ],
                'defaultOrder' => [
                    'search_full_name' => SORT_ASC,
                    'email' => SORT_ASC,
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

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', $fullNameExp, $this->search_full_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', $companyNameExp, $this->search_client_company_name])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
