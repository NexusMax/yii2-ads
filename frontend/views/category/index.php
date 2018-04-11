<?php 
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use \frontend\models\Ads;
use \frontend\models\CourseMoney;
use yii\helpers\Html;

use frontend\components\SearchWidget;
use yii\widgets\Pjax;

// echo '<pre>';
// print_r($ads);
// die;

	if(empty($category['parent_category_name']))
		if(Yii::$app->controller->action->id == 'all-vip')
			$this->params['breadcrumb'][] = 'Вип обьявления';
		else if (Yii::$app->controller->action->id == 'user-ads')
			$this->params['breadcrumb'][] = 'Все обьявления ' . $ads[0]['username'] . ' ' . $ads[0]['lastname'];
		else $this->params['breadcrumb'][] = $category['category_name'];
	else{
		$this->params['breadcrumb'][] = ['label' => $category['parent_category_name'], 'url' => Url::to(['category/view', 'cat' => $category['parent_category_alias']]), 'class' => 'link breadcrumb-link'];
		$this->params['breadcrumb'][] = $category['category_name'];
	}

$current_course = CourseMoney::getCurrentCourse();
$cookies = Yii::$app->request->cookies;

// echo '<pre>';
// print_r($current_course);die;


?>

<?php Pjax::begin([
		// 'enableReplaceState'=>false,
        // 'enablePushState'=>false,
	'clientOptions' => [
			// 'push' => false
		],
	'timeout' => 5000
	]); ?>



<?php 

