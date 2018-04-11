<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<?= $this->render('_header', [
'magazineCategories' => $magazineCategories,
'main_categories' => $main_categories,
'dataProvider' => $dataProvider,
'model' => $model,
'city' => $city,
'sort' => $sort,
]) ?>
<div class="container">
<div class="row">
<?php if(!empty($ads)): ?>
<?php foreach ($ads as $key): ?>
<div class="col-sm-4 col-md-4 shop-magazine-view">
<div class="border-a">
<div class="text-center">
<div class="mag-view-header_logo <?php if(!empty($key['fire'])) { echo 'fire'; } ?>">
<img src="<?= !empty($key->mainImage) ? $key->mainImage->getUrl() : Yii::$app->params['placeholder'] ?>" alt="...">
</div>
</div>
<div class="caption">
<a class="title" href="<?= Url::to(['magazine/product', 'alias' => $key['alias']]) ?>"><h3><?= $key['name'] ?></h3></a>
<p class="p-sm caption_price"><?= $key['price'] . ' грн.' ?></p>
<p class="p-sm heasawwa"><?= mb_substr($key['text'], 0, 60) . '..' ?></p>
<p class="cat date p-sm"><?= $key['location'] ?></p>
<p class="cat date p-sm"><?= Yii::$app->formatter->format($key['created_at'], 'relativeTime') . ', в ' . Yii::$app->formatter->asDate($key['created_at'], 'php:H:i')?></p>
</div>
</div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="col-md-12">
<h3 class="text-center top-h2 top-h2-h1">Ничего не найдено</h3>
</div>
<?php endif; ?>
</div>
</div>
<div class="b30"></div>
<?= $this->render('_footer') ?>
