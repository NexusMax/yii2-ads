<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineOrder */

$this->title = 'Обновить заказ: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index', 'id' => $model->magazine_id]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="magazine-order-update">

    <?= $this->render('_form', [
        'model' => $model,
        'magazines' => $magazines,
    ]) ?>

</div>
