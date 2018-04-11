<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineAds */

$this->title = 'Создание товара';
$this->params['breadcrumbss'][] = ['label' => $magazine['name'], 'url' => ['/myaccount/magazine/view', 'id' => $magazine['id']]];
$this->params['breadcrumbss'][] = ['label' => 'Товары', 'url' => ['/myaccount/magazine/ads', 'id' => $magazine['id']]];
$this->params['breadcrumbss'][] = $this->title;
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-ads-create">

    <?= $this->render('_form', [
        'model' => $model,
        'images' => $images,
        'old_model' => $old_model,
        'fields' => $fields,
        'params' => $params,
    ]) ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>