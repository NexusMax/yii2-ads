<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineSuccessPayed */

$this->title = 'Добавить оплату';
$this->params['breadcrumbs'][] = ['label' => 'Бухгалтерия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-success-payed-create">

    <?= $this->render('_form', [
        'model' => $model,
        'tarif' => $tarif,
    ]) ?>

</div>
