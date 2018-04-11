<?php

namespace backend\models;

use Yii;

use yii\db\ActiveRecord;
use yii\db\Query;
use yii\behaviors\TimestampBehavior;

class Stock extends ActiveRecord
{

	public function behaviours()
	{
		return [
			[
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // если вместо метки времени UNIX используется datetime:
                // 'value' => new Expression('NOW()'),
            ],
		];
	}

    public static function tableName()
    {
        return '{{%stock}}';
    }


    public function rules()
    {
        return [
            
            [['validity_at', 'sum'], 'integer'],

        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название страницы',
        ];
    }


 

}