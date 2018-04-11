<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\base\Model;


class SearchMyaccount extends Model
{
    public $q;

    public function rules()
    {
        return [
            [['q'], 'string', 'max' => 128, 'skipOnEmpty' => false, 'skipOnError' => false],

        ];
    }
}
