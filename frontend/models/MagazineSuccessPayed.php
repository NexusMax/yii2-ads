<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%magazine_success_payed}}".
 *
 * @property integer $id
 * @property integer $magazine_id
 * @property integer $user_id
 * @property double $sum
 * @property integer $created_at
 * @property integer $updated_at
 */
class MagazineSuccessPayed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%magazine_success_payed}}';
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
            [['magazine_id', 'user_id', 'created_at', 'updated_at', 'tarif_id', 'individual_template', 'payed'], 'integer'],
            [['individual_template', 'payed'], 'default', 'value' => 0],
            [['sum'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'magazine_id' => 'Магазин',
            'tarif_id' => 'Тариф',
            'individual_template' => 'Индивидуальный шаблон',
            'user_id' => 'Пользователь',
            'sum' => 'Сумма',
            'payed' => 'Оплачено',
            'created_at' => 'Дата оплаты',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function getMagazine()
    {
        return $this->hasOne(\frontend\models\Magazine::className(), ['id' => 'magazine_id']);
    }

    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    public function getTarif()
    {
        return $this->hasOne(\frontend\models\MagazinePrice::className(), ['id' => 'tarif_id'])->with('plan')->with('period');
    }

}
