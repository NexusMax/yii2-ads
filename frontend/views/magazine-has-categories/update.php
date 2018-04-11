<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineHasCategories */

$this->title = 'Обновить категорию: ' . $model->name;
$this->params['breadcrumbss'][] = ['label' => $model->magazin->name, 'url' => ['/myaccount/magazine/view', 'id' => $model->magazin->id]];
$this->params['breadcrumbss'][] = ['label' => 'Категории', 'url' => ['/myaccount/magazine/magazine-has-categories', 'id' => $model->magazin->id]];
$this->params['breadcrumbss'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbss'][] = 'Обновить';
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-has-categories-update">

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
    ]) ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>
