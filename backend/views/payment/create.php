<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MagazinePayment */

$this->title = 'Создание оплаты';
$this->params['breadcrumbs'][] = ['label' => 'Оплаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-payment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
