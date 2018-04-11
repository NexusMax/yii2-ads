<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbss'][] = ['label' => $magazine['name'], 'url' => ['/myaccount/magazine/view', 'id' => $magazine['id']]];
$this->params['breadcrumbss'][] = $this->title;
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-order-index">

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
            [
                'label' => 'ID',
                'value' => 'id',
                'contentOptions' => ['data-label' => 'ID'],
            ],
            //'id',
            [

                'label' => 'Пользователь',
                'value' => function( $model ){
                    return $model->user->username . ' ' . $model->user->lastname;
                },
                'contentOptions' => ['data-label' => 'Пользователь'],
            ],
            [
                'label' => 'Дата добавления',
                'value' => function( $model ){
                    return date('d-m-Y H:i', $model->created_at);
                },
                'contentOptions' => ['data-label' => 'Дата добавления'],
            ],
            //'created_at:datetime',
            [
                'label' => 'Телефон',
                'value' => 'phone',
                'contentOptions' => ['data-label' => 'Телефон'],
            ],
            //'phone',
            // 'address',
            [
                'label' => 'Емейл',
                'value' => 'email',
                'contentOptions' => ['data-label' => 'Емейл'],
            ],
            //'email:email',
            // 'notes:ntext',
            [
                'label' => 'Статус ',
                'value' => function( $model ){
                    return $model->getStatus()[$model->status];
                },
                'contentOptions' => ['data-label' => 'Статус'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => ' {view} {update} {delete}',
                'buttons' => [
                    'view' => function($url, $model, $key) {
                        return Html::a('<span class="span-rel"><i class="big-i fa fa-external-link" aria-hidden="true"></i><p>Просмотр</p></span>', Url::to(['/myaccount/magazine/order/view', 'id' => $model->id]), ['class' => 'pay-a']);
                    },
                    'update' => function ($url, $model, $key) {
                         return Html::a('<span class="span-rel"><i class="big-i fa fa-pencil" aria-hidden="true"></i><p>Редактировать</p></span>', Url::to(['/myaccount/magazine/order/update', 'id' => $model->id]), ['class' => 'pay-a']);
                    },
                    'delete' => function ($url, $model, $key) {
                         return Html::a('<span class="span-rel"><i class="big-i fa fa-times" aria-hidden="true"></i><p>Удалить</p></span>', Url::to(['/myaccount/magazine/order/delete', 'id' => $model->id]), ['data-method' => 'POST', 'class' => 'pay-a']);
                    }
                ],
                'contentOptions' => ['data-label' => 'Настройки'],
            ],
        ],
    ]); ?>
</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>