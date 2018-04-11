<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineHasPayment */

$this->title = 'Обновить оплату: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Оплата магазинов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="magazine-has-payment-update">

    <?= $this->render('_form', [
        'model' => $model,
        'magazines' => $magazines,
        'payments' => $payments,
    ]) ?>

</div>
