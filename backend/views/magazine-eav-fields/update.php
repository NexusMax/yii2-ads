<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineEavFields */

$this->title = 'Обновить доп. поле: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Доп. поля', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="magazine-eav-fields-update">

    <?= $this->render('_form', [
         'model' => $model,
        'categories' => $categories,
        'types' => $types,
    ]) ?>

</div>
