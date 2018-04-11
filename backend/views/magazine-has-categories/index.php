<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-has-categories-index">

    <p>
        <?= Html::a('Добавить категорию', ['create', 'id' => $magazine['id']], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label'=>'Изображение',
                'value'=> function( $model ) {
                    return $model->image->getUrl();
                },
                'format' => ['image',['style'=>'max-width:100px; max-height:100px']],
            ],
            [
                'label'=>'Магазин',
                'value'=> function ($model){
                    return $model->magazin->name;
                }
            ],
            [
                'label'=>'Категория',
                'value'=> function ($model){
                    return empty($model->parent->name) ? 'Главная категория' : $model->parent->name;
                }
            ],
            'alias',
            'name',
            // 'active',
            // 'sort',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
