<?php

namespace frontend\models;

use Yii;
use yii\db\Query;


class AdsFavorites extends \yii\db\ActiveRecord
{


    // ((\+[0-9]{3,3})|0)[-]?[0-9]{9}
        // [a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?
        // [a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?
        // (https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?


        // ^((?!hede).)*$
        // ^((?!((\+[0-9]{3,3})|0)[-]?[0-9]{9}|([a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?)|(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?).)*$
        // /^((?!
        // ()|
        // ()|
        // ()
        // ).)$/

    public function rules()
    {
        return [
            [['ads_id', 'user_id'], 'required'],
            [['ads_id', 'user_id'], 'integer']
        ];
    }

	public static function tableName()
    {
        return '{{%user_has_favorites}}';
    }
    
    public static function setFavorite($ads_id, $user_id)
    {
    	$favorite = new AdsFavorites;
    	$favorite->ads_id = $ads_id;
    	$favorite->user_id = $user_id;
    	return $favorite->save();
    }

    public static function deleteFavorite($ads_id, $user_id)
    {
    	$favorite = AdsFavorites::find()->where(['ads_id' => $ads_id])->andWhere(['user_id' => $user_id])->all();
        foreach ($favorite as $key) {
            $key->delete();
        }
    	
    }
}
