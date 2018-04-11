<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Доп. поля к категориям';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-eav-fields-index">

    <p>
        <?= Html::a('Добавить доп. поле', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            // 'name',
            'name_field',
            'type_id',
            [
                'label' => 'Категория',
                'value' => function ($model){
                    return $model->category->name;
                }
            ],
            [
                'label' => 'Активность',
                'value' => function ($model){
                    return $model->active === 1 ? 'Да' : 'Нет';
                }
            ],
            // 'search',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