function getUrlA($ads_ad = null, $course = null, $sort = null){

	$_cat = Yii::$app->controller->action->controller->actionParams['cat'];

	if($ads_ad === 10){
		$ads_ad = Yii::$app->request->get('ads');
	}
	if($course === 10){
		$course = Yii::$app->request->get('course');
	}
	if($sort === 10){
		$sort = Yii::$app->request->get('sort');
	}


	return Url::to([getContr($_cat),
		'cat' => Yii::$app->controller->action->controller->actionParams['cat'],
		'subcat' => Yii::$app->controller->action->controller->actionParams['subcat'],
		'q' => Yii::$app->controller->action->controller->actionParams['q'],
		'reg' => Yii::$app->controller->action->controller->actionParams['reg'],
		'ads' => $ads_ad,
		'img' => Yii::$app->request->get('img'),
		'course' => $course,
		'sprice' => Yii::$app->request->get('sprice'),
		'eprice' => Yii::$app->request->get('eprice'),
		'sort' => $sort
	]);

}
function getContr($_cat = null){
	if(empty($_cat))
		return 'category/index';
	else
		return 'category/view';
}
?>
<div class="background color-b">
<div class="search-block container">
<div class="container col-md-12 p-y-2">
<?= SearchWidget::widget(['model' => $search, 'data' => true, 'category' => $category]); ?>
</div>
</div>
</div>
<section class="tab-section">
<div class="container">
<div class="row">
<div class="col-md-3">
<ul class="nav nav-tabs" role="tablist">
<li role="presentation">
<?php if(empty($_GET['ads'])): ?>
<a href="<?= getUrlA(null, 10, 10) ?>" class="cat active" role="tab" data-toggle="tab">Все</a>
<?php else: ?>
<a href="<?= getUrlA(null, 10, 10) ?>" class="cat">Все</a>
<?php endif; ?>
</li>
<li role="presentation">
<?php if(strcmp($_GET['ads'], 'ch') == 0): ?>
<a href="<?= getUrlA('ch', 10, 10) ?>" class="cat active" role="tab" data-toggle="tab">Частное</a>
<?php else: ?>
<a href="<?= getUrlA('ch', 10, 10) ?>" class="cat">Частное</a>
<?php endif; ?>
</li>
<li role="presentation">
<?php if(strcmp($_GET['ads'], 'bz') == 0): ?>
<a href="<?= getUrlA('bz', 10, 10) ?>" class="cat active" role="tab" data-toggle="tab">Бизнес</a>
<?php else: ?>
<a href="<?= getUrlA('bz', 10, 10) ?>" class="cat">Бизнес</a>
<?php endif; ?>
</li>
</ul>
</div>
<div class="col-md-9">
<div class="order-info">
<div class="section-order">
<p class="val">Валюта:</p>
<?php if(empty($_GET['course'])): ?>
<p class="inactive-link">грн</p>
<?php else: ?>
<a href="<?= getUrlA(10, null, 10) ?>" class="link small-link">грн</a>
<?php endif; ?>
<?php if(strcmp($_GET['course'], 'usd') == 0): ?>
<p class="inactive-link">$</p>
<?php else: ?>
<a href="<?= getUrlA(10, 'usd', 10) ?>" class="link small-link">$</a>
<?php endif; ?>
<?php if(strcmp($_GET['course'], 'eur') == 0): ?>
<p class="inactive-link">€</p>
<?php else: ?>
<a href="<?= getUrlA(10, 'eur', 10) ?>" class="link small-link">€</a>
<?php endif; ?>
</div>
<div class="section-order">
<p class="val">Сортировка:</p>
<?php if(strcmp($_GET['sort'], '-number') == 0 || empty($_GET['sort'])): ?>
<p class="inactive-link">Самые новые</p>
<?php else: ?>
<a href="<?= getUrlA(10, 10, null) ?>" class="link small-link">Самые новые</a>
<?php endif; ?>
<?php if(strcmp($_GET['sort'], 'price') == 0): ?>
<p class="inactive-link">Самые дешевые</p>
<?php else: ?>
<a href="<?= getUrlA(10, 10, 'price') ?>" class="link small-link">Самые дешевые</a>
<?php endif; ?>
<?php if(strcmp($_GET['sort'], '-big-price') == 0): ?>
<p class="inactive-link">Самые дорогие</p>
<?php else: ?>
<a href="<?= getUrlA(10, 10, '-big-price') ?>" class="link small-link">Самые дорогие</a>
<?php endif; ?>
</div>
</div> 
</div>
</div>
</div>
</section>
<section>
<div id="box"></div>
</section>
<section class="sec-bread">
<div class="container">
<div class="row">
<div class="col-md-8">
<?= Breadcrumbs::widget([
'links' => isset($this->params['breadcrumb']) ? $this->params['breadcrumb'] : [],
'tag' => 'ol',
'homeLink' => ['label' => 'Главная', 'url' => '/', 'class' => 'link breadcrumb-link']
]) ?>
</div>
<div class="col-md-4 text-right">
<div class="pt-10">
<div class="section-order">
<p class="val">Вид списка:</p>
<?php 
if(!empty($_GET['list']))
$uri_parts = explode('?list', $_SERVER['REQUEST_URI'], 2);
else {
$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
}
?>
<?php if(empty(Yii::$app->session->get('list'))): ?>
<p class="inactive-link">Список</p>
<a href="<?= $uri_parts[0] ?>" data-list="1" class="link-list link small-link">Галерея</a>
<?php else: ?>
<a href="<?= $uri_parts[0] ?>" data-list="0" class="link-list small-link">Список</a>
<p class="inactive-link">Галерея</p>
<?php endif; ?>
</div>
</div>
</div>
</div>
</div>
</section>
<div class="p-y-2 catalog-block">
<div class="container">
<?php if(!empty($top)): ?>
<div class="row">
<div class="col-md-12 mb-15">
<h2 class="top-h2">Топ-объявления</h2>
</div>
<?php if(!empty(Yii::$app->session->get('list'))): ?>
<div class="products container no_padding">
<div class=" three row no_margin non-owl">
<?php 
$render_html = '';
$row = 3;
$add = new Ads();
?>
<?php endif; ?>
<?php for($i = 0, $j = 1; $i < count($top); $i++, $j++): ?>
<?php 
if(!empty($_GET['course'])){
if(strcmp($_GET['course'], 'usd') == 0)
$price = number_format($top[$i]['price']/$current_course['usd_sale'], 2, ',', ' ') . '$';
else if(strcmp($_GET['course'], 'eur') == 0)
$price = number_format($top[$i]['price']/$current_course['eur_sale'], 2, ',', ' ') . '€';
}
else
$price = $top[$i]['price'] . ' ' . Ads::getTypePayment()[$top[$i]['type_payment']];
if(empty(intval($top[$i]['price'])))
$price = 'Без цены';
?>
<?php 
$vip = '';
		$fire = '';
		$top_ ='';
