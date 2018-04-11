<?php

namespace frontend\models;

use Yii;

class AdsHasUpdate extends \yii\db\ActiveRecord
{	
   	public static function tableName()
    {
        return '{{%ads_has_update}}';
    }


    public function SaveRef($id)
    {
    	$refresh_ad = new AdsHasUpdate();
        $refresh_ad->ads_id = $id;
        $refresh_ad->updated_at = time();
        $refresh_ad->save();
    }

    public static function getCurrentRefresh($id)
    {
    	return AdsHasUpdate::find()->where(['ads_id' => $id])->andWhere(['>', 'updated_at', strtotime('-1 Month')])->asArray()->orderBy('updated_at ASC')->all();

    }

    public static function getInfo($info)
    {
        $count = count($info);


        $text_ = '';
        switch ($count) {
            case 0: $text_ = 'У вас осталось 2 бесплатное поднятие в этом месяце!'; break;
            case 1: $text_ = 'У вас осталось еще 1 бесплатное поднятие в этом месяце!'; break;
            case 2: $text_ = '<strong>Обьявление можно поднимать только 2 раза в месяц.</strong> Последнее обновление: <strong>' . Yii::$app->formatter->asDate($info[count($info)-1]['updated_at'], 'php:d M в H:s') . '</strong>'; break;
            case 3: ''; break;
            
            default:
                break;
        }
        return $text_;
    }

}