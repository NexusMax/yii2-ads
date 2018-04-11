<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\base\Model;


class Search extends Model
{
    public $q;
    public $city;
    public $img;
    public $category_id;


    public function rules()
    {
        return [
            [['q','city'], 'string', 'max' => 128, 'skipOnEmpty' => false, 'skipOnError' => false],
            [['q','city', 'img'], 'trim'],
            [['img', 'category_id'], 'integer'],
        ];
    }
}
