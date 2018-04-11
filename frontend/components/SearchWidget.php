<?php

namespace frontend\components;


use yii\base\Widget;
use backend\models\Categories;
use yii;


class SearchWidget extends Widget
{

    public $data;
    public $model;
    public $img;
    public $categories;
    public $category;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('widget-search', [
            'data' => $this->data,
            'model' => $this->model,
            'category' => $this->category,
        ]);
    }
}