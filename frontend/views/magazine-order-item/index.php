<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказанные товары';
$this->params['breadcrumbss'][] = ['label' => $magazine['name'], 'url' => ['/myaccount/magazine/view', 'id' => $magazine['id']]];
$this->params['breadcrumbss'][] = ['label' => 'Заказ ' . $order['id'], 'url' => ['/myaccount/magazine/order/view', 'id' => $order['id']]];
$this->params['breadcrumbss'][] = $this->title;
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-order-item-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => [
            'class' => 'myoffersnew small active',
        ],
        'rowOptions' => [
            'class' => 'tr row-elem',
        ],
        'columns' => [
            'id',
            'order_id',
            'name',
            'product_id',
            [
                'label' => 'ID Товар',
                'value' => function( $model ) {
                    return $model->product_id;
                },
            ],
            'price',
            'quantity',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => ' {view} {update} {delete}',
                'buttons' => [
                    'view' => function($url, $model, $key) {
                        return Html::a('<span class="span-rel"><i class="big-i fa fa-external-link" aria-hidden="true"></i><p>Просмотр</p></span>', Url::to(['/myaccount/magazine/order-item/view', 'id' => $model->id]), ['class' => 'pay-a']);
                    },
                    'update' => function ($url, $model, $key) {
                         return Html::a('<span class="span-rel"><i class="big-i fa fa-pencil" aria-hidden="true"></i><p>Редактировать</p></span>', Url::to(['/myaccount/magazine/order-item/update', 'id' => $model->id]), ['class' => 'pay-a']);
                    },
                    'delete' => function ($url, $model, $key) {
                         return Html::a('<span class="span-rel"><i class="big-i fa fa-times" aria-hidden="true"></i><p>Удалить</p></span>', Url::to(['/myaccount/magazine/order-item/delete', 'id' => $model->id]), ['data-method' => 'POST', 'class' => 'pay-a']);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>