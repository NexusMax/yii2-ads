<?php

use yii\helpers\Html;
$this->title = $model->name;
$this->params['breadcrumbss'][] = ['label' => $this->title, 'url' => ['/myaccount/magazine/view', 'id' => $model->id]];
$this->params['breadcrumbss'][] = 'Обновление';
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-update">

    <?= $this->render('_form', [
        'model' => $model,
	    'plans' => $plans,
	    'periods' => $periods,
	    'categories' => $categories,
        'deliveries' => $deliveries,
        'payments' => $payments,
        'reg' => $reg,
        'city' => $city,
    ]) ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>