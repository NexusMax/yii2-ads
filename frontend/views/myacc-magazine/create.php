<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Magazine */

$this->title = 'Создать магазин';
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="magazine-create">

    <?= $this->render('_form', [
        'model' => $model,
        'plans' => $plans,
        'periods' => $periods,
        'categories' => $categories,
        'deliveries' => $deliveries,
        'payments' => $payments,
    ]) ?>

</div>
