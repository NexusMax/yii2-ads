<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => Url::to(['myaccount/index'])];
$this->params['breadcrumbs'][] = ['label' => htmlspecialchars_decode($ad['name']), 'url' => ['view', 'alias' => $ad['alias']]];
$this->params['breadcrumbs'][] = 'Рекламировать';

?>
<!-- <div class="container">
	<div class="row">
		<div class="col-md-12">
			<h3 class="text-center">К сожалению, рекламировать объявление на данный момент невозможно. Данная услуга будет доступна в ближайшее время.</h3>
		</div>
	</div>
</div> -->
<?php //return ?>
			<?php $form = ActiveForm::begin([
				'fieldConfig' => [
	        		'template' => "{input}",
	        		'options' => [
                        'tag' => false,
                    ],
	        	],
	        	'id' => 'PaymentForm',
	        	'enableClientValidation' => false,
				]);?>

				<input type="hidden" name="category_id" value="<?= $ad['category_id'] ?>">
				<input type="hidden" name="sub_category_id" value="<?= $ad['parent_category_id'] ?>">
				<input type="hidden" name="sub_sub_category_id" value="<?= $ad['parent_parent_category_id'] ?>">
				<input type="hidden" name="start" value="<?= $current_course['start'] ?>">
				<input type="hidden" name="medium" value="<?= $current_course['medium'] ?>">
				<input type="hidden" name="full" value="<?= $current_course['full'] ?>">
				<input type="hidden" name="select_up" value='<?= json_encode($current_price['up']) ?>'>
				<input type="hidden" name="select_vip" value='<?= json_encode($current_price['vip']) ?>'>
				<input type="hidden" name="select_top" value='<?= json_encode($current_price['top_']) ?>'>
				<input type="hidden" name="select_fire" value='<?= json_encode($current_price['fire']) ?>'>
				<input type="hidden" name="select_once" value='<?= json_encode($current_price['once_up']) ?>'>



			<div class="p-y-2">
				<div class="container no_padding">
					<div class="row">
						<div class="col-md-12">
							<h3 class="s-title-item"><?= htmlspecialchars_decode($ad['name']) ?></h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="line-with-circle">
								<div class="blue-circle">1</div>
								<span>Выберите рекламные услуги</span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h4 class="s-title">Попробуйте новые наборы услуг в готовых пакетах</h4>
						</div>
					</div>
					<div class="row s-items">
						<div class="col-md-4"> <!-- item1 -->
							<div class="s-item">
								<div class="s-item__title">
									<h3>Легкое начало <a href="#" class="s-item__title-info-circle"><i data-toggle="tooltip" data-placement="right" title="C этим пакетом Вы получите топ-объявление на 3 дня и срочно до окончания пакета" class="fa fa-info-circle" aria-hidden="true"></i></a></h3>	
									<p> </p>
								</div>
								<ul>
									<li><img src="/images/up.png" alt=""><span>Топ-объявление на <strong>3</strong> дня</span></li>
									<li><img src="/images/immid.png" alt=""><span>Срочно до окончания пакета</span></li>
									<!-- <li>VIP-объявление</li> -->
								</ul>
								<div class="s-item__price"><?= $current_course['start'] ?> <span class="s-item__price-currency">грн.</span></div>
								<?= $form->field($model, 'promotion[]')->checkbox(['label' => false, 'style'=> 'display:none;'])->label(false) ?>
								<button data-package="start" class="btn j-primary s-item__btn">Выбрать</button>
							</div>							
						</div>
						<div class="col-md-4"> <!-- item2 -->
							<div class="s-item">
								<div class="s-item__title">
									<h3>Золотая середина<a href="#" class="s-item__title-info-circle"><i data-toggle="tooltip" data-placement="right" title="C этим пакетом Вы получите топ-объявление на 7 дней, поднятие в верх на протяжении 3 дней и срочно до окончания пакета" class="fa fa-info-circle" aria-hidden="true"></i></a></h3>	
									<p><i style="color:green; font-style: normal;">14х больше</i> просмотров</p>
								</div>
								<ul>
									<li><img src="/images/up.png" alt=""><span>Топ-объявление на <strong>7</strong> дня</span></li>
									<li><img src="/images/upp.png" alt=""><span>3 поднятия в верх</span></li>
									<!-- <li><img src="/images/vip2.png" alt="">VIP-объявление</li> -->
									<li><img src="/images/immid.png" alt=""><span>Срочно до окончания пакета</span></li>
								</ul>
								<!-- <div class="s-item__old-price">98<span class="s-item__old-price-currency">грн.</span></div> -->
								<div class="s-item__price"><?= $current_course['medium'] ?><span class="s-item__price-currency">грн.</span></div>
								<?= $form->field($model, 'promotion[]')->checkbox(['label' => false, 'style'=> 'display:none;'])->label(false) ?>
								<button data-package="medium" class="btn j-primary s-item__btn">Выбрать</button>
							</div>
						</div>
						<div class="col-md-4"> <!-- item3 -->
							<div class="s-item">
								<div class="s-item__title">
									<h3>Супер старт<a href="#" class="s-item__title-info-circle"><i data-toggle="tooltip" data-placement="right" title="C этим пакетом Вы получите топ-объявление на 30 дней, поднятие в верх на протяжении 7 дней и срочно до окончания пакета" class="fa fa-info-circle" aria-hidden="true"></i></a></h3>	
									<p><i style="color:green; font-style: normal;">28х больше</i> просмотров</p>
								</div>
								<ul>
									<li><img src="/images/up.png" alt=""><span>Топ-объявление на <strong>30</strong> дня</span></li>
									<li><img src="/images/upp.png" alt=""><span>7 поднятий в верх</span></li>
									<li><img src="/images/vip2.png" alt=""><span>VIP-объявление 7 дней</span></li>
									<li><img src="/images/immid.png" alt=""><span>Срочно до окончания пакета</span></li>
								</ul>
								<!-- <div class="s-item__old-price">377<span class="s-item__old-price-currency">грн.</span></div> -->
								<div class="s-item__price"><?= $current_course['full'] ?><span class="s-item__price-currency">грн.</span></div>
								<?= $form->field($model, 'promotion[]')->checkbox(['label' => false, 'style'=> 'display:none;'])->label(false) ?>
								<button data-package="full" class="btn j-primary s-item__btn">Выбрать</button>
							</div>
						</div>
					</div>
					<h4 class="s-title">также вы можете купить одну из услуг по стандартной цене:</h4>
					<div class="row service">
						<div class="col-md-12 s-list__item">
							<?= $form->field($model, 'up')->checkbox(['label' => false])->label(false) ?>
							<img src="/images/upp.png" alt="">
							<p>Поднятие в верх списка</p>
							<select name="select_up" >
								<?php foreach ($current_price['up'] as $key => $value): ?>
									<option value="<?= $key ?>"><?= $key ?> дней</option>
								<?php endforeach; ?>
							</select>
							<div class="s-item__price">15<span class="s-item__price-currency">грн.</span></div>
						</div>
						<div class="col-md-12 s-list__item">
							<?= $form->field($model, 'vip')->checkbox(['label' => false])->label(false) ?>
							<img src="/images/vip2.png" alt="">
							<p>VIP-объявление</p>
							<select name="select_vip" >
								<?php foreach ($current_price['vip'] as $key => $value): ?>
									<option value="<?= $key ?>"><?= $key ?> дней</option>
								<?php endforeach; ?>
							</select>
							<div class="s-item__price">28<span class="s-item__price-currency">грн.</span></div>
						</div>
						<div class="col-md-12 s-list__item">
							<?= $form->field($model, 'top_')->checkbox(['label' => false])->label(false) ?>
							<img src="/images/up.png" alt="">
							<p>Топ-объявление</p>
							<select name="select_top" >
								<?php foreach ($current_price['top_'] as $key => $value): ?>
									<option value="<?= $key ?>"><?= $key ?> дней</option>
								<?php endforeach; ?>
							</select>
							<div class="s-item__price">15<span class="s-item__price-currency">грн.</span></div>
						</div>
						<div class="col-md-12 s-list__item">
							<?= $form->field($model, 'fire')->checkbox(['label' => false])->label(false) ?>
							<img src="/images/immid.png" alt="">
							<p>Срочно (7 дней)</p>
							<select name="select_fire" style="display: none">
								<?php foreach ($current_price['fire'] as $key => $value): ?>
									<option value="<?= $key ?>"><?= $key ?> дней</option>
								<?php endforeach; ?>
							</select>
							<div class="s-item__price">10<span class="s-item__price-currency">грн.</span></div>
						</div>
						<div class="col-md-12 s-list__item">
							<?= $form->field($model, 'once_up')->checkbox(['label' => false])->label(false) ?>
							<img src="/images/one.png" alt="">
							<p>Единоразовое поднятие</p>
							<select name="select_once" style="display: none">
								<?php foreach ($current_price['once_up'] as $key => $value): ?>
									<option value="<?= $key ?>"><?= $key ?> дней</option>
								<?php endforeach; ?>
							</select>
							<div class="s-item__price">5<span class="s-item__price-currency">грн.</span></div>
						</div>
						<div class="col-md-12 s-list__bot"><span>Всего к оплате:</span><div class="s-item__price">0<span class="s-item__price-currency">грн.</span></div></div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<a href="https://jandooo.com/pravila-ispol-zovaniya">Узнать больше о платных услугах</a>
						</div>
						<div class="col-md-9">
							<?= Html::submitButton('Оплатить', ['class' => 'btn j-primary', 'style' => 'float: right;', 'id' => 'PaymentButton']) ?>
							<!-- <a href="<?php // Url::to(['ads/paket', 'alias' => $ad['alias']]) ?>" class="btn j-primary" style="float: right; margin-right: 10px;">Не рекламировать</a> -->
							<a href="/myaccount/" class="btn j-primary" style="float: right; margin-right: 10px;">Не рекламировать</a>
						</div>
					</div>
				</div> <!--end container -->
			</div>
		<?php ActiveForm::end(); ?>	
	

		