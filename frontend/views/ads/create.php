<?php

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => Url::to(['myaccount/index'])];
$this->params['breadcrumbs'][] = 'Разместить обьявление';
?>

<?= $this->render('_form', [
	'reg' => $reg,
    'model' => $model,
    'categories' => $categories,
    'user' => $user,
]) ?>
