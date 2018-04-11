<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-order-index">

    <p>
        <?= Html::a('Добавить новый заказ', ['create', 'id' => $magazine['id']], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'label' => 'Магазин',
                'value' => function( $model ){
                    return $model->magazine->name;
                },
            ],
            [
                'label' => 'Пользователь',
                'value' => function( $model ){
                    return $model->user->username . ' ' . $model->user->lastname;
                },
            ],
            'created_at:datetime',
            'updated_at:datetime',
            // 'phone',
            // 'address',
            // 'email:email',
            // 'notes:ntext',
            'status',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
