<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineHasPayment */

$this->title = $model->payment->name;
$this->params['breadcrumbss'][] = ['label' => $model->magazin->name, 'url' => ['/myaccount/magazine/view', 'id' =>  $model->magazin->id]];
$this->params['breadcrumbss'][] = ['label' => 'Оплата', 'url' => ['/myaccount/magazine/payment', 'id' => $model->magazin->id]];
$this->params['breadcrumbss'][] = $this->title;
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-has-payment-view">

    <?= DetailView::widget([
        'options' => ['class' => 'myoffersnew small active', 'style' => 'width:100%'],
        'template' => '<tr class="tr row-elem"><th{captionOptions}>{label}</th><td{contentOptions}>{value}</td></tr>',
        'model' => $model,
        'attributes' => [
            'id',
            'magazine_id',
            'payment_id',
            'public_key',
            'private_key',
            'card',
        ],
    ]) ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>