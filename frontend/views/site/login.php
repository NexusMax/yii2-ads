<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<section class="login-page">

	<h3>Чтобы разместить / подать бесплатное объявление на Jandooo</h3>

	<div class="login-box">

		<div class="login-tabs">
			<nav class="login-tabs__navigation">
				<ul>
					<li><a id="login_tab" class="active" href="#" data-content="login">Войти</a></li>
					<li><a id="register_tab" href="#" data-content="register" class="">Регистрация</a></li>
				</ul>
			</nav>
			<ul class="login-tabs__content">
				<li class="active" data-content="login">

					<div class="help-login">
						<div class="help-login_1">
							<img src="/images/arrow_green.png" alt="" />
							<div>войдите указав емейл или телефон и пароль</div>
						</div>
						<div class="help-login_2">
							<img src="/images/arrow_red.png" alt="" />
							<div>это Ваше первое объявление? - Просто зарегистрируйтесь</div>
						</div>
						<div class="help-login_3">
							<img src="/images/arrow_blue.png" alt="" />
							<div>авторизируйтесь через свой аккаунт социальной сети</div>
						</div>
					</div>

					<div class="login-form">
						
						<?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'default']]); ?>
						<fieldset class="standard-login-box">
							<div class="fblock">
								<div class="focusbox">

									<?= $form->field($model, 'text')->textInput(['class' => 'light required', 'placeholder' => 'Email или номер телефона', 'title' => 'Email или номер телефона'])->label(false) ?>
								
								</div>
							</div>
							<div class="fblock">
								<div class="focusbox">

								<?= $form->field($model, 'password')->passwordInput(['class' => 'light required', 'placeholder' => 'Пароль', 'title' => 'Ваш текущий пароль', 'autocomplete' => 'off', 'oldautocomplete' => 'off'])->label(false) ?>
								
								</div>
							</div>
							<div class="login-form__bottom">
								<div>
								<div class="login-form__rememberme">

									<?= $form->field($model, 'rememberMe')->checkbox() ?>

								</div>

								<?= Html::a('Забыли пароль?', ['site/request-password-reset'], ['class' => 'login-form__lostpassword']) ?>
								</div>

							</div>
							<div class="form-group">

								<?= Html::submitButton('Войти', ['class' => 'btn j-primary btn-login', 'name' => 'login-button']) ?>
								
							</div>
						</fieldset>
						<?php ActiveForm::end(); ?>

						<!-- <a class="login-form__othermethods" href="#">Другие способы входа</a> -->
		<?php 
		if( isset($_SERVER['HTTPS'] ) )
			$redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] .'/site/login-social';
		else
			$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] .'/site/login-social';
		?>

							<script src="//ulogin.ru/js/ulogin.js"></script>
							<div id="uLogin" class="social-login" data-ulogin="display=buttons;theme=flat;fields=first_name,last_name,email;providers=facebook;redirect_uri=<?= $redirect_uri ?>;mobilebuttons=0;">
									
								<div data-uloginbutton="facebook" class="social text"><i class="fa fa-facebook" aria-hidden="true"></i>Вход через Facebook</div>

							</div>

						

					</div>

				</li>
				<li data-content="register">
					<?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'enableClientValidation'=>true, 'validationUrl' => Url::to(['site/ajax']), 'id' => 'form-signup', 'options' => ['class' => 'login-form default']]); ?> 
					<div class="fblock">
						<div class="focusbox">

							<?= $form->field($signup, 'username', ['enableAjaxValidation' => true])->textInput(['class' => 'light required', 'placeholder' => 'Имя', 'title' => 'Укажите Ваше имя'])->label(false) ?>
							
						</div>
					</div>
					<div class="fblock">
						<div class="focusbox">

							<?= $form->field($signup, 'email', ['enableAjaxValidation' => true])->textInput(['class' => 'light required', 'placeholder' => 'Email', 'title' => 'Укажите Ваш email'])->label(false) ?>
							
						</div>
					</div>
					<div class="fblock" id="smsDiv">
						<div class="focusbox">

							<?= $form->field($signup, 'phone', ['enableAjaxValidation' => true])->textInput(['class' => 'light required', 'placeholder' => 'Телефон', 'title' => 'Укажите Ваш телефон'])->label(false) ?>
							
						</div>
					</div>
					<div class="fblock" id="pass1Div">
						<div class="focusbox">

							<?= $form->field($signup, 'password', ['enableAjaxValidation' => true])->passwordInput(['class' => 'light required', 'placeholder' => 'Пароль', 'title' => 'Введите свой пароль', 'autocomplete' => 'off', 'oldautocomplete' => 'off'])->label(false) ?>

							<i data-icon="eye" class=""></i>
						</div>
					</div>
					<div class="form-group">

						<?= Html::submitButton('Регистрация', ['class' => 'btn j-primary', 'name' => 'signup-button']) ?>
						
					</div>
					<?php ActiveForm::end(); ?>
				</li>
			</ul>
		</div>
	</div>

</section>