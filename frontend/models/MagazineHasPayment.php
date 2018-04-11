<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;


class MagazineHasPayment extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%magazine_has_payment}}';
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
            [['magazine_id', 'payment_id', 'created_at', 'updated_at'], 'integer'],
            [['public_key', 'private_key', 'card'], 'string', 'max' => 255],
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
            'payment_id' => 'Тип оплаты',
            'magazine_id' => 'Магазин',
            'public_key' => 'Публичный ключ',
            'private_key' => 'Приватный ключ',
            'card' => 'Номер счета',
            'created_at' => 'Дата создания',
            'updated_at' => 'Последнее обновление',
        ];
    }


    public function getMagazin()
    {
        return $this->hasOne(\frontend\models\Magazine::className(), ['id' => 'magazine_id']);
    }

    public function getPayment()
    {
        return $this->hasOne(\frontend\models\MagazinePayment::className(), ['id' => 'payment_id']);
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    public static function savePayments($payments, $model_id)
    {
        foreach ($payments as $key) {
            $payment = new MagazineHasPayment();
            $payment->payment_id = $key;
            $payment->magazine_id = $model_id;
            $payment->save();
        }
    }

}