<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
// echo '<pre>';
// print_r(Yii::$app->modules['yii2images']['placeHolderPath']);
// print_r(Yii::$app->modules->yii2images->placeHolderPath);
// die;

$this->title = 'Товары';
$this->params['breadcrumbss'][] = ['label' => $magazine['name'], 'url' => ['/myaccount/magazine/view', 'id' => $magazine['id']]];
$this->params['breadcrumbss'][] = $this->title;

?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-ads-index">
    
    <p>
        <span>
            <?= Html::a('Добавить товар', ['/myaccount/magazine/ads/create', 'id' => $magazine['id']], ['class' => 'btn btn-sm j-success']) ?>
        </span>
        <span>
            В Вашем тарифе бесплатных товаров: <?= $count_ads ?>/<?= $count_free_ads ?>. Размещение последующих товаров (<?= $sum_next_ads ?> грн.)
        </span>
    </p>
    <p>
        <span>Срочно: <?= $count_fire ?>/<?= $count_free_fire ?></span>
        <span>Обновлений: <?= $count_update ?>/<?= $count_free_update ?></span>
    </p>

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
            // ['class' => 'yii\grid\SerialColumn'],
            // ['class' => 'yii\grid\CheckboxColumn'],
            // 'id',
            [
                // 'class' => yii\grid\DataColumn::className(),
                'format' => 'html',
                'label' => 'Дата',
                'value' => function( $model ){
                    return 'C: ' . Yii::$app->formatter->asDate($model->created_at, 'php:d M') . '<br>По: ' . Yii::$app->formatter->asDate($model->validity_at, 'php:d M');
                },
                'contentOptions' => ['data-label' => 'Дата'],
            ],
            [
                'label'=>'Изображение',
                'value'=> function( $model ) {
                    return $model->mainImage ? $model->mainImage->getUrl() : Yii::$app->params['placeholder'];
                },
                'format' => ['image',['style'=>'max-width:100px; max-height:100px']],
                'contentOptions' => ['data-label' => 'Фото'],
            ],
            [
                'label'=>'Имя',
                'value'=> 'name',
                'contentOptions' => ['data-label' => 'Имя'],
            ],
            [
                'label'=>'Алиас',
                'value'=> 'alias',
                'contentOptions' => ['data-label' => 'Алиас'],
            ],
            //'name',
            //'alias',
            // [
            //     'label'=>'Категория',
            //     'value'=> function ($model){
            //         return $model->category->name;
            //     }
            // ],
            // 'text:ntext',
            // 'active',
            // 'created_at',
            // 'updated_at',
            // 'validity_at',
            [
                'label'=>'Цена',
                'value'=> function ($model){
                    return $model->price . ' ' . $model->getTypePayment()[$model->type_payment];
                },
                'contentOptions' => ['data-label' => 'Цена'],
            ],
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
                        return Html::a('<span class="span-rel"><i class="big-i fa fa-clone" aria-hidden="true"></i><p>Копировать</p></span>', Url::to(['/myaccount/magazine/ads/copy', 'id' => $model->id]), ['class' => 'pay-a']);
                    },
                    'view' => function($url, $model, $key) {
                        return Html::a('<span class="span-rel"><i class="big-i fa fa-external-link" aria-hidden="true"></i><p>Просмотр</p></span>', Url::to(['/myaccount/magazine/ads/view', 'id' => $model->id]), ['class' => 'pay-a']);
                    },
                    'update' => function ($url, $model, $key) {
                         return Html::a('<span class="span-rel"><i class="big-i fa fa-pencil" aria-hidden="true"></i><p>Редактировать</p></span>', Url::to(['/myaccount/magazine/ads/update', 'id' => $model->id]), ['class' => 'pay-a']);
                    },
                    'delete' => function ($url, $model, $key) {
                         return Html::a('<span class="span-rel"><i class="big-i fa fa-times" aria-hidden="true"></i><p>Удалить</p></span>', Url::to(['/myaccount/magazine/ads/delete', 'id' => $model->id]), ['data-method' => 'POST', 'class' => 'pay-a']);
                    }
                ],
            ],
        ],
        'afterRow' => function($model, $key, $index) {
            return Html::tag('tr',
                Html::tag('td', (!empty($model->fire) ? ' Срочно истекает ' . Yii::$app->formatter->format($model->fire->validity_at, 'relativeTime') : ''), ['class' => 'tr row-elem'])
                .Html::tag('td', '', ['class' => 'tr row-elem'])
                .Html::tag('td', $model->getStatistic(), ['class' => 'tr row-elem myaccount-statistic', 'data-ad-id' => $model->id])
                .Html::tag('td', 'В категории: ' . (empty($model->category->name) ? '(не задано)' : $model->category->name), ['class' => 'tr row-elem'])
                .Html::tag('td', '', ['class' => 'tr row-elem'])
                .Html::tag('td', Html::a('Обновить', ['myaccount/magazine/ads/upd', 'id' => $model->id], ['class' => 'btn btn-sm btn-success']) . ' ' . Html::a('Срочно', ['myaccount/magazine/ads/fire', 'id' => $model->id], ['class' => 'btn btn-sm btn-success']), ['class' => 'tr row-elem'])
            , ['style' => 'background: #f1f1f1;']);
        },
    ]); ?>
</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>