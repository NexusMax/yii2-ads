<?php
use yii\helpers\Html;
use backend\models\Pages;
use common\models\User;
use backend\models\Categories;
use yii\widgets\ActiveForm;

use backend\console\SendEmail;

?>
<div class="categories-index">
    <p>
        <?= Html::a('Отправить письмо', ['create'], ['class' => 'btn btn-success']) ?>
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
                    
                    <div class="header-section header-section-name">
                       <?= $sort->link('name'); ?>
                    </div>

					<div class="header-section header-section-name">Текст</div>

					<div class="header-section header-section-name">Дата отправки</div>

                    <div class="header-section header-section-settings">Действия</div>
                </div>

                <?php foreach ($mail as $key): ?>
                   
                        <div class="table-body">
                            <div class="body-section body-section-info">
                                <span class="body-number-sort"><?php echo $key['id']; ?></span>
                            </div>                        

                            <div class="body-section body-section-name">
                                <a href="/admin/blog/update?id=<?php echo $key['id']; ?>"><?php echo $key['subject'] ?></a>
                            </div>

							<div class="body-section body-section-name">
								<?= $key['message'] ?>
 							</div>

 							<div class="body-section body-section-name">
								 <?php echo Yii::$app->formatter->asDate($key['created_at'], 'php:d/m/Y H:i'); //Ads::getDate($key['created_at']); ?>
 							</div>


                            <div class="body-section body-section-settings">
                                <a href="/admin/email/delete?id=<?php echo $key['id']; ?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" class="body-section-settings-delete"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 47.971 47.971" width="20px" height="20px"><path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88   c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242   C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879   s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z" fill="currentColor"></path></svg></a>
                            </div>
                        </div>

                <?php endforeach; ?>


                <?php


                    // Отправляем занание в очередь, и получаем его ID.
                    $id = Yii::$app->queue->push(new SendEMail());

                    // Задание еще находится в очереди.
                    print_r( Yii::$app->queue->isWaiting($id));
                    echo '<br>';
                    // Воркер взял задание из очереди, и выполняет его.
                    print_r( Yii::$app->queue->isReserved($id));
                    echo '<br>';
                    // Воркер уже выполнил задание.
                    print_r( Yii::$app->queue->isDone($id));
                    echo '<br>';

                ?>
             
            </div>
        </div>
    </div>

</div>
<?php
if(!empty(!$pages))
    echo yii\widgets\LinkPager::widget([
        'pagination' => $pages,
    ]);
?>