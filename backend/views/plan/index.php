<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Планы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-plan-index">

    <p>
        <?= Html::a('Создать план', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'active',
            'created_at:dateTime',
            'updated_at:dateTime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
