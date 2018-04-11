<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;


class MagazineDelivery extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%magazine_delivery}}';
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


    public function getMagazin()
    {
        return $this->hasMany(\frontend\models\MagazineHasDelivery::className(), ['delivery_id' => 'id']);
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