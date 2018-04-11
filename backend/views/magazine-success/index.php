<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Бухгалтерия';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-success-payed-index">

    <p>
        <?= Html::a('Добавить оплату', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label' => 'Магазин',
                'value' => 'magazine.name',
            ],
            [
                'label' => 'Пользователь',
                'value' => function($model){
                    return $model->user->username . ' ' . $model->user->lastname;
                },
            ],
            [
                'label' => 'Тариф',
                'value' => function($model){

                    return $model->tarif->plan->name . ' (период: ' . $model->tarif->period->name . ')';
                },
            ],
            [
                'label' => 'Индивидуальный шаблон',
                'value' => function($model){
                    return ($model->individual_template == 1) ? 'Да' : 'Нет';
                },
            ],
            [
                'label' => 'Оплачено',
                'value' => function($model){
                    return ($model->payed == 1) ? 'Да' : 'Нет';
                },
            ],
            'sum',
            'created_at:dateTime',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
