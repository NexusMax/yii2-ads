<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazinePayment */

$this->title = 'Обновление оплаты: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Оплаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="magazine-payment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
