<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\MagazinePeriod */

$this->title = 'Создать период';
$this->params['breadcrumbs'][] = ['label' => 'Периоды', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magazine-period-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