if(!empty($top[$i]['type'])){

	foreach ($top[$i]['type'] as $key) {
		
		if($key['type'] == 2){
			$vip = ' vip ';
		}
		if($key['type'] == 5){
			$fire = ' fire ';
		}

		if($key['type'] == 3){
			$top_ = ' top ';
		}
	}

}

?>

<?php if(empty(Yii::$app->session->get('list'))): ?>
<div class="col-md-12 product-info">
<div class="row">
<div class="col-md-2 p-r">
<div class="product-img <?= $vip ?> <?= $fire ?>">
<?php if(!empty($fire)): ?>
<p class="img-top-urgently"><span class="img-top-text-urgently">Срочно</span></p>
<?php endif; ?>
<a href="<?= Url::to(['ads/view', 'alias' => $top[$i]['alias']]); ?>">
<?= Ads::getMiniImage($top[$i], $top[$i]['name'], 160, 90); ?>												
</a>
<p class="img-top"></p><span class="img-top-text">ТОП</span> 
</div>
</div>
<div class="col-md-10">
<div class="product-desc">
<div class="row">
<div class="col-md-8">
<div class="product-info-left">
<p><a href="<?= Url::to(['ads/view', 'alias' => $top[$i]['alias']]); ?>" class="product-title"><?= $top[$i]['name']; ?></a></p>
<p class="category"><?= $top[$i]['parent_category_name']; ?> » <?= $top[$i]['category_name']; ?></p>
</div>
<div class="bottom-info">
<p class="mb-0 bottom-p"><span class="siti"><?= $top[$i]['location']; ?></span></p>
<p class="mb-0 bottom-p"><span class="date"><?= Yii::$app->formatter->asDate($top[$i]['created_at'], 'php:d M H:i') ?></span></p>
</div>
</div>
<div class="col-md-4">
<div class="product-info-right">
<strong><?= $price; ?></strong>
<div class="bottom-info">    
<?php if(strcmp($cookies->getValue('ad_' . $top[$i]['id'], 'en'), 'en') !== 0): ?> 
<p class="like"><span class="favorite-text">Удалить из<br/> избранного</span> <a href="#" data-id="<?= $top[$i]['id']; ?>" data-icon="star-filled" class="favorite-out"></a></p>
<?php else: ?>
<p class="like">
<?php if(Yii::$app->user->isGuest): ?>
	<span class="favorite-text"></span><a href="#" data-id="<?= $top[$i]['id']; ?>"  data-guest="1" class="favorite"><i class="fa fa-star-o" aria-hidden="true"></i></a>
<?php else: ?>

<span class="favorite-text">В избранное</span>
<a href="#" data-id="<?= $top[$i]['id']; ?>"  class="favorite">
<i class="fa fa-star-o" aria-hidden="true"></i>
</a>
<?php endif; ?>
</p>
<?php endif; ?>											
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div> 
<?php else: ?>
<?php 
if($j == 1)
$render_html .= '<div class="row five item">';
$top[$i]['price'] = $price;
$render_html .= $add->FullRenderProduct($top[$i], false);
if($j == $row || ($i == $count_ads - 1)){
$render_html .= '</div>';
$j = 0;
}
?>
<?php endif; ?>
<?php endfor; ?>
<?= $render_html ?>
<?php if(!empty(Yii::$app->session->get('list'))): ?>
</div>
</div>
<?php endif; ?>
</div>
<?php endif; ?>
<div class="row">
<div class="col-md-12 mb-15">
<h2 class="top-h2">Обычные-объявления</h2>
</div>
<?php if(!empty(Yii::$app->session->get('list'))): ?>
<div class="products container no_padding">
<div class=" three row no_margin non-owl">
<?php 
$render_html = '';
$row = 5;
$add = new Ads();
?>
<?php endif; ?>
<?php if(!empty($ads)):?>
<?php for($i = 0, $j = 1; $i < count($ads); $i++, $j++): ?>
<?php 
if(!empty($_GET['course'])){
if(strcmp($_GET['course'], 'usd') == 0)
$price = number_format($ads[$i]['price']/$current_course['usd_sale'], 2, ',', ' ') . '$';
else if(strcmp($_GET['course'], 'eur') == 0)
$price = number_format($ads[$i]['price']/$current_course['eur_sale'], 2, ',', ' ') . '€';
}
else
$price = $ads[$i]['price'] . ' ' . Ads::getTypePayment()[$ads[$i]['type_payment']];
if(empty(intval($ads[$i]['price'])))
$price = 'Без цены';
?>
<?php 
$vip = '';
$fire = '';
$top = '';
if(!empty($ads[$i]['type'])){
foreach ($ads[$i]['type'] as $key) {
if($key['type'] == 2){
$vip = ' vip ';
}
if($key['type'] == 5){
$fire = ' fire ';
}
if($key['type'] == 3){
$top = ' top ';
}
}
}
?>
<?php if(empty(Yii::$app->session->get('list'))): ?>
<div class="col-md-12 product-info">
<div class="row">
<div class="col-md-2 p-r">
<div class="product-img <?= $vip ?> <?= $fire ?>">
	<?php if(!empty($fire)): ?>
