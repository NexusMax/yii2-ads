<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineSuccessPayed */

$this->title = 'Обновить оплату: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Бухгалтерия', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="magazine-success-payed-update">

    <?= $this->render('_form', [
        'model' => $model,
        'tarif' => $tarif,
    ]) ?>

</div>
