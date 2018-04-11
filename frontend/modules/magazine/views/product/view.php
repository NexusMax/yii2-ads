<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">
<h1><?= Html::encode($this->title) ?></h1>
<p>
<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
<?= Html::a('Delete', ['delete', 'id' => $model->id], [
'class' => 'btn btn-danger',
'data' => [
'confirm' => 'Are you sure you want to delete this item?',
'method' => 'post',
],
]) ?>
</p>
<?php $img = $model->getImage(); ?>
<?= DetailView::widget([
'model' => $model,
'attributes' => [
'id',
'name',
'description:html',
'price',
'hot',
[
'attribute' => 'image',
'value' => "<img src='{$img->getUrl()}'>",
'format' => 'html',
],
//'image',
'image_2',
'image_3',
'image_4',
'image_5',
'rating',
'new_arrival',
'best_seller',
'special_offer',
'category_id',
'status',
'keyword:ntext',
],
]) ?>
</div>