<p class="img-top-urgently"><span class="img-top-text-urgently">Срочно</span></p>
<?php endif; ?>
<a href="<?= Url::to(['ads/view', 'alias' => $ads[$i]['alias']]); ?>">
<?= Ads::getMiniImage($ads[$i], $ads[$i]['name'], 160, 90); ?>												
</a>
</div>
</div>
<div class="col-md-10">
<div class="product-desc">
<div class="row">
<div class="col-md-8">
<div class="product-info-left">
<p><a href="<?= Url::to(['ads/view', 'alias' => $ads[$i]['alias']]); ?>" class="product-title"><?= $ads[$i]['name']; ?></a></p>
<p class="category"><?= $ads[$i]['parent_category_name']; ?> » <?= $ads[$i]['category_name']; ?></p>
</div>
<div class="bottom-info">
<p class="mb-0 bottom-p"><span class="siti"><?= $ads[$i]['location']; ?></span></p>
<p class="mb-0 bottom-p"><span class="date"><?= Yii::$app->formatter->asDate($ads[$i]['created_at'], 'php:d M H:i') ?></span></p>
</div>
</div>
<div class="col-md-4">
<div class="product-info-right">
<strong><?= $price; ?></strong>
<div class="bottom-info">
	<?php if(Yii::$app->user->isGuest): ?>
		<p class="like"><span class="favorite-text"></span><a href="#"   data-guest="1" class="favorite"><i class="fa fa-star-o" aria-hidden="true"></i></a></p>
	<?php else: ?>
		<?php if(strcmp($cookies->getValue('ad_' . $ads[$i]['id'], 'en'), 'en') !== 0): ?>
			<p class="like"><span class="favorite-text">Удалить из<br> избранного</span> <a href="#" data-id="<?= $ads[$i]['id']; ?>" data-icon="star-filled" class="favorite-out"></a></p>
			<?php else: ?>
			<p class="like"><span class="favorite-text">В избранное</span> <a href="#" data-guest="<?= Yii::$app->user->isGuest; ?>" data-id="<?= $ads[$i]['id']; ?>"  class="favorite"><i class="fa fa-star-o" aria-hidden="true"></i></a></p>
			<?php endif; ?>

	<?php endif;?>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php else: ?>
<?php 
if($j == 1)
$render_html .= '<div class="row five item">';
$ads[$i]['price'] = $price;
$render_html .= $add->FullRenderProduct($ads[$i], false);
?>
<?php endif; ?>
<?php endfor; ?>
<?php else: ?>
<div class="col-md-12">
<h3 class="text-center top-h2">Ничего не найдено</h3>
</div>
<?php endif; ?>
<?= $render_html ?>
<?php if(!empty(Yii::$app->session->get('list'))): ?>
</div>
</div>
<?php endif; ?>
<?php //$pagination->urlManager = Yii::$app->urlManager->createUrl(['category/view', 'par' => '1'])
$pagination->route = getUrlA();unset($_GET['reg']); unset($_GET['cat']); unset($_GET['subcat']);
?>
<?= yii\widgets\LinkPager::widget([
'pagination' => $pagination,
]);?>
</div>
</div>
</div>
<?php Pjax::end(); ?>