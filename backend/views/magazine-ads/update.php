<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineAds */

$this->title = 'Обновление товара: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index', 'id' => $model->magazine_id]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновления';
?>
<div class="magazine-ads-update">

    <?= $this->render('_form', [
        'model' => $model,
        'fields' => $fields,
        'params' => $params,
    ]) ?>

</div>
