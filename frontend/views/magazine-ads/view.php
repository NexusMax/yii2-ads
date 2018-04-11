<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineAds */
$this->title = $model->name;

$this->params['breadcrumbss'][] = ['label' => $model->magazin->name, 'url' => ['/myaccount/magazine/view', 'id' => $model->magazin->id]];
$this->params['breadcrumbss'][] = ['label' => 'Товары', 'url' => ['/myaccount/magazine/ads', 'id' => $model->magazin->id]];
$this->params['breadcrumbss'][] = $this->title;

?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-ads-view">

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
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
            [
                'label'=>'Изображение',
                'value'=> $model->image->getUrl(),
                'format' => ['image',['width'=>'100','height'=>'100']],
            ],
            // [
            //     'label'=>'Магазин',
            //     'value'=> $model->magazin->name,
            // ],
            'alias',
            'name',
            [
                'label'=> $model->attributeLabels()['category_id'],
                'value'=> $model->category->name,
            ],
            'text:ntext',
            [
                'label'=> $model->attributeLabels()['active'],
                'value'=> $model->getBargain()[$model->active],
            ],
            'created_at:datetime',
            // 'validity_at:datetime',
            'price',
            // [
            //     'label'=> $model->attributeLabels()['bargain'],
            //     'value'=> $model->getBargain()[$model->bargain],
            // ],
            // [
            //     'label'=> $model->attributeLabels()['negotiable'],
            //     'value'=> $model->getNegotiable()[$model->negotiable],
            // ],
            // [
            //     'label'=> $model->attributeLabels()['type_payment'],
            //     'value'=> $model->getTypePayment()[$model->type_payment],
            // ],
            // [
            //     'label'=> $model->attributeLabels()['type_delivery'],
            //     'value'=> $model->type_delivery != 0 ? $model->getTypeDelivery()[$model->type_delivery] : '(не указано)',
            // ],
            // 'location',
            // 'contact',
            // 'email:email',
            'views',
            // 'number_views',
            // 'phone',
            // 'phone_2',
            // 'phone_3',
            // [
            //     'label'=> $model->attributeLabels()['type_ads'],
            //     'value'=> $model->type_ads != 0 ? $model->getTypeAds()[$model->type_ads] : '(не указано)',
            // ],
        ],
    ]) ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>