<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Периоды';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-period-index">

    <p>
        <?= Html::a('Создать период', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'days',
            'created_at:dateTime',
            'updated_at:dateTime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
