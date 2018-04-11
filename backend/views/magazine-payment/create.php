<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineHasPayment */

$this->title = 'Добавить оплату к магазину';
$this->params['breadcrumbs'][] = ['label' => 'Оплата магазинов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-has-payment-create">

    <?= $this->render('_form', [
        'model' => $model,
        'magazines' => $magazines,
        'payments' => $payments,
    ]) ?>

</div>
