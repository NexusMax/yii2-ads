<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineOrder */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index', 'id' => $model->magazine_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-order-view">

    <p>
        <?= Html::a('Товары', ['/magazine-order-item', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
                'label' => 'Магазин',
                'value' => $model->magazine->name,
            ],
            [
                'label' => 'Пользователь',
                'value' => $model->user->username . ' ' . $model->user->lastname,
            ],
            'phone',
            'address',
            'email:email',
            'notes:ntext',
            [
                'label' => 'Статус',
                'value' => $model->getStatus()[$model->status],
            ],
            'created_at:datetime',
            'updated_at:datetime',
            [
                'label' => 'Товары',
                'value'=> implode(', ',\yii\helpers\ArrayHelper::map($model->getItem()->asArray()->all(), 'id', 'name'))
            ],
            [
                'label' => 'Цена',
                'value' => $model->getAllPrice(),
            ],
        ],
    ]) ?>

</div>
