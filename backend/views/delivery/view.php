<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineDelivery */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Доставки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-delivery-view">

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
            'name',
            'type',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
