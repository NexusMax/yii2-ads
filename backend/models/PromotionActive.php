<?php

namespace backend\models;

use Yii;

class PromotionActive extends \yii\db\ActiveRecord
{	


    public function rules()
    {
        return [
            ['created_at', 'validity_at', 'integer', 'required'],
        ];
    }
   	public static function tableName()
    {
        return '{{%ads_promotion}}';
    }

}