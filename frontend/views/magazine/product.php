<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use frontend\models\MagazineEavFields;

$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => Url::to(['magazine/shops'])];
$this->params['breadcrumbs'][] = ['label' => $magazine['name'], 'url' => Url::to(['magazine/view', 'alias' => $magazine['alias']])];
$this->params['breadcrumbs'][] = $ad['name'];
?>
<div class="container mag-view-wr mag-view-header">
<div class="row">
<div class="col-md-4 mag-view-header_col1">
<div class="mag-view-header_logo">
<img src="<?= !empty($magazine->imageRico) ? $magazine->imageRico->getUrl() : Yii::$app->params['placeholder'] ?>" alt="...">
</div>
<div class="mag-view-header_foundation">
<p><img src="/images/jando_icon.png" alt=""><?= 'На Jandooo c ' . Yii::$app->formatter->asDate($magazine['created_at'], 'long') ?></p>
</div>
</div>
<div class="col-md-4 text-center mag-view-header_col2">
<h3 class="magazine-title"><?= $magazine['name'] ?></h3>
</div>
<div class="col-md-4 mag-view-header_col3">
<div class="row">
<div class="mag-view-header_phone col-md-12">
<address><i class="iconer fa fa-phone"></i><?= $magazine['phone'] ?></address>
<address><i class="iconer fa fa-phone"></i><?= $magazine['phone_2'] ?></address>
</div>
<div class="col-md-12 mag-view_working-hours">
<p><i class="iconer fa fa-clock-o"></i>Режим работы:
с <?= $magazine['worked_start_at'] ?>
до <?= $magazine['worked_end_at'] ?></p>
</div>
</div>
</div>
</div>
</div>
<div class="container product-page">
<div class="header-title">
<h1 class="no-margin-top"><?= htmlspecialchars_decode($ad['name']); ?></h1>
</div>
<span><?= '<i class="fa fa-clock-o" aria-hidden="true"></i> ' . Yii::$app->formatter->asDate($ad['created_at'], 'php:d.m.Y H:i') ?> </span><span><?= '<i class="fa fa-refresh" aria-hidden="true"></i> Обновлено: ' . Yii::$app->formatter->asDate($ad['updated_at'], 'php:d.m.Y H:i') ?></span><span><?= '<i class="fa fa-eye" aria-hidden="true"></i> Просмотров: ' . $ad['views'] ?></span><span><?= '<i class="fa fa-bookmark" aria-hidden="true"></i> ID:' . $ad['id'] ?></span>
<div class="product">
<div class="row">
<div class="col-md-8">
<div class="image">
<?php if($main_img['urlAlias'] != 'placeHolder'): ?>
<a data-fancybox="gallery" class="big_img" data-main-pro="1" data-mini-href="<?= $main_img['filePath'] ?>" href="<?= Yii::getAlias('@images') . '/store/' . $main_img['filePath'] ?>">
<img src="<?= $main_img->getUrl() ?>" alt="<?= htmlspecialchars_decode($ad['name']) ?>" />
</a>
<?php else: ?>
<a data-fancybox="gallery" class="big_img" data-mini-href="<?= $images[0]['filePath'] ?>" href="<?= Yii::getAlias('@images') . '/nophoto.jpg' ?>">
<?= Html::img(Yii::getAlias('@images') . '/nophoto.jpg' , ['alt' => 'default']) ?>
</a>
<?php endif; ?>
<div id="owl-carousel-ad" class="owl-carousel owl-theme">
<?php foreach ($images as $key): ?>
<?php if(!$key['isMain']):?>
<div class="item item-img"><a class="mini_img" href="<?= Yii::getAlias('@images') . '/store/' . $key['filePath'] ?>" alt="<?= htmlspecialchars_decode($ad['name']) ?>"><?= \frontend\models\Ads::getMiniImage($key, $ad['name']); ?></a></div>
<?php endif; ?>
<?php endforeach; ?>
</div>
</div>
<?php if(!empty($magazine['deliveriesss']) || !empty($magazine['paymentsss'])):  ?>
<div class="description-block">
<ul>
<?php if(!empty($magazine['deliveriesss'])): ?>
<?php foreach ($magazine['deliveriesss'] as $key): ?>
<li><b>Тип доставки: </b><?= $key['name'] ?></li>
<?php endforeach; ?>
<?php endif; ?>
<?php if(!empty($magazine['paymentsss'])): ?>
<?php foreach ($magazine['paymentsss'] as $key): ?>
<li><b>Тип оплаты: </b><?= $key['name'] ?></li>
<?php endforeach; ?>
<?php endif; ?>

<?php if(!empty($ad['vals'])):?>

