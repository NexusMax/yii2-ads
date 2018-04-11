<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="magazine-search">
<form class="form-inline search" id="magazine-shop-form" action="/magazine/shops" method="get" data-pjax>
<div class="row">
<div class="col-md-3 padding-right-0">
<select class="form-control dropdown light" name="category_id" aria-required="true">
<option value="">Любая категория</option>
<?php foreach ($magazineCategories as $key): ?>
<option value="<?= $key['id'] ?>" <?= Yii::$app->request->get('category_id') == $key['id'] ? 'selected' : '' ?>><?= $key['name'] ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="col-md-4 padding-0">
<input type="text" id="search-name" class="form-control" name="name" value="<?= Yii::$app->request->get('name') ?>" placeholder="Поиск по магазинам">
</div>
<div class="col-md-3 padding-0">
<select class="form-control dropdown light w100" name="city_id" aria-required="true">
<option value="">Вся Украина</option>
<?php foreach ($city as $key): ?>
<option value="<?= $key['id'] ?>" <?= Yii::$app->request->get('city_id') == $key['id'] ? 'selected' : '' ?>><?= $key['name'] ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="col-md-2 padding-left-0">
<button type="submit" class="btn j-primary">Найти</button>
</div>
</div>
</form>
</div>