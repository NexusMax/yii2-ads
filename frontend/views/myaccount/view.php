<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$app = Yii::$app;
?>

<?= $this->render('_header') ?>
<div role="tabpanel" >

	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="messages-top-row clearfix">
			<div class="btn-back"><a href="<?= Url::to(['myaccount/messages']) ?>"><i class="fa fa-chevron-left" aria-hidden="true"></i>Назад</a></div>
<!-- 			<div class="move-to-archive"><a href="#"><i class="fa fa-trash" aria-hidden="true"></i>Переместить в архив</a></div>
			<div class="mark-the-star"><a href="#"><i class="fa fa-star-o" aria-hidden="true"></i>Отметить звездочкой</a></div> -->
		</div>
		<div class="messages-sheet clearfix">


			<?php foreach ($messages as $key):?>
			
			
				<?php if($key['from'] == $my_id) : ?>

					<div class="message-item" style="float: right;">
						<div class="message-item__top clearfix">
							<span class="message-item__name">Ваше сообщение</span>
							<span class="message-item__date">Отправлено: <?= $app->formatter->asDate($key['created_at'], 'php:d M H:i') ?></span>
						</div>
						<div class="message-item__wrapper clearfix" style="background-color: #eaf5d4">
							<p><?= htmlspecialchars_decode($key['message']) ?></p>
						</div>
						<div class="message-item__bottom">
							<?php if(!empty($key['readed_at'])):?>
								<span class="when-read">Прочитано <?= $app->formatter->format($key['readed_at'], 'relativeTime') ?></span>
							<?php else: ?>
								<span class="when-read">Не прочитано</span>
							<?php endif; ?>
						</div>
					</div>

				<?php else : ?>

					<div class="message-item" style="float: left;">
						<div class="message-item__top clearfix">
							<span class="message-item__name"><?= $key['username'] ?></span>
							<span class="message-item__date">Отправлено: <?= $app->formatter->asDate($key['created_at'], 'php:d M H:i') ?></span>
						</div>
						<div class="message-item__wrapper clearfix" style="background-color: #f2f2f2">
							<p><?= htmlspecialchars_decode($key['message']) ?></p>
						</div>
					</div>

				<?php endif; ?>

			<?php endforeach; ?>

		</div>


					<?php $form = ActiveForm::begin([]);?>

					<?= $form->field($new_message, 'message')->textarea(['rows' => '6', 'placeholder' => 'Введите сообщение'])->label(false) ?>
					<div class="form-group">

						<?= Html::submitButton('Отправить', ['class' => 'btn j-primary']) ?>

					</div>
					<?php ActiveForm::end(); ?>


	</div>

</div>
<?= $this->render('_footer') ?>