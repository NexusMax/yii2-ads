<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Magazine Eav Values';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-eav-value-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Magazine Eav Value', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'field_id',
            'option_id',
            'value',
            'product_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
