<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazinePrice */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Тарифы планов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-price-view">

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить данный елемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'plan_id',
            'period_id',
            'count_ads',
            'top_30_day',
            'design',
            'price',
            'order',
            'fire',
            'dop_tov',
            'ind_design',
            'per_consult',
            'created_at:dateTime',
            'updated_at:dateTime',
        ],
    ]) ?>

</div>
