<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Тарифы планов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-price-index">
    <p>
        <?= Html::a('Создать новый тариф', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label'=>'План',
                'value'=>'plan.name',
            ],
            [
                'label'=>'Период',
                'value'=>'period.name',
            ],
            'count_ads',
            'top_30_day',
            // 'design',
            // 'price',
            // 'order',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
