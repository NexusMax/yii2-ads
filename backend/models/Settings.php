<?php

namespace backend\models;

use Yii;

use yii\db\ActiveRecord;
use yii\db\Query;
use yii\behaviors\TimestampBehavior;

class Settings extends ActiveRecord
{



    public static function tableName()
    {
        return '{{%settings}}';
    }


    public function rules()
    {
        return [
            
            [['active'], 'integer'],

        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }


 

}