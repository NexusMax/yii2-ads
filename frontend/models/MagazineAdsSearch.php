<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\MagazineAds;

/**
 * MagazineAdsSearch represents the model behind the search form about `frontend\models\MagazineAds`.
 */
class MagazineAdsSearch extends MagazineAds
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'magazine_id', 'user_id', 'category_id', 'active', 'created_at', 'updated_at', 'validity_at', 'bargain', 'negotiable', 'type_payment', 'type_delivery', 'views', 'number_views', 'city_id', 'reg_id', 'type_ads', 'publish'], 'integer'],
            [['alias', 'name', 'text', 'location', 'phone', 'contact', 'email', 'phone_2', 'phone_3'], 'safe'],
            [['price'], 'number'],
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
    public function search($params, $magazine_id, $sort, $categories, $id = null)
    {
        $query = MagazineAds::find()->where(['magazine_id' => $magazine_id])->with('mainImage')->with('fire')->orderBy($sort->orders);


        // add conditions that should always apply here
        if ($id !== null && isset($categories[$id])) {
            $query->where(['category_id' => $this->getCategoryIds($categories, $id)]);

        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load(['MagazineAdsSearch' => $params]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            // 'category_id' => $this->category_id,
            'price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'location', $this->location]);

        return $dataProvider;
    }

    public function getCategoryIds($categories, $categoryId, &$categoryIds = [])
    {
        foreach ($categories as $category) {
            if ($category['id'] == $categoryId) {
                $categoryIds[] = $category['id'];
            }
            elseif ($category['parent_id'] == $categoryId){
                $this->getCategoryIds($categories, $category['id'], $categoryIds);
            }
        }
        return $categoryIds;
    }
}
