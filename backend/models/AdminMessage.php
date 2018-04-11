<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%categories}}".
 *

 */
class AdminMessage extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */


    public static function tableName()
    {
        return '{{%admin_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'unread', 'created_at'], 'integer'],
            ['email', 'email'],
            [['text'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'text' => 'Сообщение'
        ];
    }

    public function getUser()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'user_id']);
    }

    public function getParent()
    {
        return $this->hasOne(\backend\models\AdminMessage::className(), ['parent_id' => 'id'])->where(['not in', 'parent_id', '0']);
    }

    public static function getLastMessage($count = 5)
    {
        return AdminMessage::find()->orderBy('id DESC')->limit($count)->all();
    }

    public static function getUnreadMessage()
    {
        return AdminMessage::find()->select('count(*) as k')->where('unread = 0')->asArray()->orderBy('id DESC')->with('user')->one()['k'];
    }

}