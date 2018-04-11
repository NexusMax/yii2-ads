<?php

namespace backend\models;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

use Yii;


class Email extends \yii\db\ActiveRecord
{

	public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],

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
        return '{{%email}}';
    }


    public function rules()
    {
        return [
            [['subject', 'admin_email', 'message'], 'required'],
            [['text'], 'safe'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Отправлено',
            'updated_at' => 'Обновлено',
            'message' => 'Текст сообщения',
            'subject' => 'Заголовок письма',
            'admin_email' => 'Email',
        ];
    }
}