<?php echo  MagazineEavFields::getHtmlFieldss($ad, false, $params); ?>

<?php endif; ?>
</ul>
</div>
<?php endif; ?>
<p class='description-text'><?= htmlspecialchars_decode($ad['text']); ?></p>
<?php if(Yii::$app->user->isGuest): ?>
<p><a href="<?= Url::to(['site/login']); ?>">Авторизируйтесь</a>, чтобы написать магазину.</p>
<?php elseif($ad['user_id'] != Yii::$app->user->identity->id): ?>
<h2 class="connect-with-author">Свяжитесь с магазином</h2>
<button class="btn j-primary mbb-20 counter-phone" data-ads-id="<?= $magazine['id'] ?>" data-view="1" data-toggle="collapse" data-target="#contacts2"><i class="fa fa-phone" aria-hidden="true"></i> Показать контакты</button>
<div class="collapse" id="contacts2">
<div class="well white-bgc">
<?php if(!empty($magazine['phone'])): ?>
<a href="tel:<?= $magazine['phone'] ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?= $magazine['phone'] ?></a>
<?php endif;?>
<?php if(!empty($magazine['phone_2'])): ?>
<a href="tel:<?= $magazine['phone_2'] ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?= $magazine['phone_2'] ?></a>
<?php endif;?>
<?php if(!empty($magazine['phone_3'])): ?>
<a href="tel:<?= $magazine['phone_3'] ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?= $magazine['phone_3'] ?></a>
<?php endif;?>
</div>
</div>
<?php endif; ?>
<div class="div-with-">
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'mtt-20']]);?>
<?= $form->field($comments, 'message')->textarea(['class' => 'light required', 'placeholder' => 'Текст сообщения...', 'title' => 'Введите сообщение'])->label(false) ?>
<a href="#" class="mess-add-file">Прикрепить файл</a>
<div class="wrap-inf-mes" style="display: none">
<?= $form->field($comments, 'images')->fileInput(['class'=> 'float-left'])->label(false) ?>
</div>
<div class="form-group float-right"><?= Html::submitButton('<i class="fa fa-envelope-o" aria-hidden="true"></i> Отправить', ['class' => 'btn j-primary']) ?></div>
<div class="product-social_bar">
<ul>
<li><a href="#" class="product-social_bar-item" onclick="Share.facebook('https://jandooo.com/product/<?= $ad['alias'] ?>', '<?= $ad['name'] ?>','<?= $main_img->getUrl() ?>', '<?= $ad['name'] ?>')"><i class="fa fa-facebook-square"></i></a></li>
<li><a href="#" class="product-social_bar-item" onclick="Share.google('https://jandooo.com/product/<?= $ad['alias'] ?>')"><i class="fa fa-google-plus-square"></i></a></li>
<li><a href="#" class="product-social_bar-item" onclick="Share.twitter('https://jandooo.com/product/<?= $ad['alias'] ?>', '<?= $ad['name'] ?>')"><i class="fa fa-twitter-square"></i></a></li>
</ul>
</div>
<?php ActiveForm::end(); ?>
</div>
</div>
<div class="col-md-4">
<div class="p-content">
<div class="span">
<?php 
$price = '<span class="price_name">Цена:</span> <span class="price_value">' . $ad['price'] . ' грн</span>';
?>
<div class="wrap_prices">
<?= $price; ?>
</div>
<div class="fad_city">
<i class="fa fa-map-marker" aria-hidden="true"></i>
<div class="text"><?= $ad['location']; ?></div>
</div>
<div class="fad_map"><iframe style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA4ySfZlbdXq832ilqx-GcIk3tpmfnREHU&q=<?= $ad['location']; ?>" allowfullscreen></iframe></div>
<p>
<button class="btn j-primary counter-phone" data-ads-id="<?= $magazine['id'] ?>" data-view="1" data-toggle="collapse" data-target="#contacts">Показать контакты</button>
<div class="collapse" id="contacts">
<div class="well">
<?php if(!empty($magazine['phone'])): ?>
<a href="tel:<?= $magazine['phone'] ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?= $magazine['phone'] ?></a>
<?php endif;?>
<?php if(!empty($magazine['phone_2'])): ?>
<a href="tel:<?= $amagazined['phone_2'] ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?= $magazine['phone_2'] ?></a>
<?php endif;?>
<?php if(!empty($magazine['phone_3'])): ?>
<a href="tel:<?= $magazine['phone_3'] ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?= $magazine['phone_3'] ?></a>
<?php endif;?>
</div>
</div>
</p>
<?php if(Yii::$app->user->identity->id != $ad['user_id']): ?>
<?php if(!empty(Yii::$app->cart->getPositions()[$ad['id']])): ?>
<p><a href="#" class="btn j-success">В корзине</a></p>
<?php else: ?>
<p><?= Html::a('В корзину', ['cart/add', 'id' => $ad['id']], ['class' => 'btn j-success']) ?></p>
<?php endif; ?>
<?php endif; ?>
</div>
<?php if(Yii::$app->user->isGuest): ?>
<p><a href="<?= Url::to(['site/login']); ?>">Авторизируйтесь</a>, чтобы написать магазину.</p>
<?php else: ?>
<?php if(Yii::$app->user->identity->id != $ad['user_id']): ?>
<button type="button" class="btn j-primary sms-call marginb-7" data-toggle="modal" data-target="#myModal">Написать магазину</button>
<div class="modal fade" tabindex="-1" role="dialog" id="myModal" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title" id="myModalLabel">Написать магазину</h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<form action="#" method="POST">
<textarea name="ad_message" cols="30" rows="10" placeholder="Введите Ваше сообщение"></textarea>
<input type="hidden" name="ads_id" value="<?= $ad['id'] ?>">
<input type="hidden" name="ads_alias" value="<?= $ad['alias'] ?>">
<input type="hidden" name="magazine_id" value="<?= $ad['magazine_id'] ?>">
</form>
</div>
<div class="modal-footer">
<button type="submit" class="btn btn-success otp">Отправить</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
</div>
</div>
</div>
</div>
<?php endif; ?>
<?php endif; ?>
<?php if(!empty($user_ads)): ?>
<h2 class="section-header">Другие товары магазина</h2>
<div class="owl-carousel three owl-car-car">
<div class="owl-item">
<div class="row five item">
<?php foreach ($user_ads as $key): ?>
<div class="item-select new-imagi">
<a href="<?= Url::to(['magazine/product', 'alias' => $key['alias']]) ?>">
<img src="<?= !empty($key->mainImage) ? $key->mainImage->getUrl() : Yii::$app->params['placeholder'] ?>"  alt="<?= $key['name'] ?>">
</a>
<p><a href="<?= Url::to(['magazine/product', 'alias' => $key['alias']]) ?>" title="<?= $key['name'] ?>"><?= $key['name'] ?></a></p>
<p class="ladprice">Цена: <span><?= $key['price'] ?> грн.</span></p>
<div class="additional-info">
<p class="city"><?= $key['location'] ?></p>
<p><a href="<?= Url::to(['magazine/view', 'alias' => $magazine['alias'], 'category_id' => $key['category']['id']]) ?>" title="<?= $key['category']['name'] ?>"><?= $key['category']['name'] ?></a></p>
</div>
</div>
<?php endforeach; ?>
</div>
</div>
</div>
<?php endif; ?>
</div>
</div>
</div>
</div>
<?php if(!empty($like_ads)): ?>
<h2 class="section-header">Похожие объявления</h2>
<div class="owl-carousel three">
<?= $like_ads ?>
</div>
<?php endif; ?>
</div>
<?php \yii\bootstrap\Modal::begin([
'id' => 'user-status',
'size' => 'modal-lg',
'header' => '<h4>Отзывы об авторе</h4>',
'footer' => '<a href="#" data-dismiss="modal" class="btn btn-default">Закрыть</a>'
]);?>
<?php if(empty($userStatus)): ?>
<h4 class="text-center">На данный момент отзывы отсутствуют. Станьте первым.</h4>
<?php else: ?>
<?php foreach ($userStatus as $key): ?>
<p><small><?= Yii::$app->formatter->asDate($key['created_at'], 'php:d M H:i') ?></small> <a href="/category/user-ads/<?= $key['user']['id'] ?>"><?= $key['user']['username'] ?> <?= $key['user']['lastname'] ?></a></p>
<p>
<?php if($key['author_id'] == Yii::$app->user->identity->id): ?>
<i data-id="<?= $key['id'] ?>" style="color: red" class="fa fa-times" aria-hidden="true"></i>
<?php endif; ?>
<?= $key['text'] ?></p>
<?php endforeach; ?>
<?php endif; ?>
<?php if(Yii::$app->user->isGuest): ?>
<p class="text-center">Чтобы написать сообщение, пожалуйста, авторизируйтесь или зарегистрируйтесь</p>
<?php else: ?>
<?php if(Yii::$app->user->identity->id != $ad['user_id']): ?>
<form action="/ads/userstatus" id="user-status-form">
<input type="hidden" name="author_id" value="<?= $ad['user_id'] ?>">
<textarea name="text" style="width: 100%"></textarea>
<button class="btn j-success send-user-status" style="float:right">Отправить</button>
</form>
<?php endif; ?>
<?php endif; ?>
<?php \yii\bootstrap\Modal::end(); ?>