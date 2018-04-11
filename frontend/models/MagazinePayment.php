<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;


class MagazinePayment extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%magazine_payment}}';
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
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['type'], 'string', 'max' => 255],
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
            'created_at' => 'Дата создания',
            'updated_at' => 'Последнее обновление',
            'type' => 'Дополнительное поле',
        ];
    }


    public function getTarif()
    {
        return $this->hasOne(\frontend\models\MagazinePlan::className(), ['id' => 'tarif_plan']);
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

}