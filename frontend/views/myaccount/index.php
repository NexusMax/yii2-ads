<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;


?>

<?= $this->render('_header', ['search' => $search]) ?>
<div role="tabpanel">

	<div class="row">



		<?php if(empty($ads_id)): ?>
			<div class="content">
				<?php if(!Yii::$app->request->get('SearchMyaccount')['q']):?>
					<i class="fa fa-5x fa-thumb-tack rotate45l" aria-hidden="true"></i>
					<h3 class="x-large2"><?= Yii::$app->params['myaccount']['none'] ?></h3>
					<?php if(Yii::$app->controller->action->id == 'index'): ?>
						<a class="btn j-success" href="<?= Url::to(['ads/create']) ?>">Подать объявление</a>
					<?php endif; ?>
				<?php else: ?>
					<h3 class="x-large2">
						По запросу: "<?= Yii::$app->request->get('SearchMyaccount')['q'] ?>" ничего не найдено
					</h3>
				<?php endif; ?>
				
			</div>
		<?php else: ?>
			<?php print_r($ads); ?>
		<?php endif; ?>

	</div>

</div>


<?php \yii\bootstrap\Modal::begin([
	    'id' => 'after-del',
	    'size' => 'modal-lg',
	    'header' => '<h4>Обьявление было удалено</h4>',
	    'footer' => '<a href="#" data-dismiss="modal" data-value="0" class="after-del-link btn btn-default">Нет</a><a href="#" data-value="1" data-dismiss="modal" class="after-del-link btn btn j-success">Да</a>'
]);?>

<h5>Удалось ли Вам продать товар?</h5>


<?php \yii\bootstrap\Modal::end(); ?>

<?= $this->render('_footer'); ?>