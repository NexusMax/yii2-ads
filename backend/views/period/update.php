<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazinePeriod */

$this->title = 'Обновление периода: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Периоды', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="magazine-period-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
