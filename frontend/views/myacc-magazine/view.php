<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbss'][] = ['label' => $this->title];
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-view">
    
    <?php if($model->validity_at > time()): ?>
    <p>
        <?= Html::a('Настройки магазина', ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
        <?= Html::a('Заказы (' . count($model->orderss) . ')', ['/myaccount/magazine/order', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
        <?= Html::a('Категории', ['/myaccount/magazine/magazine-has-categories', 'id' => $model->id], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::a('Товары', ['/myaccount/magazine/ads', 'id' => $model->id], ['class' => 'btn btn-sm btn-warning']) ?>
        <?= Html::a('Настройки оплаты', ['/myaccount/magazine/payment', 'id' => $model->id], ['class' => 'btn btn-sm btn-warning']) ?>
        <?= Html::a('Тарифный план', ['/myaccount/magazine/pay', 'id' => $model->id], ['class' => 'btn btn-sm btn-warning']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-sm btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'myoffersnew small active', 'style' => 'width:100%'],
        'template' => '<tr class="tr row-elem"><th{captionOptions}>{label}</th><td{contentOptions}>{value}</td></tr>',
        'attributes' => [
            'id',
            'name',
            'alias',
            [
                'contentOptions' => ['class' => 'td'],
                'label'=>'Лого',
                'value'=> $model->image->getUrl(),
                'format' => 'image',
            ],
            [
                'captionOptions' => ['class' => 'td'],
                'contentOptions' => ['class' => 'td'],
                'label'=>'Категория',
                'value'=> $model->category->name,
            ],
            'desc:ntext',
            [
                'captionOptions' => ['class' => 'td'],
                'contentOptions' => ['class' => 'td'],
                'label'=>'Шаблон',
                'value'=> $model->getTemplates($model->template),
            ],
            [
                'captionOptions' => ['class' => 'td'],
                'contentOptions' => ['class' => 'td'],
                'label'=>'Тарифный план',
                'value'=> $model->tarif->name,
            ],
            [
                'captionOptions' => ['class' => 'td'],
                'contentOptions' => ['class' => 'td'],
                'label'=>'Активность',
                'value'=> ($model->active === 1) ? 'Активно' : 'Неактивно',
            ],
            [
                'captionOptions' => ['class' => 'td'],
                'contentOptions' => ['class' => 'td'],
                'label'=>'Период',
                'value'=> $model->periodd->name,
            ],
            'contact',
            [
                'captionOptions' => ['class' => 'td'],
                'contentOptions' => ['class' => 'td'],
                'label'=>'Доставка',
                'value'=> function ($model){
                    return implode(', ',\yii\helpers\ArrayHelper::map($model->getDeliveries()->asArray()->all(), 'id', 'name'));
                },
            ],
            'worked_start_at:time',
            'worked_end_at:time',
            'phone',
            'phone_2',
            'validity_at:dateTime',
            'created_at:dateTime',
        ],
    ]) ?>

    <?php else: ?>
    
    <h3>Время действия магазина закончилось!</h3>
    <?= Html::a('Оплата', Url::to(['/myaccount/magazine/pay', 'id' => $model->id]), ['class' => 'btn j-primary m-y-1 btn-magaz']) ?>

    <?php endif; ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>