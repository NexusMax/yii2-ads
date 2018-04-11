<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineSuccessPayed */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Бухгалтерия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-success-payed-view">

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
                'label' => 'Магазин',
                'value' => $model->magazine->name,
            ],
            [
                'label' => 'Пользователь',
                'value' => $model->user->username . ' ' . $model->user->lastname,
            ],
            'sum',
            [
                'label' => 'Тариф',
                'value' => $model->tarif->plan->name . ' (период: ' . $model->tarif->period->name . ')',
            ],
            [
                'label' => 'Индивидуальный шаблон',
                'value' => ($model->individual_template == 1) ? 'Да' : 'Нет',
            ],
            [
                'label' => 'Оплачено',
                'value' => ($model->payed == 1) ? 'Да' : 'Нет',
            ],
            'created_at:dateTime',
            'updated_at:dateTime',
        ],
    ]) ?>

</div>
