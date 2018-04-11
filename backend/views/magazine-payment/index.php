<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Оплата магазина';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-has-payment-index">

    <p>
        <?= Html::a('Добавить оплату', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'magazin.name',
            'payment.name',
            'public_key',
            'private_key',
            // 'card',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
