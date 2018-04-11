<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineOrderItem */

$this->title = 'Добавить товар';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index', 'id' => $model->order_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-order-item-create">

    <?= $this->render('_form', [
        'model' => $model,
        'ads' => $ads,
    ]) ?>

</div>
