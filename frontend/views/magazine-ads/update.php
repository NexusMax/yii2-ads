<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineAds */

$this->title = 'Обновление товара: ' . $model->name;

$this->params['breadcrumbss'][] = ['label' => $model->magazin->name, 'url' => ['/myaccount/magazine/view', 'id' => $model->magazin->id]];
$this->params['breadcrumbss'][] = ['label' => 'Товары', 'url' => ['/myaccount/magazine/ads', 'id' => $model->magazin->id]];
$this->params['breadcrumbss'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbss'][] = 'Обновления';
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-ads-update">

    <?= $this->render('_form', [
        'model' => $model,
        'fields' => $fields,
        
        'params' => $params,
    ]) ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>