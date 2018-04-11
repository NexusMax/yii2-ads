<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => Url::to(['myaccount/index'])];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'alias' => $model->alias]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>

    <?= $this->render('_form', [
        'model' => $model,
        'user' => $user,
        'categories' => $categories,
        'parent_child' => $parent_child,
        'childs_childs_cat' => $childs_childs_cat,
        'childs_cat' => $childs_cat,
        'startSubField' => $startSubField,
        'images' => $images,
        'reg' => $reg,
        'city' => $city,
    ]) ?>
