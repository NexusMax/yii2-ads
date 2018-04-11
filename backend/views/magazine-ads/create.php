<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineAds */

$this->title = 'Создание товара';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-ads-create">

    <?= $this->render('_form', [
        'model' => $model,
        'fields' => $fields,
        'params' => $params,
    ]) ?>

</div>
