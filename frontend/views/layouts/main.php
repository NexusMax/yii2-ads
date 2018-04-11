<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
// use frontend\assets\MainAsset;
// use frontend\assets\OptimazeAsset;
use common\widgets\Alert;
use common\widgets\Breadcrumbs;
use yii\helpers\Url;
AppAsset::register($this);
// MainAsset::register($this);
// OptimazeAsset::register($this);
$unread = \frontend\models\Message::getUnreadCount();
if(!empty($unread))
$unread = '(' . $unread . ')';

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<?= Html::csrfMetaTags() ?>
<title><?= Html::encode($this->title) ?></title>
<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<header class="col-md-12">
<div class="logo container no_padding">
<div class="img-logo col-md-3 text-xs-left">
<div class="center">
<img  src="/images/jando_icon.png" alt="Портал обьявлений Jandooo" />
<?php if('/index' !== Url::current()): ?>
<a href="<?= Url::home() ?>"><span class=""><?= Yii::$app->name; ?></span></a>
<?php else: ?>
<span class=""><?= Yii::$app->name; ?></span>
<?php endif; ?>
</div>
</div>
<div class="col-md-9 text-xs-right">
<a href="<?= Url::to(['ads/create']) ?>" class="btn j-success main-menu-link"><span>+</span>Разместить объявление</a>
<a href="<?= Url::to(['magazine/shops']) ?>" class="btn btn-link main-menu-link">Магазины</a>
<a href="<?= Url::to(['/category/index']) ?>" class="btn btn-link main-menu-link">Объявления</a>
<?php 
$header_menu = Yii::$app->cache->get('header_menu');
if($header_menu === false){
$links = '';
if(!empty(Yii::$app->params['pages'])){
foreach (Yii::$app->params['pages'] as $key){
if($key['position'] == 1){
$links .= ' <a href="'. Url::to(['pages/view', 'alias' => $key['alias']]) .'" class="btn btn-link main-menu-link"> '. $key['name'] .' </a> ';
}
}
}
$h_me = $links;
Yii::$app->cache->set('header_menu', $links, 6000);
}else {
$h_me = $header_menu;
}
echo $h_me;
?>
<?php if(Yii::$app->user->isGuest): ?>
<a href="<?= Url::to(['site/login']) ?>" class="btn j-primary">Войти / Регистрация</a>
<?php else: ?>
<div class="wrap-myaccount-menu">
<a href="<?= Url::to(['myaccount/index']) ?>" class="btn j-primary myaccount-link">Личный кабинет <?= $unread ?></a>
<ul class="myaccount-menu">
<li><i class="fa fa-bars" aria-hidden="true"></i> Мой профиль:</li>
<li><a href="<?= Url::to(['myaccount/index']) ?>">Объявления</a></li>
<li><a href="<?= Url::to(['myaccount/magazine']) ?>">Магазины</a></li>
<li><a href="<?= Url::to(['myaccount/messages']) ?>">Сообщения <?= $unread ?></a></li>
<li><a href="<?= Url::to(['myaccount/profile']) ?>">Платежи и счет</a></li>
<li><a href="<?= Url::to(['myaccount/settings']) ?>">Настройки</a></li>
<?php $productInCart = Yii::$app->cart->getCount(); ?>
<li><a href="<?= Url::to(['cart/list']) ?>">Корзина <?= ($productInCart) ? '(' . $productInCart . ')' : '' ?></a></li>
<li class="divider"></li>
<li><a href="<?= Url::to(['myaccount/favorite']) ?>"><i class="fa fa-star" aria-hidden="true"></i> Избранные</a></li>
<li class="myaccount-logout"><a href="<?= Url::to(['site/logout']) ?>"><i class="fa fa-power-off" aria-hidden="true"></i> Выйти</a></li>
</ul>
</div>
<?php endif; ?>
</div>
</div>
</header>
<main>
<div class="container">
<?= Breadcrumbs::widget([
'homeLink' => ['label' => 'Jandooo', 'url' => '/'],
'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>
<?= Alert::widget() ?>

<?php if(Yii::$app->user->identity->ban && strcmp(Yii::$app->controller->id, 'myaccount') === 0): ?>
		<div class="alert-warning alert fade in">
			Ваш аккаунт заблокирован.
		</div>
<?php endif; ?>

</div>
<?= $content ?>
<div class="new_ads_image">
<a href="<?= Url::to(['ads/create']); ?>">
<div class="text">Разместить объявление</div>
<div class="plus">+</div>
</a>
</div>
</main>
<footer>
<div class="container footer-inner">
<div class="row">
<div class="footer_menu col-md-9 row">
<div class="col-md-3">
<p class="title_in_footer">Jandooo.com<?php // $_SERVER['HTTP_HOST'] ?></p>
<?php 
$footer_menu = Yii::$app->cache->get('footer_menu');
if($footer_menu === false){
$links = '';
if(!empty(Yii::$app->params['pages'])){
foreach (Yii::$app->params['pages'] as $key){
if($key['position'] == 2){
$links .= '<a href="'. Url::to(['pages/view', 'alias' => $key['alias']]) .'" class="link-white">'. $key['name'] .'</a>';
}
}
}
$f_me = $links;
Yii::$app->cache->set('footer_menu', $links, 6000);
}else {
$f_me = $footer_menu;
}
echo $f_me;
?>
<a href="<?= Url::to(['/blog/index/']) ?>" class="link-white">Блог</a>
</div>
<?php print_r(\frontend\models\Categories::getHtmlListCategories()); ?>
<div class="col-md-3">
<?php print_r(\frontend\models\Ads::getPopularCity()); ?>
</div>
</div>
<div class="col-md-3">
<div>
<p class="title_in_footer">Письмо администрации</p>
<p class="write_us_intro">Если у Вас есть предложения или замечания по работе проекта - пожалуйста, напишите нам! Мы обязательно рассмотрим Ваше обращение.</p>
</div>
<div class="callBackForm">
<div id="wrap-contact-form127">
<button id="button_admin_message" class="btn j-primary m-y-1">Написать сообщение</button>
</div></div></div></div>
<div class="col-md-8 no_padding">
<div class="ps_text">
<img class="" src="/images/jando_icon.png" alt="" />
© Jandooo - портал объявлений. При использовании сайта, подаче объявлений и оплате услуг. Пользователь предоставляет своё согласие на обработку своих персональных данных и <a href="/rules" class="link-white">принимает уловия оферты</a>. <a href="/information-about-cookies" class="link-white">Информация о cookies</a>.
</div>
</div>
<div class="col-md-4 copyrights">
<p>&nbsp; Все права защищены.</p>
</div>
</div>
</footer>
<?php \yii\bootstrap\Modal::begin([
'id' => 'admin-message',
'size' => 'modal-lg',
'header' => '<h4>Написать сообщение</h4>',
'footer' => '<a href="#" data-dismiss="modal" class=" btn btn-default">Закрыть</a><a href="#" id="send_admin_btn" class=" btn j-success">Отправить</a>'
]); ?>
<form action="/" method="POST" id="form_admin_message_form">
<input type="hidden" name="user_id" value="<?= Yii::$app->user->identity->id ?>">
<input type="text" name="phone" value="<?= Yii::$app->user->identity->phone ?>" placeholder="Введите телефон" required>
<input type="email" name="email" value="<?= Yii::$app->user->identity->email ?>" placeholder="Введите емейл" required>
<textarea name="text" placeholder="Введите сообщение" style="width: 100%"></textarea>
</form>
<?php \yii\bootstrap\Modal::end();?>
<?php $this->endBody() ?>
<noscript id="deferred-styles">
<link rel="stylesheet" type="text/css" href="/css/tether.min.css"/>
<link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.min.css"/>
<link rel="stylesheet" type="text/css" href="/js/assets/owl.carousel.min.css"/>
<link rel="stylesheet" type="text/css" href="/js/assets/owl.theme.default.min.css"/>
<link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css"/>
<link rel="stylesheet" type="text/css" href="/css/jquery.fancybox.min.css"/>
</noscript>
<script>
var loadDeferredStyles = function() {
var addStylesNode = document.getElementById("deferred-styles");
var replacement = document.createElement("div");
replacement.innerHTML = addStylesNode.textContent;
document.body.appendChild(replacement)
addStylesNode.parentElement.removeChild(addStylesNode);
};
var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 0); });
else window.addEventListener('load', loadDeferredStyles);
</script>
<?php if (!isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Speed Insights') === false): ?><script type="text/javascript" > (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter47280237 = new Ya.Metrika({ id:47280237, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://d31j93rd8oukbv.cloudfront.net/metrika/watch_ua.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/47280237" style="position:absolute; left:-9999px;" alt="" /></div></noscript><?php endif; ?>
</body>
</html>
<?php $this->endPage() ?>