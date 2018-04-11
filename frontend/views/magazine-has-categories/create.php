<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineHasCategories */

$this->title = 'Добавить категорию';
$this->params['breadcrumbss'][] = ['label' => $magazine['name'], 'url' => ['/myaccount/magazine/view', 'id' => $magazine['id']]];
$this->params['breadcrumbss'][] = ['label' => 'Категории', 'url' => ['/myaccount/magazine/magazine-has-categories', 'id' => $magazine['id']]];
$this->params['breadcrumbss'][] = $this->title;
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-has-categories-create">

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
    ]) ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>