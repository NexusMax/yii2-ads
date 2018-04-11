<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;


class MagazineHasDelivery extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%magazine_has_delivery}}';
    }


    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_id', 'magazine_id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'delivery_id' => 'Тип доставки',
            'magazine_id' => 'Магазин',
            'created_at' => 'Дата создания',
            'updated_at' => 'Последнее обновление',
        ];
    }


    public function getMagazin()
    {
        return $this->hasOne(\frontend\models\Magazine::className(), ['id' => 'magazine_id']);
    }

    public function getDelivery()
    {
        return $this->hasOne(\frontend\models\MagazineDelivery::className(), ['id' => 'delivery_id']);
    }

    public static function saveDeliveries($deliveries, $model_id)
    {
        foreach ($deliveries as $key) {
            $delivery = new MagazineHasDelivery();
            $delivery->delivery_id = $key;
            $delivery->magazine_id = $model_id;
            $delivery->save();
        }
    }

}