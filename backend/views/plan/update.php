<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazinePlan */

$this->title = 'Обновление плана: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Планы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="magazine-plan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
