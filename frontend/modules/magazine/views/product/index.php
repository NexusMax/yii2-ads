<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">
<h1><?= Html::encode($this->title) ?></h1>
<p><?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?></p>
<?= GridView::widget([
'dataProvider' => $dataProvider,
'columns' => [
['class' => 'yii\grid\SerialColumn'],
'id',
'name',
// 'description:ntext',
'price',
'hot',
// 'image',
// 'image_2',
// 'image_3',
// 'image_4',
// 'image_5',
'rating',
// 'new_arrival',
// 'best_seller',
// 'special_offer',
[
'attribute' => 'category_id',
'value' => function($data){
return $data->category->name;
},
],
'status',
['class' => 'yii\grid\ActionColumn'],
],
]); ?>
</div>