<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineEavFields */

$this->title = 'Добавить доп. поле к категории';
$this->params['breadcrumbs'][] = ['label' => 'Доп. поля', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-eav-fields-create">

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'types' => $types,
    ]) ?>

</div>
