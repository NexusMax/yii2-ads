<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\OrderP */

$this->title = 'Create Order P';
$this->params['breadcrumbs'][] = ['label' => 'Order Ps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-p-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
