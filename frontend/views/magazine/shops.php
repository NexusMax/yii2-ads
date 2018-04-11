<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\helpers\Markdown;
use common\widgets\Breadcrumbs;

// $this->params['breadcrumbs'][] = ['label' => '', 'url' => Url::to(['category/view', 'cat' => '', 'subcat' => ''])];
$this->params['breadcrumbss-'][] = 'Магазины';
?>
<?php //return ?>
<?php Pjax::begin(['timeout' => 5000]); ?>

<div class="container" style="    padding-top: 23px;">
<?= Breadcrumbs::widget([
'homeLink' => ['label' => 'Jandooo', 'url' => '/'],
'links' => isset($this->params['breadcrumbss-']) ? $this->params['breadcrumbss-'] : [],
]) ?>

</div>

<div class="magazin-page_wrapper">
<div class="container magazin-page_container">
<div class="shop-magazine-wrap">
<div class="search-block container">
<div class="container col-md-12 p-y-2">
<?php echo $this->render('_search', ['magazineCategories' => $magazineCategories, 'city' => $city]); ?>
</div>
</div>
</div>
<div class="container">
<div class="row">
<div class="col-md-12">
<p class="count-mag">Все магазины <span><?= $dataProvider->getCount() ?></span> <a href="<?= Url::to(['magazine/create']) ?>" class="magazine-create btn btn-sm j-success">Открыть магазин</a></p>
</div>
</div>
</div>
<div class="container">
<div class="row">
<div class="col-md-12">
<ul class="magazine-cat">
<?php foreach ($magazineCategories as $key): ?>
<li><a href="/magazine/shops?category_id=<?= $key['id'] ?>"><?= $key['name'] ?></a> <span class="count-cat"><?= $key['count'] ?></span></li>
<?php endforeach ?>
</ul>
</div>
</div>
</div>
<div class="container mt-10">
<?php foreach ($shops as $key): ?>
<div class="row magrin-0 shop-magazine">
<div class="col-sm-6 col-md-2 padding-0 text-center shop-magazine_wrapper-img">
<a href="<?= Url::to(['magazine/view', 'alias' => $key['alias']]); ?>">
<img class="shop-magazine_img" src="<?= !empty($key->imageRico) ? $key->imageRico->getUrl() : Yii::$app->params['placeholder'] ?>" alt="...">
</a>
</div>
<div class="col-sm-6 col-md-10">
<div class="caption shop-caption">
<a class='shop-caption_title' href="<?= Url::to(['magazine/view', 'alias' => $key['alias']]); ?>">
<h3><?= $key['name'] ?></h3>
</a>
<p class="desc shop-caption_desc"><?= mb_substr(strip_tags($key['desc']), 0, 120) . '..' ?></p>
<p><span class="cat"><?= $key['category']['name'] ?></span> <span class="separator-i"></span> <span><a class='shop-caption_count' href="<?= Url::to(['magazine/view', 'alias' => $key['alias']]); ?>"><?= intval($key['count_ads']) . ' товара' ?></a></span></p>
<p><span class="cat"><?= $key['city']['db_defnamelang'] ?></span></p>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
<?php Pjax::end(); ?>