<?php

namespace frontend\components;


use yii\base\Widget;
use backend\models\Categories;
use yii;


class ProductWidget extends Widget
{
    // data
    public $data;
    // number file view
    public $viewNumber;


    public function init()
    {
        parent::init();
        if($this->viewNumber === null) $this->viewNumber = 'one';
    }

    public function run()
    {

        return $this->render('widget-product-' . $this->viewNumber . '.php', [
            'data' => $this->data,
        ]);
    }
}