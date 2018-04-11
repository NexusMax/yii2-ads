<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineHasCategories */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-has-categories-view">

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
                'label'=>'Изображение',
                'value'=> $model->image->getUrl(),
                'format' => ['image',['style'=>'max-width:100px; max-height:100px']],
            ],
            [
                'label'=>'Магазин',
                'value'=> $model->magazin->name,
            ],
            [
                'label'=>'Категория',
                'value'=> empty($model->parent->name) ? 'Главная категория' : $model->parent->name,
            ],
            'alias',
            'name',
            [
                'label'=>'Активность',
                'value'=> ($model->active === 1) ? 'Активно' : 'Неактивно',
            ],
            'sort',
            'created_at:dateTime',
            'updated_at:dateTime',
        ],
    ]) ?>

</div>
