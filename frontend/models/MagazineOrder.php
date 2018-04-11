<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%magazine_order}}".
 *
 * @property integer $id
 * @property integer $magazine_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $phone
 * @property string $address
 * @property string $email
 * @property string $notes
 * @property string $status
 */
class MagazineOrder extends \yii\db\ActiveRecord
{

    public $total;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%magazine_order}}';
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
            [['magazine_id'], 'required'],
            [['magazine_id', 'created_at', 'updated_at', 'user_id', 'status'], 'integer'],
            [['notes'], 'string'],
            ['email', 'email'],
            [['phone'], 'match', 'pattern' => '/((\+[0-9]{6})|0)[-]?[0-9]{9}/'],
            [['phone', 'address', 'email'], 'string', 'max' => 255],
            ['status', 'default', 'value' => 0],
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
            'created_at' => 'Дата добавления',
            'updated_at' => 'Последнее обновление',
            'phone' => 'Телефон',
            'address' => 'Адрес',
            'email' => 'Емейл',
            'notes' => 'Заметки',
            'status' => 'Статус',
            'user_id' => 'Пользователь',
        ];
    }

    public function getItem()
    {
        return $this->hasMany(\frontend\models\MagazineOrderItem::className(), ['order_id' => 'id']);
    }

    public function getMagazine()
    {
        return $this->hasOne(\frontend\models\Magazine::className(), ['id' => 'magazine_id']);
    }

    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    public function getStatus()
    {
        return [
            'Ожидается',
            'Выполнен',
            'Отклонён',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if($this->isNewRecord){
                $this->user_id = Yii::$app->user->identity->id;
                $this->status = 0;
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    public function getAllPrice()
    {
        foreach ($this->item as $key) {
            $this->total += $key->price * $key->quantity;
        }

        return $this->total;
    }

    public function sendEmail()
    {
        return Yii::$app->mailer->compose('order', ['order' => $this])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('Новый заказ #' . $this->id)
            ->send();
    }
}
