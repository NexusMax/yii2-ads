<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

if(!empty($ad['parent_category_name'])){
  $this->params['breadcrumbs'][] = ['label' => $ad['parent_category_name'], 'url' => Url::to(['category/view', 'cat' => $ad['parent_category_alias']])];
  $this->params['breadcrumbs'][] = ['label' => $ad['category_name'], 'url' => Url::to(['category/view', 'cat' => $ad['parent_category_alias'], 'subcat' => $ad['category_alias']])];
  $this->params['breadcrumbs'][] = htmlspecialchars_decode($ad['name']);
}else{
  $this->params['breadcrumbs'][] = ['label' => $ad['category_name'], 'url' => Url::to(['category/view', 'cat' => $ad['parent_category_alias'], 'subcat' => $ad['category_alias']])];
  $this->params['breadcrumbs'][] = htmlspecialchars_decode($ad['name']);

}

$cookies = Yii::$app->request->cookies;

?>


<div class="container product-page">

	<div class="header-title">
		<h1 class="no-margin-top"><?= htmlspecialchars_decode($ad['name']); ?></h1>
                <?php if(!empty($model)): ?>
                <?= Html::a('Вернуться к редактированию', Url::to(['/ads/create/', 'model' => $model]), ['data-method' => 'post',]);?>
              <?php endif; ?>
    <?php if(strcmp($cookies->getValue('ad_' . $ad['id'], 'en'), 'en') !== 0): ?>
      <p class="like"><span class="favorite-text">Удалить из<br> избранного</span> <a href="#" data-id="<?= $ad['id']; ?>" data-icon="star-filled" class="favorite-out"></a></p>
    <?php else: ?>
      <p class="like"><span class="favorite-text">В избранное</span> <a href="#" data-guest="<?= Yii::$app->user->isGuest; ?>" data-id="<?= $ad['id']; ?>"  class="favorite"><i class="fa fa-star-o" aria-hidden="true"></i></a></p>
    <?php endif; ?>

  </div>
  <span><?= '<i class="fa fa-clock-o" aria-hidden="true"></i> ' . Yii::$app->formatter->asDate($ad['created_at'], 'php:d.m.Y H:i') ?> </span><span><?= '<i class="fa fa-refresh" aria-hidden="true"></i> Обновлено: ' . Yii::$app->formatter->asDate($ad['updated_at'], 'php:d.m.Y H:i') ?></span><span><?= '<i class="fa fa-eye" aria-hidden="true"></i> Просмотров: ' . $ad['views'] ?></span><span><?= '<i class="fa fa-bookmark" aria-hidden="true"></i> ID:' . $ad['id'] ?></span>

  <div class="product">

    <div class="row">
      <div class="col-md-8">
        <div class="image">
          <a data-fancybox="gallery" class="big_img" data-mini-href="<?= $images[0]['filePath'] ?>" href="<?= Yii::getAlias('@images') . '/store/' . $images[0]['filePath'] ?>">
            <?php if(!empty($images[0]['filePath'])): ?>
            <img src="<?= $_ad->image->getUrl() ?>" alt="<?= htmlspecialchars_decode($ad['name']) ?>" />
          <?php else: ?>
            <?= Html::img(Yii::getAlias('@images') . '/nophoto.jpg' , ['alt' => 'default']) ?>
          <?php endif; ?>
          </a>

          <div id="owl-carousel-ad" class="owl-carousel owl-theme">
            <?php foreach ($images as $key): ?>
              <?php if(!$key['isMain']):?>
                <div class="item item-img"><a class="mini_img" href="<?= Yii::getAlias('@images') . '/store/' . $key['filePath'] ?>" alt="<?= htmlspecialchars_decode($ad['name']) ?>"><?= \frontend\models\Ads::getMiniImage($key, $ad['name']); ?></a></div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>

        </div>
        <?php if(Yii::$app->user->identity->id == $ad['user_id']): ?>
          <div>
            <?= Html::a(
                'Поднять вверх списка', 
                Url::to(['/myaccount/refresh', 'id' => $ad['id']]), 
                [
                    'data-method' => 'post',
                    'class' => 'btn j-primary m-y-1'
                ]);
            ?>
            <?= Html::a(
                        'Рекламировать', 
                        Url::to(['ads/reklama', 'alias' => $ad['alias']]), 
                        [
                            'class' => 'btn j-primary m-y-1'
                    ]);
            ?>
          </div>
        <?php endif; ?>
        

        <div class="description-block">
          <ul>
            <?php if(!empty($ad['negotiable'])): ?>
            <li><b>Цена договорная: </b><?= \frontend\models\Ads::getNegotiable()[$ad['negotiable']];?></li>
            <?php endif; ?>
            <?php if(!empty($ad['type_delivery'])): ?>
            <li><b>Тип доставки: </b><?= \frontend\models\Ads::getTypeDelivery()[$ad['type_delivery']];?></li>
            <?php endif;?>
            <?php if(!empty($ad['bargain'])): ?>
            <li><b>Торг: </b><?= \frontend\models\Ads::getBargain()[$ad['bargain']];?></li>
            <?php endif; ?>
            <?php if(!empty($ad['type_ads'])): ?>
            <li><b>Тип обьявления: </b><?= \frontend\models\Ads::getTypeAds()[$ad['type_ads']];?></li>
            <?php endif; ?>
            <?php if(!empty($ad['location'])): ?>
              <li><b>Месторасположение: </b><?= $ad['location'];?></li>
            <?php endif;?>
            
            <?php print_r($sub_fields); ?>
          </ul>

        </div>
        <p class='description-text'><?= htmlspecialchars_decode($ad['text']); ?></p>
      </div>
      <div class="col-md-4">
        <div class="p-content">
          <div class="span">
                            
                            <?php 
                            if(empty(intval($ad['price'])))
                              $price = '<span class="price_value">Без цены</span>';
                            else
                              $price = '<span class="price_name">Цена:</span> <span class="price_value">' . $ad['price'] . ' грн<br><span class="currency_in_ads">'. number_format($ad['price']/$course['usd_sale'], 2, ',', ' ') .'$ <span class="eur_sale">'. number_format($ad['price']/$course['eur_sale'], 2, ',', ' ') .'€</span></span></span>';
                            ?>
            <div class="wrap_prices">
              <?= $price; ?>
            </div>

            <div class="fad_city">
              <i class="fa fa-map-marker" aria-hidden="true"></i>
              <div class="text"><?= $ad['location']; ?></div>
            </div>


            <div class="fad_map">

              <iframe style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA4ySfZlbdXq832ilqx-GcIk3tpmfnREHU
              &q=<?= $ad['location']; ?>" allowfullscreen></iframe>
            </div>

              <p>

                <button class="btn j-primary counter-phone" data-ads-id="<?= $ad['id'] ?>" data-view="1" data-toggle="collapse" data-target="#contacts">Показать контакты</button>
                <div class="collapse" id="contacts">
                  <div class="well">
                    <?php if(!empty($ad['phone'])): ?>
                      <a href="tel:<?= $ad['phone'] ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?= $ad['phone'] ?></a>
                    <?php endif;?>
                    <?php if(!empty($ad['phone_2'])): ?>
                      <a href="tel:<?= $ad['phone_2'] ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?= $ad['phone_2'] ?></a>
                    <?php endif;?>
                    <?php if(!empty($ad['phone_3'])): ?>
                      <a href="tel:<?= $ad['phone_3'] ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?= $ad['phone_3'] ?></a>
                    <?php endif;?>
                    
                  </div>
                </div>

              </p>
              <p><a class="btn j-primary about-author" href="#">Отзывы об авторе</a></p>
            </div>



            <?php if(Yii::$app->user->isGuest): ?>
              <p><a href="<?= Url::to(['site/login']); ?>">Авторизируйтесь</a>, чтобы написать автору.</p>
            <?php else: ?>
              <?php if(Yii::$app->user->identity->id != $ad['user_id']): ?>

                <button type="button" class="btn j-primary sms-call marginb-7" data-toggle="modal" data-target="#myModal">Написать автору</button>

                <div class="modal fade" tabindex="-1" role="dialog" id="myModal" aria-labelledby="mySmallModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Написать автору обьявления</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      </div>
                      <div class="modal-body">
                        <form action="#" method="POST">
                          <textarea name="ad_message" cols="30" rows="10" placeholder="Введите Ваше сообщение"></textarea>
                          <input type="hidden" name="ads_id" value="<?= $ad['id'] ?>">
                          <input type="hidden" name="ads_alias" value="<?= $ad['alias'] ?>">
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

          </div>
          <div class="p-content">
            <div class="user-inf">
              <p class="user-img">
                <?php if(!empty($user)): ?>
                <?php $user_img =  $user->getImage(); ?>

                <?php if($user_img->filePath !=='noimage-min.jpg'): ?>
                <img src="<?=  $user_img->getUrl('80x80'); ?>" alt="">
                <?php else: ?>
                <i class="fa fa-user-o" aria-hidden="true"></i>
                 <?php endif; ?>
               <?php else: ?>
               <i class="fa fa-user-o" aria-hidden="true"></i>

                 <?php endif; ?>
              </p>
              <p class=""><noindex><a rel="nofollow" href="<?= Url::to(['category/view' , 'cat' => 'user-ads', 'subcat' => $ad['user_id']]) ?>">
                <?php if(empty($ad['username'])): ?>
                  <?= $ad['email'] ?>
                <?php else: ?>
                  <?= $ad['username'] ?>
                <?php endif; ?>
              </a></noindex></p>
              <p><?= '<span class="ind-id-u"><i class="fa fa-bookmark" aria-hidden="true"></i> ID: ' . $user['id'] . '</span>' ?></p>
              <p class="">На <b>JANDOOO</b> c <?php if(!empty($ad['user_create'])): ?><?= Yii::$app->formatter->asDate($ad['user_create'], 'long'); ?><?php endif; ?></p>
            </div>


            <div class="span">
              <p><noindex><a class="btn j-primary marginb-25" rel="nofollow" href="<?= Url::to(['category/view' , 'cat' => 'user-ads', 'subcat' => $ad['user_id']]) ?>">Другие обьявления автора</a></noindex></p>
            </div>

          </div>

        </div>
      </div>

    </div>


    <?php if(Yii::$app->user->isGuest): ?>

    <p><a href="<?= Url::to(['site/login']); ?>">Авторизируйтесь</a>, чтобы написать автору.</p>

    <?php elseif($ad['user_id'] != Yii::$app->user->identity->id): ?>
      
      <h2 class="connect-with-author">Свяжитесь с автором обьявления</h2>
      <button class="btn j-primary mbb-20 counter-phone" data-ads-id="<?= $ad['id'] ?>" data-view="1" data-toggle="collapse" data-target="#contacts2"><i class="fa fa-phone" aria-hidden="true"></i> Показать контакты</button>
        <div class="collapse" id="contacts2">
          <div class="well white-bgc">
            <?php if(!empty($ad['phone'])): ?>
              <a href="tel:<?= $ad['phone'] ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?= $ad['phone'] ?></a>
            <?php endif;?>
            <?php if(!empty($ad['phone_2'])): ?>
              <a href="tel:<?= $ad['phone_2'] ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?= $ad['phone_2'] ?></a>
            <?php endif;?>
            <?php if(!empty($ad['phone_3'])): ?>
              <a href="tel:<?= $ad['phone_3'] ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?= $ad['phone_3'] ?></a>
            <?php endif;?>
            
          </div>
        </div>

        <div class="div-with">
      <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'mtt-20']]);?>

      <?= $form->field($comments, 'message')->textarea(['class' => 'light required', 'placeholder' => 'Текст сообщения...', 'title' => 'Введите сообщение'])->label(false) ?>
      
      <a href="#" class="mess-add-file">Прикрепить файл</a>
      <div class="wrap-inf-mes" style="display: none">
         <?= $form->field($comments, 'images')->fileInput(['class'=> 'float-left'])->label(false) ?>
      </div>
     
      <div class="form-group float-right"><?= Html::submitButton('<i class="fa fa-envelope-o" aria-hidden="true"></i> Отправить', ['class' => 'btn j-primary']) ?></div>

 
      <?php ActiveForm::end(); ?>
