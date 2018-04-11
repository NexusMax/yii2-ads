<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<div class="categories-index">
<style>
    .inl{
        display: inline-block;
    }
</style>
    <div class="container">
        <div class="row">
           <!--  <form  action="<?= Url::current() ?>" method="GET" class="inl">
                <input type="number" name="q" value="<?= Yii::$app->request->get('q') ?>" placeholder="id пользователя">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form> -->
            <p class="inl"><?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?></p>
            <p class="inl"><a class="popup-with-zoom-anim btn btn-primary" href="#small-dialog-price">Массовое пополнение счета</a></p>
            <p class="inl"><a class="popup-with-zoom-anim  btn btn-primary" href="#small-dialog-price-stock">Акция при регистрации</a></p>
            <p class="inl">
                <a href="<?= Url::to(['users/move-users']) ?>" class="btn btn-success">Export для рассылки</a>
            </p>
        </div>

            

    </div>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="wrap-inputs">
        <div class="table-wrap">
            <div class="table-inner-wrap">
                <div class="table-header">
                    <div class="header-section header-section-info">
                        <input type="checkbox">
                        <span class="header-number-sort"><?= $sort->link('id') ?></span>
                    </div>
                    <div class="header-section header-section-name"><?= $sort->link('name') ?></div>
                    <div class="header-section header-section-name">Обьявления автора</div>
                    <div class="header-section header-section-email"><?= $sort->link('email') ?></div>
                    <div class="header-section header-section-reg"><?= $sort->link('created_at') ?></div>
                    <div class="header-section header-section-reg"><?= $sort->link('lastvisit') ?></div>
                    <div class="header-section header-section-reg">Баланс</div>
                    <div class="header-section header-section-status"><?= $sort->link('status') ?></div>
                    <div class="header-section header-section-settings">Действия</div>
                </div>
                
                <?php foreach ($users as $key): ?>

                   
                        <div class="table-body">
                            <div class="body-section body-section-info">
                                <?php echo $key['id']; ?>
                            </div>
                            <div class="body-section body-section-name">
                                <a href="/admin/users/update?id=<?php echo $key['id']; ?>"><?php echo $key['username'] ?> <?php echo $key['lastname'] ?></a>
                            </div>
                             <div class="body-section body-section-name">
                                <a class="popup-with-zoom-anim ads-user" href="#small-dialog-ads-<?= $key['id'] ?>">
                                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                                    <?php if(!empty($key['ads'])): ?>
                                        (<?= count($key['ads']) ?>)
                                    <?php else: ?>
                                        
                                    <?php endif; ?>
                                </a>
                                <div id="small-dialog-ads-<?= $key['id'] ?>" class="zoom-anim-dialog mfp-hide zoom-zoom">

                                    <?php if(!empty($key['ads'])): ?>
                                        <h4>Обьявления автора</h4>
                                        <table class="table table-hover">
                                            <tr>
                                                <th>#</th>
                                                <th>Название</th>
                                                <th>Категория</th>
                                                <th>Просмотры</th>
                                                <th>Дата публикации</th>
                                                <th>Действия</th>
                                            </tr>
                                    <?php foreach ($key['ads'] as $val): ?>
                                        <tr>
                                            <td><?= $val['id'] ?></td>
                                            <td><a href="<?= '/ads/' . $val['alias'] ?>"><?= $val['name'] ?></a></td>
                                            <td><?= $val['category']['name'] ?></td>
                                            <td><?= $val['views'] ?></td>
                                            <td><?= Yii::$app->formatter->asDate($val['created_at'], 'php:d M Y');?></td>
                                            <td>
                                                <a href="/admin/ads/update?id=<?= $val['id']; ?>">Редактировать</a> 
                                                <a href="/admin/ads/delete?id=<?= $val['id']; ?>" data-drop-user-ads="<?= $val['id']; ?>" data-drop-id="<?= $val['id']; ?>" title="Удалить" aria-label="Удалить"  class="body-section-settings-delete small-img-a"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 47.971 47.971" width="20px" height="20px"><path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88   c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242   C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879   s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z" fill="currentColor"></path></svg></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                        </table>
                                    <?php else: ?>
                                        <h4>Обьявлений нет</h4>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="body-section body-section-email">
                                <a class="popup-with-zoom-anim" href="#small-dialog" data-ajax-user-id="<?php echo $key['id'] ?>"><?php echo $key['email']; ?></a> 
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
                                                <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
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
                                        <input type="hidden" value="/users" name="redirect">
                                        <div class="row">
                                            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary send-main-submit']) ?>
                                        </div>
                                        </form>
                                    <?php ActiveForm::end(); ?>
                                    </div>  
                            </div>
                            <div class="body-section body-section-reg"><?php echo Yii::$app->formatter->asDate($key['created_at'], 'php:d/m/Y H:i'); ?></div>
                            <div class="body-section body-section-reg">
                                <?php if(time() - $key['lastvisit'] > 604800):?>
                                <?php echo Yii::$app->formatter->asDate($key['lastvisit'], 'php:d/m/Y H:i'); ?>
                                <?php else: ?>
                                <?= Yii::$app->formatter->format($key['lastvisit'], 'relativeTime') ?>
                                <?php endif; ?>
                                </div>
                            <div class="body-section body-section-reg"><?= $key['balance'] ?></div>
                            <div class="body-section body-section-status">
                                <select class="form-control" data-id="<?php echo $key['id']; ?>" name="user_status">
                                    <?php if($key['ban'] == 0): ?>
                                    <option selected value="0">Активно</option>
                                    <option value="1">Заблокировано</option>
                                    <?php else:?>
                                    <option value="0">Активно</option>
                                    <option selected value="1">Заблокировано</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="body-section body-section-settings">
                                <a href="/admin/users/delete?id=<?php echo $key['id']; ?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" class="body-section-settings-delete"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 47.971 47.971" width="20px" height="20px"><path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88   c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242   C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879   s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z" fill="currentColor"></path></svg></a>
                                <a href="#small-dialog" class="add-balance-user" data-user-id="<?= $key['id'] ?>"><i class="fa fa-usd" aria-hidden="true"></i></a>
                                <div id="small-dialog" class="zoom-anim-dialog mfp-hide">
                                        <h2>Отправить сообщение</h2>
                                        <?php $form = ActiveForm::begin(); ?>
                                        <input type="hidden" name="user_id" value="<?= $key['id'] ?>">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Имя пользователя<?php echo $key['username'] . ' ' . $key['lastname'] ?></label>
                                                <input type="text" name="username" class="form-control" id="exampleInputEmail1" placeholder="Username" value="<?php echo $key['username'] . ' ' . $key['lastname'] ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Емейл адрес пользователя</label>
                                                <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email" value="<?php echo $key['email'] ?>">
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
                                                <label for="exampleInputTem">Сумма</label>
                                                <input type="number" name="price" class="form-control" id="exampleInputTem" placeholder="К-сво" value="<?= $key['balance'] ?>">
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
                                            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary send-send-main-submit']) ?>
                                        </div>
                                        </form>
                                    <?php ActiveForm::end(); ?>
                                    </div>  
                            </div>
                        </div>

                <?php endforeach; ?>
             
            </div>
        </div>
    </div>
