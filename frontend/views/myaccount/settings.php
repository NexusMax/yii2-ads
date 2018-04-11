<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<?= $this->render('_header') ?>
<div role="tabpanel" >

	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">

			<div class="panel-heading" role="tab" id="headingOne">
				<h4 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
						Изменить контактные данные <i class="fa fa-angle-down" aria-hidden="true"></i>
					</a>
				</h4>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					<?php $form = ActiveForm::begin([]);?>
						<label class="myaccoun-setting_label myaccoun-setting_label-name">Имя</label>
						<?= $form->field($userName, 'name')->textInput(['class' => 'light required myaccoun-setting_input', 'placeholder' => 'Ваше имя', 'title' => 'Ваше имя', 'value' => Yii::$app->user->identity->username])->label(false) ?>
						<label class="myaccoun-setting_label myaccoun-setting_label-lastname">Фамилия</label>
						<?= $form->field($userName, 'lastname')->textInput(['class' => 'light required myaccoun-setting_input', 'placeholder' => 'Ваша фамилия', 'title' => 'Ваша фамилия', 'value' => Yii::$app->user->identity->lastname])->label(false) ?>

						<div class="form-group">

							<?= Html::submitButton('Сохранить', ['class' => 'btn j-primary myaccoun-setting_btn']) ?>

						</div>
						<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingOne">
				<h4 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
						Изменить номер телефона <i class="fa fa-angle-down" aria-hidden="true"></i>
					</a>
				</h4>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					<?php $form = ActiveForm::begin([]);?>
					<label class="myaccoun-setting_label myaccoun-setting_label-name">Номер телефона</label>
					<?= $form->field($updatePhone, 'phone')->textInput(['class' => 'light required myaccoun-setting_input', 'placeholder' => 'Новый телефон', 'title' => 'Новый телефон', 'autocomplete' => 'off', 'oldautocomplete' => 'off', 'value' => Yii::$app->user->identity->phone])->label(false) ?>

					<div class="form-group">

						<?= Html::submitButton('Сохранить', ['class' => 'btn j-primary myaccoun-setting_btn']) ?>

					</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingOne">
				<h4 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePhoto" aria-expanded="true" aria-controls="collapseTwo">
						Изменить фото <i class="fa fa-angle-down" aria-hidden="true"></i>
					</a>
				</h4>
			</div>
			<div id="collapsePhoto" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);?>
					<label class="myaccoun-setting_label myaccoun-setting_label-name">Фото</label>
					<div class="wrap-user-ac-img">
						<img class="user-ac-img" src="<?= Yii::$app->user->identity->image->getUrl() ?>" alt="">
						<?php if(Yii::$app->user->identity->image->id): ?>
							<i class="fa fa-times del-img-ac" aria-hidden="true" data-id="<?= Yii::$app->user->identity->image->id ?>"></i>
						<?php endif; ?>
					</div>
					<?= $form->field($updatePhoto, 'imageFile')->fileInput(['class' => 'light required myaccoun-setting_input'])->label(false) ?>

					<div class="form-group">

						<?= Html::submitButton('Сохранить', ['class' => 'btn j-primary myaccoun-setting_btn']) ?>

					</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>

			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="headingOne">
					<h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
							Изменить пароль <i class="fa fa-angle-down" aria-hidden="true"></i>
						</a>
					</h4>
				</div>
				<div id="collapseThree" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
					<div class="panel-body">
						<?php $form = ActiveForm::begin([]);?>

						<?= $form->field($updatePassword, 'password')->passwordInput(['class' => 'light required myaccoun-setting_input', 'placeholder' => 'Новый пароль', 'title' => 'Новый пароль', 'autocomplete' => 'off', 'oldautocomplete' => 'off'])->label(false) ?>

						<?= $form->field($updatePassword, 'password_2')->passwordInput(['class' => 'light required myaccoun-setting_input', 'placeholder' => 'Повторите новый пароль', 'title' => 'Повторите новый пароль', 'autocomplete' => 'off', 'oldautocomplete' => 'off'])->label(false) ?>

						<div class="form-group">

							<?= Html::submitButton('Сохранить', ['class' => 'btn j-primary myaccoun-setting_btn myaccoun-setting_btn-save']) ?>

						</div>
						<?php ActiveForm::end(); ?>
					</div>
				</div>
			</div>
<!-- 
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="headingOne">
						<h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
								Уведомления <i class="fa fa-angle-down" aria-hidden="true"></i>
							</a>
						</h4>
					</div>
					<div id="collapseFour" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
						<div class="panel-body">
							notify
						</div>
					</div>
				</div> -->
					<!-- <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
									SMS-уведомления <i class="fa fa-angle-down" aria-hidden="true"></i>
								</a>
							</h4>
						</div>
						<div id="collapseFive" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
							<div class="panel-body">
								sms
							</div>
						</div>
					</div> -->
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingOne">
								<h4 class="panel-title">
									<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
										Удалить учетную запись <i class="fa fa-angle-down" aria-hidden="true"></i>
									</a>
								</h4>
							</div>
							<div id="collapseSix" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
								<div class="panel-body">
									<?php $form = ActiveForm::begin([]);?>

									<?= $form->field($deleteAccount, 'hidden')->hiddenInput([])->label(false) ?>

									<div class="form-group">

										<?= Html::submitButton('Удалить', ['class' => 'btn j-primary myaccoun-setting_btn myaccoun-setting_btn-save', 'data' => ['confirm' => 'Вы действительно хотите удалить аккаунт?']]) ?>

									</div>
									<?php ActiveForm::end(); ?>
								</div>
							</div>

						</div>

					
				

			

		


	</div>

</div>
<?= $this->render('_footer') ?>