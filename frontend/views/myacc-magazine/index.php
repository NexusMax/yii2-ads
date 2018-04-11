<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-index">
    <? if( \Yii::$app->devicedetect->isMobile() ):?>
        <div class="" style="">
            <!--?$data = $dataProvider->getModels(); print_r($data);?-->
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => false,
                'tableOptions' => [
                    'class' => 'myoffersnew small active shops-tab_table',
                ],
                'rowOptions' => [
                    'class' => 'tr row-elem',
                ],
                'columns' => [
                    [
                        'contentOptions' => ['class' => 'td'],
                        'label'=>'Лого',
                        'value'=> function ($model){
                            return $model->image->getUrl();
                        },
                        'format' => 'image',
                    ],
                    [
                        'contentOptions' => ['class' => 'td'],
                        'label'=>'Информация',
                        'content'=>function ($model){
                            return Html::tag('p', Html::encode($model->name.Html::encode(", ").$model->category->name.Html::encode(", действует до: ").Yii::$app->formatter->asDate($model->validity_at, 'php:d M')), ['class' => 'mobile-text-for-tabel']);
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {pay}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                return Html::a('<i class="big-i fa fa-external-link" aria-hidden="true"></i> Настройки', Url::to(['/myaccount/magazine/view', 'id' => $model->id]), ['class' => 'btn j-primary m-y-1 btn-magaz']);
                            },
                            'pay' => function ($url, $model, $key) {
                                if($model->validity_at < time()){
                                    return Html::a('<i class="big-i fa fa-usd" aria-hidden="true"></i> Продлить', Url::to(['/myaccount/magazine/pay', 'id' => $model->id]), ['class' => 'btn j-primary m-y-1 btn-magaz btn-magaz-siniy']);
                                }
                            },
                        ],
                    ],
                ],
                'afterRow' => function($model, $key, $index) {
                    return Html::tag('tr',
                        Html::tag('td', '', ['class' => 'tr row-elem'])
                        .Html::tag('td', ($model->validity_at > time()) ? 'Окончание тарифа ' . Yii::$app->formatter->format($model->validity_at, 'relativeTime') : 'Тариф истёк ' . Yii::$app->formatter->format($model->validity_at, 'relativeTime'), ['class' => 'tr row-elem'])
                    );
                },
            ]); ?>
        </div>
    <?else:?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => [
            'class' => 'myoffersnew small active shops-tab_table',
        ],
        'rowOptions' => [
            'class' => 'tr row-elem',
        ],
        'columns' => [
            [
                'contentOptions' => ['class' => 'td text-center'],
                'label'=>'ID',
                'value'=>'id',
            ],
            [
                'contentOptions' => ['class' => 'td'],
                'label'=>'Лого',
                'value'=> function ($model){
                    return $model->image->getUrl();
                },
                'format' => 'image',
            ],
            'name',
            [
                'contentOptions' => ['class' => 'td'],
                'label'=>'Категория',
                'value'=>'category.name',
            ],
            [
                'label'=>'Шаблон',
                'value'=> function ($model){
                    return $model->getTemplates($model->template);
                },
            ],
            [
                'label'=>'Тарифный план',
                'value'=> function ($model){
                    return $model['tarif']['name'];
                },
            ],
            [
                'label'=>'Период',
                'value'=> function ($model){
                    return $model['periodd']['name'];
                },
            ],
            [
                'label'=> 'Действует до',
                'value'=> function ($model){
                    return Yii::$app->formatter->asDate($model->validity_at, 'php:d M');
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {pay}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                         return Html::a('<i class="big-i fa fa-external-link" aria-hidden="true"></i> Управление магазином', Url::to(['/myaccount/magazine/view', 'id' => $model->id]), ['class' => 'btn j-primary m-y-1 btn-magaz']);
                    },
                    'pay' => function ($url, $model, $key) {
                        if($model->validity_at < time()){
                            return Html::a('<i class="big-i fa fa-usd" aria-hidden="true"></i> Продлить', Url::to(['/myaccount/magazine/pay', 'id' => $model->id]), ['class' => 'btn j-primary m-y-1 btn-magaz btn-magaz-siniy']);
                        }
                    },
                ],
            ],
        ],
        'afterRow' => function($model, $key, $index) {
            return Html::tag('tr',
                Html::tag('td', '', ['class' => 'tr row-elem'])
/*                .Html::tag('td', '', ['class' => 'tr row-elem'])
                .Html::tag('td', '', ['class' => 'tr row-elem'])*/
                .Html::tag('td', ($model->validity_at > time()) ? 'Окончание тарифа ' . Yii::$app->formatter->format($model->validity_at, 'relativeTime') : 'Тариф истёк ' . Yii::$app->formatter->format($model->validity_at, 'relativeTime'), ['class' => 'tr row-elem'])
            );
        },
    ]); ?>
    <?endif;?>
</div>

<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>
