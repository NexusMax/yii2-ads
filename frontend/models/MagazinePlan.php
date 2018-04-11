<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\behaviors\TimestampBehavior;


class MagazinePlan extends \yii\db\ActiveRecord
{


    public static function tableName()
    {
        return '{{%magazine_plan}}';
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

    public function getPriceIndex()
    {
        return $this->hasMany(\frontend\models\MagazinePrice::className(), ['plan_id' => 'id'])->indexBy('period_id');
    }

    public function getFirstPrice()
    {
        return $this->hasMany(\frontend\models\MagazinePrice::className(), ['plan_id' => 'id'])->where(['period_id' => Yii::$app->request->post('Magazine')['period']]);
    }


    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'active'], 'integer'],
            ['active', 'default', 'value' => 1],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'active' => 'Активность',
            'created_at' => 'Дата создания',
            'updated_at' => 'Последнее обновление',

        ];
    }
   
}