<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineOrderItem */

$this->title = 'Обновить товар: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index', 'id' => $model->order_id]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обовить';
?>
<div class="magazine-order-item-update">

    <?= $this->render('_form', [
        'model' => $model,
        'ads' => $ads,
    ]) ?>

</div>
