<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineAds */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index', 'id' => $model->magazine_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-ads-view">

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label'=>'Изображение',
                'value'=> $model->image->getUrl(),
                'format' => ['image',['width'=>'100','height'=>'100']],
            ],
            [
                'label'=>'Магазин',
                'value'=> $model->magazin->name,
            ],
            [
                'label'=>'Пользователь',
                'value'=> $model->user->username . ' ' .  $model->user->lastname,
            ],
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
            'updated_at:datetime',
            'validity_at:datetime',
            'price',
            [
                'label'=> $model->attributeLabels()['bargain'],
                'value'=> $model->getBargain()[$model->bargain],
            ],
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
            //     'value'=> $model->getTypeDelivery()[$model->type_delivery],
            // ],
            // 'location',
            // 'phone',
            // 'contact',
            // 'email:email',
            'views',
            // 'number_views',
            // 'phone_2',
            // 'city_id',
            // 'reg_id',
            // 'phone_3',
            // [
            //     'label'=> $model->attributeLabels()['type_ads'],
            //     'value'=> $model->getTypeAds()[$model->type_ads],
            // ],
            [
                'label'=> $model->attributeLabels('publish')['publish'],
                'value'=> $model->getBargain()[$model->publish],
            ],
        ],
    ]) ?>

</div>
