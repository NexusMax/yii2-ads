<?php
use yii\helpers\Html;
use backend\models\Pages;
use common\models\User;
use backend\models\Categories;
use yii\widgets\ActiveForm;

?>
<div class="categories-index">
    <!-- <p>
        <?= Html::a('Написать сообщение', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

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
                    <div class="header-section header-section-name">
                       <?= $sort->link('name'); ?>
                    </div>
                    <div class="header-section header-section-name">
                       Дата
                    </div>
                    <div class="header-section header-section-name">
                        Телефон
                    </div>
                    <div class="header-section header-section-name">
                    	<?= $sort->link('user'); ?>
                    </div>

                    <div class="header-section header-section-name">
                        <?= $sort->link('status'); ?>
                    </div>

                    <div class="header-section header-section-name">Действия</div>
                </div>

                <?php foreach ($messages as $key): ?>
                   
                        <div class="table-body">
                            <div class="body-section body-section-info">
                                <?php if(!empty($key['parent'])): ?>
                                    <a href="#" class="child-category" data-id="<?php echo $key['id']; ?>"><i class="fa fa-plus-square"></i></a>
                                <?php endif;?>
                                <span class="body-number-sort"><?php echo $key['id']; ?></span>
                            </div>

                            <div class="body-section body-section-name">
                                <?= htmlspecialchars_decode( $key['text']) ?>
                            </div>
                             <div class="body-section body-section-name">
                                <?= Yii::$app->formatter->asDate($key['created_at'], 'php: d/m/Y H:i'); ?>
                            </div>
                            <div class="body-section body-section-name">
                                <?= $key['phone'] ?>
                            </div>
                            <div class="body-section body-section-name">
                                <a class="add-balance-user" href="#small-dialog" data-ajax-user-id="<?php echo $key['user_id'] ?>">
                                            <?php if(!empty($key['user']['username'])): ?>
                                                <?= $key['user']['username'] ?> <?= $key['user']['lastname'] ?>
                                            <?php elseif(!empty($key['user']['email'])): ?>
                                                <?= $key['user']['email'] ?>
                                            <?php elseif(!empty($key['email'])): ?>
                                                <?= $key['email'] ?>
                                            <?php endif; ?>
                                        
                                    </a>
                                    <div id="small-dialog" class="zoom-anim-dialog mfp-hide">
                                        <h2>Отправить сообщение</h2>
                                        <?php $form = ActiveForm::begin(); ?>
                                        <input type="hidden" name="message_id" value="<?= $key['id'] ?>">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Емейл адрес пользователя</label>
                                                <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email" value="<?php echo $key['email'] ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="exampleInputTem">Тема сообщения</label>
                                                <input type="text" name="tema" class="form-control" id="exampleInputTem" placeholder="Text" value="Администраця портала Jandooo">
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
                                        <input type="hidden" value="/users" name="redirect">
                                        <div class="row">
                                            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary send-send-send-main-submit']) ?>
                                        </div>
                                        </form>
                                    <?php ActiveForm::end(); ?>
                                    </div>  

                            </div>
                            

                            <div class="body-section body-section-name">
                                <?php if(intval($key['unread']) === 1) {echo 'Отвечено';} else {echo 'Не отвечено';} ?>
                            </div>
                            <div class="body-section body-section-name">
                                <a href="/admin/messages/delete?id=<?php echo $key['id']; ?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" class="body-section-settings-delete"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 47.971 47.971" width="20px" height="20px"><path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88   c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242   C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879   s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z" fill="currentColor"></path></svg></a>
                            </div>
                        </div>

                        <?php if(!empty($key['parent'])): ?>
                        <div class="hidden-categories" data-wrap-id="<?php echo $key['id']; ?>">
                            <div class="table-body">
                            <div class="body-section body-section-info">
                                <span class="body-number-sort"><?php echo $key['parent']['id']; ?></span>
                            </div>

                            <div class="body-section body-section-name">
                                <?= htmlspecialchars_decode( $key['parent']['text']) ?>
                            </div>
                            <div class="body-section body-section-name">
                                <?= Yii::$app->formatter->asDate($key['parent']['created_at'], 'php: d/m/Y H:i'); ?>
                            </div>

                            <div class="body-section body-section-user">
                              

                            </div>

                            <div class="body-section body-section-active">
                               
                            </div>
                            <div class="body-section body-section-settings">
                                <a href="/admin/messages/delete?id=<?php echo $key['id']; ?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" class="body-section-settings-delete"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 47.971 47.971" width="20px" height="20px"><path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88   c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242   C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879   s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z" fill="currentColor"></path></svg></a>
                            </div>
                        </div>
                        </div>
                        <?php endif;?>

                <?php endforeach; ?>
             
            </div>
        </div>
    </div>

</div>
<?php
    echo yii\widgets\LinkPager::widget([
        'pagination' => $pages,
    ]);
?>