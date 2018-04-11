<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MagazinePrice */

$this->title = 'Создать новый тариф';
$this->params['breadcrumbs'][] = ['label' => 'Magazine Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-price-create">

    <?= $this->render('_form', [
        'model' => $model,
        'plans' => $plans,
        'periods' => $periods,
    ]) ?>

</div>
