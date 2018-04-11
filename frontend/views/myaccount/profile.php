<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

?>

<?= $this->render('_header') ?>


<div>
	<?php if(!empty($ads_ids)): ?>
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
            ['class' => 'yii\grid\SerialColumn'],
            // ['class' => 'yii\grid\CheckboxColumn'],
            [
            	'label' => 'Ид',
            	'value' => 'id',
            ],
            [
            	'label' => 'Объявление',
            	'value' => function ($model) {
            		return Html::a($model['ads']['name'],Url::to(['ads/view', 'alias' => $model['ads']['alias']]));
            	},
            	'format' => 'html',
            ],
            [
            	'label' => 'Тип продвижения',
            	'value' => function ($model) use ($promotionName){
            		return $promotionName[$model['type']];
            	},
            ],
            [
            	'label' => 'Дней',
            	'value' => 'type_time',
            ],
            [
            	'label' => 'Цена',
            	'value' => 'price',
            ], 
            [
            	'label' => 'Оплачено',
            	'value' => function($model){
            		return intval($model['payed']) === 1 ? 'Да' : 'Нет';
            	},
            ],
            [
            	'label' => 'Пакет',
            	'value' => function($model) use ($packageName){
            		return intval($model['package_id']) !== 0 ? $packageName[$model['package']['promotion']] : '-';
            	},
            ],
            [
                'format' => 'html',
                'label' => 'Дата',
                'value' => function( $model ){
                    return 'C: ' . Yii::$app->formatter->asDate($model['created_at'], 'php:d M') . '<br>По: ' . Yii::$app->formatter->asDate($model['validity_at'], 'php:d M');
                },
                'contentOptions' => ['data-label' => 'Дата'],
            ],
        ],
    ]); ?>
	<?php else: ?>
		<div>В историия платежей нет записей</div>
	<?php endif; ?>

</div>


<?= $this->render('_footer') ?>