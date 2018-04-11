<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Magazine */

$this->title = 'Обновить магазин: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="magazine-update">

    <?= $this->render('_form', [
        'model' => $model,
	    'plans' => $plans,
	    'periods' => $periods,
	    'categories' => $categories,
        'deliveries' => $deliveries,
        'payments' => $payments,
        'reg' => $reg,
        'city' => $city,
    ]) ?>

</div>
