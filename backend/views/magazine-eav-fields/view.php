<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineEavFields */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Доп. поля', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-eav-fields-view">

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
            // 'name',
            'name_field',
            'type_id',
            [
                'label' => 'Тип',
                'value' => $model->type_id,
            ],
            'category_id',
            [
                'label' => 'Категория',
                'value' => $model->category->name
            ],
            'active',
            [
                'label' => 'Активность',
                'value' => $model->active === 1 ? 'Да' : 'Нет',
            ],
            // 'search',
        ],
    ]) ?>

</div>
