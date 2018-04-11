<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%magazine_ads_log}}".
 *
 * @property integer $id
 * @property integer $magazine_id
 * @property integer $user_id
 * @property integer $type
 * @property integer $created_at
 * @property integer $updated_at
 */
class MagazineAdsLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%magazine_ads_log}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['magazine_id', 'user_id', 'type', 'created_at', 'updated_at'], 'integer'],
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
            'user_id' => 'Пользователь',
            'type' => 'Тип',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}
