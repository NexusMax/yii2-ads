<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineOrder */

$this->title = $model->id;
$this->params['breadcrumbss'][] = ['label' => $model->magazine->name, 'url' => ['/myaccount/magazine/view', 'id' => $model->magazine->id]];
$this->params['breadcrumbss'][] = ['label' => 'Заказы', 'url' => ['index', 'id' => $model->magazine_id]];
$this->params['breadcrumbss'][] = $this->title;
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-order-view">

    <p>
        <?= Html::a('Товары', ['/myaccount/magazine/order-item', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
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
                'label' => 'Пользователь',
                'value' => $model->user->username . ' ' . $model->user->lastname,
            ],
            'phone',
            'address',
            'email:email',
            'notes:ntext',
            [
                'label' => 'Статус',
                'value' => $model->getStatus()[$model->status],
            ],
            'created_at:datetime',
            [
                'label' => 'Товары',
                'value'=> implode(', ',\yii\helpers\ArrayHelper::map($model->getItem()->asArray()->all(), 'id', 'name'))
            ],
            [
                'label' => 'Цена',
                'value' => $model->getAllPrice(),
            ],
        ],
    ]) ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>