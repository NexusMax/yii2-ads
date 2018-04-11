<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazinePrice */

$this->title = 'Обновить тариф: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Тарифы планов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="magazine-price-update">

    <?= $this->render('_form', [
        'model' => $model,
        'plans' => $plans,
        'periods' => $periods,
    ]) ?>

</div>
