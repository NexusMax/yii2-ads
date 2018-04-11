<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\behaviors\TimestampBehavior;


class MagazinePrice extends \yii\db\ActiveRecord
{


    public static function tableName()
    {
        return '{{%magazine_price}}';
    }


    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }


    public function rules()
    {
        return [
            [['period_id', 'plan_id', 'count_ads', 'top_30_day', 'design', 'order', 'price', 'old_price', 'fire', 'dop_tov', 'ind_design', 'per_consult','created_at', 'updated_at'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'period_id' => 'Номер периода',
            'plan_id' => 'Номер плана',
            'count_ads' => 'Колличество товаров',
            'top_30_day' => 'Топ товаров на 30 дней',
            'design' => 'Дизайн',
            'price' => 'Сумма',
            'old_price' => 'Старая сумма',
            'created_at' => 'Дата создания',
            'updated_at' => 'Последнее обновление',
            'order' => 'Порядок',
            'fire' => 'Срочно',
            'dop_tov' => 'Допольнительные товары',
            'ind_design' => 'Индивидуальный дизайн',
            'per_consult' => 'Персональный консультант',
        ];
    }


    public function getPlan()
    {
        return $this->hasOne(\frontend\models\MagazinePlan::className(), ['id' => 'plan_id']);
    }

    public function getPeriod()
    {
        return $this->hasOne(\frontend\models\MagazinePeriod::className(), ['id' => 'period_id']);
    }
   
}