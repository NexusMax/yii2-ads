<?php

use yii\helpers\Html;

$this->title = $magazine['name'];
$this->params['breadcrumbss'][] = ['label' => $this->title, 'url' => ['/myaccount/magazine/view', 'id' => $magazine['id']]];
$this->params['breadcrumbss'][] = ['label' => 'Оплата', 'url' => ['/myaccount/magazine/payment', 'id' => $magazine['id']]];
$this->params['breadcrumbss'][] = ['label' => $model->payment->name, 'url' => ['/myaccount/magazine/payment/view', 'id' => $model->id]];
$this->params['breadcrumbss'][] = 'Обновление';
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-has-payment-update">

    <?= $this->render('payment_form', [
        'model' => $model,
        'magazines' => $magazines,
        'payments' => $payments,
    ]) ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>