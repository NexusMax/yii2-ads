<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\behaviors\TimestampBehavior;


class MagazinePeriod extends \yii\db\ActiveRecord
{


    public static function tableName()
    {
        return '{{%magazine_period}}';
    }


    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getPrice()
    {
        return $this->hasMany(\frontend\models\MagazinePrice::className(), ['plan_id' => 'id']);
    }


    public function rules()
    {
        return [
            [['days', 'created_at', 'updated_at', 'active'], 'integer'],
            ['active', 'default', 'value' => 1],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'days' => 'Колличество дней',
            'created_at' => 'Дата создания',
            'updated_at' => 'Последнее обновление',

        ];
    }
   
}