<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\assets\headerAppAsset;


headerAppAsset::register($this);
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!doctype html>
    <html class="no-js" lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerMetaTag(['http-equiv' => 'x-ua-compatible', 'content' => 'ie=edge']);?>
        <?php $this->registerLinkTag(['rel' => 'icon', 'href' => '/images/icons/favicon.ico']) ?>

        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

    </head>

    <body>
    <?php $this->beginBody() ?>
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="/http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!-- Body main wrapper start -->
    <div class="wrapper home-one">

        <!-- Start of header area -->
        <header class="header-area header-wrapper">
            <div class="header-top-bar black-bg clearfix">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="login-register-area">
                                <ul>
                                <?php if(!Yii::$app->user->isGuest): ?>
                                    <li><a href="/my-account">My account, <?= Yii::$app->user->identity['name']; ?></a></li>
                                    <li><a href="<?= Url::to(['/site/logout']);?>">Logout</a></li>
                                <?php else: ?>
                                    <li><a href="/login">Login</a></li>
                                    <li><a href="/register">Register</a></li>
                                <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 hidden-xs">
                            <div class="social-search-area text-center">
                                <div class="social-icon socile-icon-style-2">
                                    <ul>
                                        <li><a href="/" title="facebook"><i class="fa fa-facebook"></i></a> </li>
                                        <li><a href="/" title="twitter"><i class="fa fa-twitter"></i></a> </li>
                                        <li> <a href="/" title="dribble"><i class="fa fa-dribbble"></i></a></li>
                                        <li> <a href="/" title="behance"><i class="fa fa-behance"></i></a> </li>
                                        <li> <a href="/" title="rss"><i class="fa fa-rss"></i></a> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="sticky-header"  class="header-middle-area">
                <div class="container">
                    <div class="full-width-mega-dropdown">
                        <div class="row">
                            <div class="col-md-2 col-sm-2">
                                <div class="logo ptb-20">
                                    <a href="<?= Url::home();?>"><?= Html::img('/images/logo/logo.png', ['alt' => 'main logo']); ?></a>
                                </div>
                            </div>
                            <div class="col-md-7 col-sm-10 hidden-xs">
                                <nav id="primary-menu">
                                    <ul class="main-menu">
                                        <li class="current"><a class="active" href="<?= Url::to(['/admin']);?>">Home</a></li>
                                        <li class="mega-parent pos-rltv"><a href="<?= Url::to(['category/index']);?>">Категории</a>
                                            <div class="mega-menu-area mma-800">
                                                <ul class="single-mega-item">
                                                    <li><a href="<?= Url::to(['category/index']);?>">Список категорий</a></li>
                                                    <li><a href="<?= Url::to(['category/create']);?>">Добавить категорию</a></li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li class="mega-parent pos-rltv"><a href="<?= Url::to(['product/index']);?>">Товары</a>
                                            <div class="mega-menu-area mma-700">
                                                <ul class="single-mega-item">
                                                    <li><a href="<?= Url::to(['product/index']);?>">Список товаров</a></li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="col-md-3 hidden-sm hidden-xs">
                                <div class="search-box global-table">
                                    <div class="global-row">
                                        <div class="global-cell">
                                            <form action="#">
                                                <div class="input-box">
                                                    <input class="single-input" placeholder="Search anything" type="text">
                                                    <button class="src-btn"><i class="fa fa-search"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- mobile-menu-area start -->
                            <div class="mobile-menu-area">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <nav id="dropdown">
                                                <ul>
                                                    <li><a href="/">Home</a></li>
                                                    <li><a href="/shop">Категории</a>
                                                        <ul class="single-mega-item">
                                                            <li><a href="/shop">Список категорий</a></li>
                                                            <li><a href="/shop">Добавить категорию</a></li>
                                                        </ul>
                                                    </li>
                                                    <li><a href="/shop">Товары</a>
                                                        <ul class="single-mega-item">
                                                            <li><a href="/shop">Список товаров</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--mobile menu area end-->
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- End of header area -->
        <div class="container">
            <?php if(Yii::$app->session->getFlash('success')):?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?= Yii::$app->session->getFlash('success') ?>
                </div>
            <?php endif;?>
            <?= $content; ?>
        </div>

        <!--footer bottom area start-->
        <div class="footer-bottom global-table">
            <div class="global-row">
                <div class="global-cell">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="copyrigth"> Copyright @
                                    <a href="/devitems.com">Devitems</a> All right reserved
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <ul class="payment-support text-right">
                                    <li>
                                        <a href=""><img src="/views/clothing/images/icons/pay1.png" alt="" /></a>
                                    </li>
                                    <li>
                                        <a href=""><img src="/views/clothing/images/icons/pay2.png" alt="" /></a>
                                    </li>
                                    <li>
                                        <a href=""><img src="/views/clothing/images/icons/pay3.png" alt="" /></a>
                                    </li>
                                    <li>
                                        <a href=""><img src="/views/clothing/images/icons/pay4.png" alt="" /></a>
                                    </li>
                                    <li>
                                        <a href=""><img src="/views/clothing/images/icons/pay5.png" alt="" /></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--footer bottom area end-->

    </div>

    <?php $this->endBody() ?>

    </body>
    </html>
<?php $this->endPage() ?>