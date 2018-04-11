<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Объявления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-ads-index">

    <p>
        <?= Html::a('Добавить объявление', ['create', 'id' => $magazine['id']], ['class' => 'btn btn-success']) ?>
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
                'label'=>'Пользователь',
                'value'=> function ($model){
                    return $model->user->username . ' ' . $model->user->lastname;
                }
            ],
            'alias',
            'name',
            // 'category_id',
            // 'text:ntext',
            // 'active',
            // 'created_at',
            // 'updated_at',
            // 'validity_at',
            // 'price',
            // 'bargain',
            // 'negotiable',
            // 'type_payment',
            // 'type_delivery',
            // 'location',
            // 'phone',
            // 'contact',
            // 'email:email',
            // 'views',
            // 'number_views',
            // 'phone_2',
            // 'city_id',
            // 'reg_id',
            // 'phone_3',
            // 'type_ads',
            // 'publish',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{copy} {view} {update} {delete}',
                'buttons' => [
                    'copy' => function($url, $model, $key) {
                        return Html::a('<i class="big-i fa fa-clone" aria-hidden="true"></i>', Url::to(['copy', 'id' => $model->id]));
                    }
                ],
            ],
        ],
    ]); ?>
</div>
