<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineOrderItem */

$this->title = 'Обновить товар: ' . $model->name;
$this->params['breadcrumbss'][] = ['label' => $magazine['name'], 'url' => ['/myaccount/magazine/view', 'id' => $magazine['id']]];
$this->params['breadcrumbss'][] = ['label' => 'Заказ ' . $order['id'], 'url' => ['/myaccount/magazine/order', 'id' => $order['id']]];
$this->params['breadcrumbss'][] = ['label' => 'Товары', 'url' => ['index', 'id' => $model->order_id]];
$this->params['breadcrumbss'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbss'][] = 'Обовить';
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-order-item-update">

    <?= $this->render('_form', [
        'model' => $model,
        'ads' => $ads,
    ]) ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>
