<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineOrderItem */

$this->title = $model->name;
$this->params['breadcrumbss'][] = ['label' => $magazine['name'], 'url' => ['/myaccount/magazine/view', 'id' => $magazine['id']]];
$this->params['breadcrumbss'][] = ['label' => 'Заказ ' . $order['id'], 'url' => ['/myaccount/magazine/order/view', 'id' => $order['id']]];
$this->params['breadcrumbss'][] = ['label' => 'Товары', 'url' => ['index', 'id' => $model->order_id]];
$this->params['breadcrumbss'][] = $this->title;
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-order-item-view">

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-sm btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'myoffersnew small active', 'style' => 'width:100%'],
        'template' => '<tr class="tr row-elem"><th{captionOptions}>{label}</th><td{contentOptions}>{value}</td></tr>',
        'attributes' => [
            'id',
            [
                'label' => 'Номер заказа',
                'value' => $model->order->id,
            ],
            'name',
            'price',
            [
                'label' => 'Товар',
                'value' => $model->product->name,
            ],
            'quantity',
        ],
    ]) ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>