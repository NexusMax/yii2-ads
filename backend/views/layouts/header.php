<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */


$last_message = \backend\models\AdminMessage::getLastMessage(5);
$count_unread_message = \backend\models\AdminMessage::getUnreadMessage();

$count_last_ads = \backend\models\Ads::getDayAdsCount();
$last_ads = \backend\models\Ads::getLastAds(5);

$count_last_user = \backend\models\User::getDayUserCount();
$last_user = \backend\models\User::getLastUser(5);
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">JD</span><span class="logo-lg">JANDOOO</span>', '/', ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success"><?= $count_unread_message ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">У Вас <?= intval($count_unread_message) ?> новых сообщения</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <?php foreach ($last_message as $key): ?>
                                <li>
                                    <a href="<?= Url::to(['/messages/index']) ?>">
                                        <div class="pull-left">
                                            <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle"
                                                 alt="User Image"/>
                                        </div>
                                        <h4>
                                         
                                            <?php if(!empty($key['user']['username'])): ?>
                                                <?= $key['user']['username'] ?> <?= $key['user']['lastname'] ?>
                                            <?php elseif(!empty($key['user']['email'])): ?>
                                                <?= $key['user']['email'] ?>
                                            <?php elseif(!empty($key['email'])): ?>
                                                <?= $key['email'] ?>
                                            <?php endif; ?>

                                            
                                            <small><i class="fa fa-clock-o"></i> <?= Yii::$app->formatter->format($key['created_at'], 'relativeTime') ?></small>
                                        </h4>
                                        <p><?= mb_substr(htmlspecialchars_decode($key['text']), 0, 16) . '...' ?></p>
                                    </a>
                                </li>

                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li class="footer"><a class="footer-color" href="<?= Url::to(['/messages']) ?>">Все сообщения</a></li>
                    </ul>
                </li>
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning"><?= $count_last_ads ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">За последние сутки обьявлений: <?= intval($count_last_ads) ?></li>
                        <li>

                            <ul class="menu">
                                <?php foreach ($last_ads as $key): ?>
                                <li>
                                    <a href="<?= Url::to(['ads/update', 'id' => $key['id']]) ?>">
                                        <i class="fa fa-users text-aqua"></i> <?= mb_substr(htmlspecialchars_decode($key['name']), 0, 16) . '...' ?>
                                        <small class="pull-right"><i class="fa fa-clock-o"></i> <?= Yii::$app->formatter->format($key['created_at'], 'relativeTime') ?></small>
                                    </a>
                                </li>
                                <?php endforeach ; ?>
                            </ul>
                        </li>
                        <li class="footer"><a class="footer-color" href="<?= Url::to(['/ads']) ?>">Смотреть все</a></li>
                    </ul>
                </li>

                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user"></i>
                        <span class="label label-danger"><?= $count_last_user ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">За последние сутки пользователей: <?= intval($count_last_user) ?></li>
                        <li>

                            <ul class="menu">
                                <?php foreach ($last_user as $key): ?>
                                <li>
                                    <a href="<?= Url::to(['/users/update', 'id' => $key['id']]) ?>">
                                        <h3>
                                            <?php if(!empty($key['username'])): ?>
                                                <?= $key['username'] ?> <?= $key['lastname'] ?>
                                            <?php elseif(!empty($key['email'])): ?>
                                                <?= $key['email'] ?>
                                            <?php elseif(!empty($key['email'])): ?>
                                                <?= $key['email'] ?>
                                            <?php elseif(!empty($key['phone'])): ?>
                                            <?= $key['phone'] ?>
                                            <?php endif; ?>
                                            <small class="pull-right"><i class="fa fa-clock-o"></i> <?= Yii::$app->formatter->format($key['created_at'], 'relativeTime') ?></small>
                                        </h3>
                                       <!--  <div class="progress xs">
                                            <div class="progress-bar progress-bar-aqua" style="width: 20%"
                                                 role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                 aria-valuemax="100">
                                                <span class="sr-only">20% Complete</span>
                                            </div>
                                        </div> -->
                                    </a>
                                </li>
                                <?php endforeach ; ?>
                            </ul>
                        </li>
                        <li class="footer">
                            <a class="footer-color" href="<?= Url::to(['/users']) ?>">Смотреть всех</a>
                        </li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?php echo Yii::$app->user->identity->username; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle"
                                 alt="User Image"/>

                            <p>

                                <?php echo Yii::$app->user->identity->username; ?>
                                <!-- <small>Member since Nov. 2012</small> -->
                            </p>
                        </li>
                        <!-- Menu Body -->
                       <!--  <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Friends</a>
                            </div>
                        </li> -->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <!-- <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div> -->
                            <div class="pull-right">
                                <?= Html::a(
                                    'Выход',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
            </ul>
        </div>
    </nav>
</header>
