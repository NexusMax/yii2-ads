<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineHasCategories */

$this->title = $model->name;
$this->params['breadcrumbss'][] = ['label' => $magazine['name'], 'url' => ['/myaccount/magazine/view', 'id' => $magazine['id']]];
$this->params['breadcrumbss'][] = ['label' => 'Категории', 'url' => ['/myaccount/magazine/magazine-has-categories', 'id' => $magazine['id']]];
$this->params['breadcrumbss'][] = $this->title;
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-has-categories-view">

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-sm btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'myoffersnew small active', 'style' => 'width:100%'],
        'template' => '<tr class="tr row-elem"><th{captionOptions}>{label}</th><td{contentOptions}>{value}</td></tr>',
        'attributes' => [
            'id',

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
        ],
    ]) ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>