</div>
      
    <?php endif; ?>

    <div class="product-social_bar">
        <p class="red-share">Поделиться в соц. сетях:</p>
		<ul>
			<li>
				<a href="#" class="product-social_bar-item" 
				onclick="Share.facebook('https://jandooo.com/ads/<?= $ad['alias'] ?>', '<?= $ad['name'] ?>', '<?=  $_ad->image->getUrl() ?>', '<?= $ad['name'] ?>')">
					<i class="fa fa-facebook-square"></i>
				</a>
			</li>
			<li><a href="#" class="product-social_bar-item" onclick="Share.google('https://jandooo.com/ads/<?= strip_tags($ad['alias']) ?>')"><i class="fa fa-google-plus-square"></i></a></li>
			<li><a href="#" class="product-social_bar-item" onclick="Share.twitter('https://jandooo.com/ads/<?= $ad['alias'] ?>', '<?= strip_tags($ad['name']) ?>')"><i class="fa fa-twitter-square"></i></a></li>
		</ul>
	</div>


    <?php if(!empty($user_ads)): ?>
     <h2 class="section-header">Другие объявления автора</h2>
     <div class="owl-carousel three owl-car">
      <?= $user_ads ?>
    </div>
  <?php endif; ?>

  <?php if(!empty($like_ads)): ?>
   <h2 class="section-header">Похожие объявления</h2>
   <div class="owl-carousel three owl-car">
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



