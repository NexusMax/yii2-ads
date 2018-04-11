<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Magazine */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-view">

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Категории', ['/magazine-has-categories', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Товары', ['/magazine-ads', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Заказы', ['/magazine-order', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
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
                'contentOptions' => ['class' => 'td'],
                'label'=>'Лого',
                'value'=> $model->image->getUrl(),
                'format' => 'image',
            ],
            'name',
            'alias',
            [
                'label'=>'Категория',
                'value'=> $model->category->name,
            ],
            [
                'label'=>'Пользователь',
                'value'=> $model->user->username . ' ' .  $model->user->lastname,
            ],
            'desc:ntext',
            [
                'label'=>'Шаблон',
                'value'=> $model->getTemplates($model->template),
            ],
            [
                'label'=>'Тарифный план',
                'value'=> $model->tarif->name,
            ],
            'active',
            [
                'label'=>'Период',
                'value'=> $model->periodd->name,
            ],
            [
                'label'=>'Доставка',
                'value'=> $model->name,
            ],
            'contact',
            'validity_at:dateTime',
            'created_at:dateTime',
            'updated_at:dateTime',
        ],
    ]) ?>

</div>