</div>


<div id="small-dialog-price" class="zoom-anim-dialog mfp-hide">
    <h2>Пополнить счет</h2>
    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="form-group">
                <label for="exampleInputEmail1">Имя пользователя</label>
                <input type="number" name="price" class="form-control" id="exampleInputEmail1" placeholder=" грн ">
            </div>
        </div>

        <div class="row">
            <?= Html::submitButton('Пополнить', ['class' => 'btn btn-primary price-send']) ?>
        </div>

    
    <?php ActiveForm::end(); ?>
</div> 

<div id="small-dialog-price-stock" class="zoom-anim-dialog mfp-hide">
    <h2>Акция при регистрации</h2>

    <?php if(!empty($stock)): ?>
        <p>Текущая акция:</p>
        <table class="table table-hover">
            <tr>
                <th>#</th>
                <th>Сумма</th>
                <th>Пользователей получили</th>
                <th>По такое число</th>
                <th>Действия</th>
            </tr>
            <tr>
                <td><?= $stock['id'] ?></td>
                <td><?= $stock['sum'] ?></td>
                <td><?= $stock['count'] ?></td>
                <td><?= Yii::$app->formatter->asDate($val['validity_at'], 'php:d M Y H:i');?></td>
                <td>
                    <a href="#" data-stock-id="<?= $stock['id'] ?>" title="Удалить" aria-label="Удалить" class="stock-del"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 47.971 47.971" width="20px" height="20px"><path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88   c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242   C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879   s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z" fill="currentColor"></path></svg></a>
                </td>
            </tr>
        </table>
    <?php endif; ?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="form-group">
            <label for="exampleInputEmail1">Сумма бонусов</label>
            <input type="number" name="price" class="form-control" placeholder=" грн ">
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="exampleInputEmail1">По какое число</label>
            <input type="date" name="validity" class="form-control" >
        </div>
    </div>

        <div class="row">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary price-send-stock']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
    echo yii\widgets\LinkPager::widget([
        'pagination' => $pages,
    ]);
?>