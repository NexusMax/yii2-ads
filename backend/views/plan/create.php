<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MagazinePlan */

$this->title = 'Создание плана';
$this->params['breadcrumbs'][] = ['label' => 'Планы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-plan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
