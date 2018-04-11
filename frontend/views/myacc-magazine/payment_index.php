<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = $magazine['name'];
$this->params['breadcrumbss'][] = ['label' => $this->title, 'url' => ['/myaccount/magazine/view', 'id' => $magazine['id']]];
$this->params['breadcrumbss'][] = ['label' => 'Оплата'];
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-has-payment-index">
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
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'payment.name',
            'public_key',
            'private_key',
            // 'card',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{payment} {view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                         return Html::a('<span class="span-rel"><i class="big-i fa fa-pencil" aria-hidden="true"></i><p>Редактировать</p></span>', Url::to(['/myaccount/magazine/payment/update', 'id' => $model->id]), ['class' => 'pay-a']);
                    },
                    'delete' => function ($url, $model, $key) {
                         return Html::a('<span class="span-rel"><i class="big-i fa fa-times" aria-hidden="true"></i><p>Удалить</p></span>', Url::to(['/myaccount/magazine/payment/delete', 'id' => $model->id]), ['data-method' => 'POST', 'class' => 'pay-a']);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>