<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use frontend\models\Ads;
use yii\web\UploadedFile;


class Message extends ActiveRecord
{
    public $images;

    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ],
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
        return '{{%message}}';
    }

    public function rules()
    {
        return [
            [['images'], 'file', 'extensions' => 'png, jpg, jpeg, gif'],
            [['message'], 'string'],
            [['ads_id', 'from', 'first_message_id', 'id_messages', 'for', 'author_ads_id', 'magazine_id'], 'integer'],
        ];
    }

    public function getImage()
    {
        return $this->hasOne(\rico\yii2images\models\Image::className(), ['itemId' => 'id'])
            ->onCondition(['modelName' => $this->tableName()]);
    }


    public static function sendMessage($ads_id, $message, $author_id, $my_id, $magazine_id = null)
    {
        $mess = new Message;

        $mess->ads_id = $ads_id;
        $mess->message = $message;
        $mess->from = $my_id;
        $mess->first_message_id = $my_id;
        $mess->author_ads_id = $author_id;
        $mess->for = $author_id;
        $mess->magazine_id = $magazine_id;
        $mess->id_messages = $author_id . $my_id . $ads_id;
        $mess->created_at = time();

        $mess->save();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            $my_id = Yii::$app->user->identity->id;

            // $this->from = $my_id;
            // $this->first_message_id = $my_id;
            // $this->for = $this->author_ads_id;
            // $this->id_messages = $this->author_ads_id . $my_id . $this->ads_id;
            $this->created_at = time();

            // echo '<pre>';print_r($this);die;

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->images = UploadedFile::getInstance($this, 'images');

            if(!empty($this->images))
                $this->uploads();
    }

    public function uploads()
    {   
        if ($this->validate(false)) {
            $alias = Yii::getAlias('@appWeb') . '/uploads/ads/' . $this->images->baseName . '.' . $this->images->extension;
            $this->images->saveAs($alias);

            $image = new \Imagick($alias);
            $image->setImageCompressionQuality(75);

            $watermark = new \Imagick();
            $watermark->readImage( Yii::getAlias('@frontend') . '/web/images/jandooows_mini.png');
            $image->compositeImage($watermark, \imagick::COMPOSITE_OVER, 10, 10);
            $image->writeImage($alias);

            $this->attachImage($alias);
            @unlink($alias);

            return true;
        } else {
            return false;
        }
    }

    public static function getUnreadCount()
    {
        $my_id = Yii::$app->user->identity->id;
        return Message::find()->select('count(*) as k')->where('(`author_ads_id` = "' . $my_id . '" OR `for` = "' . $my_id . '") AND `unread` = "0"')->asArray()->groupBy('id_messages')->one()['k'];
    }

}