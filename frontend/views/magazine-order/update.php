<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineOrder */

$this->title = 'Обновить заказ: ' . $model->id;
$this->params['breadcrumbss'][] = ['label' => $model->magazine->name, 'url' => ['/myaccount/magazine/view', 'id' => $model->magazine->id]];
$this->params['breadcrumbss'][] = ['label' => 'Заказы', 'url' => ['index', 'id' => $model->magazine_id]];
$this->params['breadcrumbss'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbss'][] = 'Обновить';
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-order-update">

    <?= $this->render('_form', [
        'model' => $model,
        'magazines' => $magazines,
    ]) ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>