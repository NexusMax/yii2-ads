<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineOrder */

$this->title = 'Добавить заказ';
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index', 'id' => $model->magazine_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-order-create">

    <?= $this->render('_form', [
        'model' => $model,
        'magazines' => $magazines,
    ]) ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>