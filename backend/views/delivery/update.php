<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineDelivery */

$this->title = 'Обновить доставку: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Доставки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="magazine-delivery-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
