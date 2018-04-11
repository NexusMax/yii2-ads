<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\components\SearchWidget;
?>
<div class="background top-all">
<div class="search-block container">
<div class="container col-md-12 p-y-2">
<?= SearchWidget::widget(['model' => $search, 'data' => false]); ?>
</div>
</div>
</div>
<div class="mob-tab-button">
<a href="javascript:;">Все рубрики</a>
</div>
<div class="background p-y-2 p-20 top-all">
<div class="container no_padding wrap-img-vip">
<a class="a-vip a-vip-left" href="https://jandooo.com/category/raznoe/optovye-tovary-po-49-grn">
<img src="/images/opt2-min.png"  alt="">
<span class="span-go span-go-one">Все по <br> <strong>49 грн</strong></span>
</a>
<a class="a-vip a-vip-left a-vip-left-two" href="https://jandooo.com/category/raznoe/optovye-tovary-po-199-grn">
<img src="/images/opt1-min.png"  alt="">
<span class="span-go span-go-two">Все по <br> <strong>199 грн</strong></span>
</a>
<a class="a-vip a-vip-right" href="https://jandooo.com/category/raznoe/optovye-tovary-po-99-grn">
<img src="/images/opt3-min.png"  alt="">
<span class="span-go span-go-three">Все по <br> <strong>99 грн</strong></span>
</a>
<a class="a-vip a-vip-right a-vip-right-two" href="https://jandooo.com/category/raznoe/optovye-tovary-po-49-grn_srQc4L">
<img src="/images/opt4-min.png"  alt="">
<span class="span-go span-go-four">Все по <br> <strong>149 грн</strong></span>
</a>
<div class="col-md-12 no_padding">
<div class="title text-xs-center z-title">
<div class="logo text-xs-left">
<img src="/images/vipmain.png" alt="" />
<h3>VIP</h3>
</div>
<div class="text-xs-right">
<a href="<?= Url::to(['category/view' , 'cat' => 'all-vip']) ?>" class="btn j-primary vip-icon-center">Открыть все VIP-объявления</a>
</div>
</div>
<div class="three vip no_margin">
<?php print_r($vip_ads); ?>
</div>
</div>
</div>
</div>
<div class="background p-y-2 top-all">
<div class="categories-block container no_padding">
<div class="categories">
<?php print_r($categories); ?>  
</div>
</div>
</div>
<div class="background p-y-2">
<div class="new-items container no_padding">
<div class="title mrgn-btm text-xs-center col-md-12">
<div class="logo text-xs-center">
<img src="/images/new.png" alt="" />
<h3>Новые объявления</h3>
</div>
</div>
<div class="hidden-sm">
<div class="custom_top1">
<a href="#"><div class="top1_b">
<img src="/images/tetya.png" class="girls_mov">
<div class="b_text_c">
<div class="b_text_s"><div class="top1_text slide1t"><p>Салон краси</p></div><div class="top1_text slide2t"><p>Нарощування вій</p></div><div class="top1_text slide3t"><p>Покриття гель-лаком</p></div><div class="top1_text slide4t"><p>Манікюр</p></div><div class="top1_text slide5t"><p>Педикюр</p></div><div class="top1_text slide6t"><p>Зачіски/Укладки</p></div></div>
</div>
<p class="reclama_b_top">Реклама</p>
</div></a></div>
</div>
<div class="products container no_padding">
<div class="left1">
<div class="sago-block clearfix"><div class="sago-blockcontent"><div class="custom">
<a href="http://ukladka-plitki.com.ua/repair-m2.html" rel="noindex, nofollow" target="_blank" class="bannerl">
<div class="banner_left">
<div class="sky"></div>
<div class="sun"></div>
<img src="/images/tuchka1.png" class="cloud1"><img src="/images/tuchka2.png" class="cloud2">
<div class="travka"></div>
<div class="building"><div class="window"></div><div class="window"></div><div class="window"></div><div class="window"></div><div class="window"></div><div class="window"></div><div class="window"></div><div class="window"></div><div class="window"></div><div class="window"></div><div class="window"></div><div class="window"></div><div class="window"></div><div class="door"></div></div>
<img src="/images/tree.png" class="tree1">
<div class="asfalt">
</div>
<div class="asfalt1">
</div>
<div class="slide_text">
<div class="text1"><p>Комплексный <br><span class="fist_r2">Ремонт</span><br><span class="fist_r2">Квартир</span></p></div>
<div class="text2"><p><span class="fist_r">Киев</span><br>(063) 799 72 18<br>(097) 002 74 27</p></div>
</div>
</div>
<p class="reclama_b_side_l">Реклама</p>
</a></div></div></div></div>
<div class="three row no_margin">
<?php print_r($last_ads); ?>
</div>
<div class="right1">
<div class="sago-block clearfix"><div class="sago-blockcontent"><div class="custom">
<a href="https://sago-group.com.ua/" rel="noindex, nofollow" target="_blank">
<img src="/images/b1.jpg">
<p class="reclama_b_side">Реклама</p>
</a></div></div></div></div>
</div>
</div>
</div>
<div class="background p-y-2">
<div class="professional container">
<div class="col-md-9 add-shop no_padding">
<?php foreach ($magazineCategories as $key): ?>
<a href="/magazine/shops?category_id=<?= $key['id'] ?>" class="item">
<img src="/web/uploads/magazinecategories/<?= $key['image']; ?>" alt="">
<div class="title"><?= $key['name']; ?></div>
</a>
<?php endforeach; ?>
</div>
<div class="col-md-3 add-shop-button">
<h3>Создайте свой интернет-магазин за 3 минуты</h3>
<p>Портал объявлений 
<?php if('/index' !== Url::current()): ?>
<a href="/">Jandooo.com</a>
<?php else: ?>
<span class="non-a">Jandooo.com</span>
<?php endif; ?>
предлагает Вам создать эффективный интернет-магазин на удобной и доступной платформе и начать зарабатывать уже спустя 10 минут.</p>
<p><a href="<?= Url::to(['magazine/create']) ?>" class="btn j-success">Создать интернет-магазин сейчас</a></p>
<p><a href="<?= Url::to(['magazine/shops']) ?>">Все магазины Jandooo</a></p>
</div></div></div>
<div class="alert-cities p-y-2 background">
<div class="container">
<div class="social_place">
<p class="fb"><a href="https://www.facebook.com/JandoooMarket/" rel="nofollow"><img src="/images/f.png" alt=""></a></p>
<p class="tw"><a href="https://twitter.com" rel="nofollow"><img src="/images/tw.png" alt=""></a></p>
<p class="gp"><a href="https://plus.google.com" rel="nofollow"><img src="/images/g+.png" alt=""></a></p>
</div>
<div class="ads_from_city_all">
<h3>Объявления по регионам</h3>
<?= $reg_ads; ?>
</div></div>
</div>
<div class="content container border-dashed">
<div class="title mrgn-btm title-big index-title">Хотите сделать ваши объявления более эффективными?<br> Читайте эти и другие советы профессионалов на блоге JANDOOO.com</div>
<div class="items-container mt-container">
<div class="row">     
<?php foreach ($blogs as $key): ?>
<div class="col-md-4">
<div class="item-three">
<?php if(!empty($key['image'])): ?><img src="/web/uploads/blog/<?php echo $key['image'] ?>" alt="admin_img"><?php else: ?><img src="/backend/web/images/noimage-min.jpg" alt="admin_img"><?php endif; ?>
<a href="<?= Url::to(['blog/view', 'alias' => $key['alias']]) ?>" class="title"><?= htmlspecialchars_decode($key['name']) ?></a>
<div class="descr"><?= mb_substr(htmlspecialchars_decode($key['intro_text']), 0, 100) . '...' ?></div>
</div>
</div>
<?php endforeach; ?>
</div>
<a href="<?= Url::to('blog/index/') ?>" class="btn j-primary">Читать еще</a>
</div>
<div class="board clearfix">
<div class="title mrgn-btm-12 title-big"><h2>Доска объявлений Украины</h2></div>
<div class="annotation">
<p><img src="/images/advertisment-min.jpg" alt="" /></p>
<p>Эффективные и <strong>бесплатные объявления Украины</strong> на JANDOOO.com позволят Вам как продать, сдать в аренду, так и найти нужный товар или услугу. <strong>Доска объявлений с удобным интерфейсом</strong>, поиском по городам и областям с довольно-таки простым и удобным интерфейсом, что экономит время и приносит максимальный результат. Jandooo - <strong>портал бесплатных объявлений</strong>, на котором быстро продают и легко покупают! Здесь Вы просто и удобно для себя найдете то, что искали! Нажав на кнопку "<a href="<?= Url::to(['ads/create']) ?>">Разместить объявление</a>", вы перейдете на форму, заполнив которую, сможете просто и без всякого дополнительного труда <strong>разместить объявление</strong>, на любую подходящую тематику. Сделать это можно легко и абсолютно <strong>бесплатно</strong>. С помощью <strong>портала Jandooo</strong>&nbsp;сможете купить или продать практически все, что угодно. <strong>Подайте свое объявление</strong> прямо сейчас!</p>
</div>
</div>
<h1>Портал Jandooo - бесплатные объявления Украины</h1>
</div>