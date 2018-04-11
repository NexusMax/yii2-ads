<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\Ads;
use common\models\User;
use backend\models\Categories;
use yii\widgets\ActiveForm;
// echo '<pre>';
// print_r($ads);
// die;
?>
<div class="categories-index">
    <p>
        <?= Html::a('Добавить обьявление', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p>
        <form  action="<?= Url::current() ?>" method="GET">
            <input type="number" name="q" value="<?= Yii::$app->request->get('q') ?>" placeholder="id объявления">
            <input type="text" name="name" value="<?= Yii::$app->request->get('name') ?>" placeholder="Название объявления">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </p>

    <div class="wrap-inputs">
        <div class="table-wrap">
            <div class="table-inner-wrap">
                <div class="table-header">
                    <div class="header-section header-section-info">
                        <input type="checkbox">
                        <span class="header-number-sort">
                            <?= $sort->link('id'); ?>
                        </span>
                    </div>
                    <div class="header-section header-section-photo">Фото</div>
                    <div class="header-section header-section-name">
                        <?= $sort->link('name'); ?>
                    </div>
                    <div class="header-section header-section-promotion">
                        Продвижение
                    </div>
                    <div class="header-section header-section-user">
                        <?= $sort->link('user'); ?>
                    </div>
                    <div class="header-section header-section-category">
                        <?= $sort->link('category'); ?>
                    </div>
                    <div class="header-section header-section-active">
                        <?= $sort->link('active'); ?>
                    </div>
                    <div class="header-section header-section-date">
                        <?= $sort->link('created_at'); ?>
                    </div>
                    <div class="header-section header-section-settings">Действия</div>
                </div>

                <?php foreach ($ads as $key): ?>
                   
                        <div class="table-body">
                            <div class="body-section body-section-info">
                                <span class="body-number-sort"><?php echo $key['id']; ?></span>
                            </div>
                            <div class="body-section body-section-photo">
                                <?= Ads::getImagesForTablesAllAds($key); ?>
                            </div>
                            <div class="body-section body-section-name">
                                <a href="/admin/ads/update?id=<?php echo $key['id']; ?>"><?php echo $key['name'] ?></a>
                            </div>
                            <div class="body-section body-section-promotion">
                                <?php if(!empty($key['oncePromotion'])): ?>
                                    <?php foreach ($key['oncePromotion'] as $valu): ?> 
                                        <?= Html::img('/backend/web/image/promotion/' . \backend\models\AdsHasImage::promotionImage($valu['type']) . '', ['alt' => 'promotion', 'class' => 'email-img-back']) ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>-</p>
                                <?php endif; ?>

                                <?php 
                                if(!empty($key['oncePromotion'])){

                                    $order_ = '<div class="pelat"><div class="prmo-name">Продвижение</div><div class="promot">';
                                    foreach ($key['oncePromotion'] as $key_) {

                                        $dt = $key_['validity_at'] - time();
                                        $day_ = floor( $dt / 86400 );

                                        $order_ .= '(' . \frontend\models\Promotion::getNameTypes()[$key_['type']] . ') - осталось кол-во (' . $day_ . ') дня <br>';
                                    }
                                    $order_ .= '</div></div>';
                                    echo $order_;
                                }
                                ?>
                            </div>
                            <div class="body-section body-section-user">
                                <a class="popup-with-zoom-anim" href="#small-dialog" data-ajax-user-id="<?php echo $key['user_id'] ?>"><?= Html::img('/backend/web/image/email.png', ['alt' => 'Почта', 'class' => 'email-img-back']) ?></a>  
                                <a href="/admin/users/update?id=<?php echo $key['user_id'] ?>"><?php echo $key['user']['username']; ?> <?php echo $key['user']['lastname']; ?></a>  

                                           
                                    <div id="small-dialog" class="zoom-anim-dialog mfp-hide">
                                        <h2>Отправить сообщение</h2>
                                        <?php $form = ActiveForm::begin(); ?>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Имя пользователя</label>
                                                <input type="text" name="username" class="form-control" id="exampleInputEmail1" placeholder="Username">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Емейл адрес пользователя</label>
                                                <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email" value="<?= $key['email'];?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="exampleInputTem">Тема сообщения</label>
                                                <input type="text" name="tema" class="form-control" id="exampleInputTem" placeholder="Text" value="Заголовок сообщения">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="exampleInputMessage">Ваше сообщение</label>
                                                <textarea name="message" id="exampleInputMessage" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="exampleInputSub">Подпись</label>
                                                <input type="text" name="sub" class="form-control" id="exampleInputSub" placeholder="Username" value="C Уважением администрация Jandooo!">
                                            </div>
                                        </div>
                                        <input type="hidden" value="index" name="redirect">
                                        <div class="row">

                                            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary send-main-submit']) ?>
                                        </div>
                                        </form>
                                    <?php ActiveForm::end(); ?>
                                    </div>  
                            </div>
                            <div class="body-section body-section-category">
                                <?php if(!empty($key['category'])): ?>
                                    <?php echo $key['category']['name']; ?>
                                <?php else: ?>
                                    <p>Корневая категория</p>
                                <?php endif; ?>
                                    
                            </div>
                            <div class="body-section body-section-active">
                                <label class="switch">
                                  <input type="hidden" name="ads" value="<?php echo $key['id']; ?>">
                                  <input type="checkbox" name="checkbox_active" value="<?php echo $key['active']; ?>">
                                  <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="body-section body-section-date">
                                <div class="date-create">
                                    <?php echo Yii::$app->formatter->asDate($key['created_at'], 'php:d/m/Y H:i'); //Ads::getDate($key['created_at']); ?>
                                </div>
                                <div class="date-validity">
                                    <?php echo Yii::$app->formatter->asDate($key['validity_at'], 'php:d/m/Y H:i'); ?>
                                </div>
                            </div>
                            <div class="body-section body-section-settings">
                                <a href="/admin/ads/delete?id=<?php echo $key['id']; ?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" class="body-section-settings-delete"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 47.971 47.971" width="20px" height="20px"><path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88   c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242   C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879   s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z" fill="currentColor"></path></svg></a>
                            </div>
                        </div>

                <?php endforeach; ?>
             
            </div>
        </div>
    </div>
<?php
    echo yii\widgets\LinkPager::widget([
        'pagination' => $pages,
    ]);
?>
</div>

