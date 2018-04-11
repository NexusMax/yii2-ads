<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Magazine;

/**
 * MagazineSearch represents the model behind the search form about `frontend\models\Magazine`.
 */
class MagazineSearch extends Magazine
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'user_id', 'template', 'tarif_plan', 'active', 'period', 'validity_at', 'created_at', 'updated_at', 'city_id'], 'integer'],
            [['name', 'alias', 'desc', 'email', 'phone', 'phone_2', 'contact'], 'safe'],
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
        $query = Magazine::find()->where('active = 1')->andWhere(['>' , 'validity_at', time()])->with('imageRico')->indexBy('id')->with('city')->with('category')->orderBy('id DESC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load(['MagazineSearch' => $params]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'category_id' => $this->category_id,
            'city_id' => $this->city_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc]);


        return $dataProvider;
    }
}
