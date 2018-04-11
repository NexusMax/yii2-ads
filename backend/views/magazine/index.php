<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Магазины';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-index">

    <p>
        <?= Html::a('Создать магазин', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'contentOptions' => ['class' => 'td'],
                'label'=>'Лого',
                'value'=> function ($model){
                    return $model->image->getUrl();
                },
                'format' => 'image',
            ],
            'name',
            'alias',
            [
                'label'=>'Категория',
                'value'=>'category.name',
            ],
            'user_id',
            'worked_start_at:time',
            'worked_end_at:time',
            // 'desc:ntext',
            // 'template',
            // 'tarif_plan',
            // 'active',
            // 'period',
            // 'validity_at',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{payment} {view} {update} {delete}',
                'buttons' => [
                    'payment' => function ($url, $model, $key) {
                         return Html::a('<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>', Url::to(['magazine-payment/magazine-view', 'id' => $model->id]));
                    }
                ],
            ],
        ],
    ]); ?>
</div>
