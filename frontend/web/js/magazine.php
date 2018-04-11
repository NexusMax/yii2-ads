<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<!-- <div class="container">
	<div class="row">
		<div class="col-md-12">
			<h3 class="text-center" style="margin-top: 50px;">Создание магазина будет доступно в ближайшее время</h3>
		</div>
	</div>
</div> -->
<?php //return ?>
		<div class="col-md-12 page-magazine p-y-2">
					<div class="container">
						<div class="title">
							<h1>Создание интернет-магазина на портале Jandooo</h1>
							<div class="description">Достаточно только указать название магазина, выбрать категорию деятельности и период, на который Вы желаете открыть магазин.</div>
						</div>

						<div class="layer">
							<div class="cont">
								<img src="/images/jandooomarket1.jpg" alt="" />
								<div class="center">
									<div class="text">Создай свой интернет магазин всего за <strong>600 грн</strong>!</div>
								</div>
							</div>
						</div>
					</div>

				<div class="">
					<div class="container p-y-2">
						<div class="row params">
							<a href="#" class="item col-md-3">
								<div class="icon"><img src="/images/icon-market1.png"></div>
								<div class="title">Персональная страница с вашими объявлениями</div>
							</a>
							<a href="#" class="item col-md-3">
								<div class="icon"><img src="/images/icon-market2.png"></div>
								<div class="title">Персональная страница с вашими объявлениями</div>
							</a>
							<a href="#" class="item col-md-3">
								<div class="icon"><img src="/images/icon-market3.png"></div>
								<div class="title">Персональная страница с вашими объявлениями</div>
							</a>
							<a href="#" class="item col-md-3">
								<div class="icon"><img src="/images/icon-market4.png"></div>
								<div class="title">Персональная страница с вашими объявлениями</div>
							</a>
						</div>
					</div>
				</div>
					
				<?php 
				$form = ActiveForm::begin([
					'options' => ['enctype'=>'multipart/form-data', 'id' => 'mag-first-form'],
					'action' => Url::to(['magazine/finish']),
					// 'validateOnType' => true,
					'validateOnBlur' => false,
					'validationDelay' => 1,
					'fieldConfig' => [
							'template' => "{label}\n{input}\n{hint}\n<div class='wrap-error-div'>\n{error}\n</div>",
						],
					'enableAjaxValidation' => true,
					'enableClientValidation'=>true,
					'validationUrl' => Url::to(['magazine/ajax']),
					'scrollToErrorOffset' => 250,
				]);
				?>
				<div class="background">
					<div class="container p-y-2">

						<div class="title mb-50">
							<h3 class="mb-15">Создание интернет-магазина на портале Jandooo</h3>
							<div class="description">Достаточно только указать название магазина, выбрать категорию деятельности и<br> период, на который Вы желаете открыть магазин.</div>
						</div>

						<div class="login-box">

						    <div class="login-tabs">
						    	<div class="title m-y-1">Этап первый</div>
						        <ul class="login-tabs__content">
						            <li class="active" data-content="login">

										<div class="help-login">
											<div class="help-login_3">
												<img src="/images/arrow_blue2.png" alt="" />
												<div>выберите категорию деятельности</div>
											</div>
											<div class="help-login_1">
												<img src="/images/arrow_green2.png" alt="" />
												<div>Укажите название вашего магазина</div>
											</div>
											<div class="help-login_2">
												<img src="/images/arrow_red.png" alt="" />
												<div>период, на который вы желаете открыть магазин</div>
											</div>
										</div>

						                <div class="login-form no-margin m-form">
											<div class="inner-form">
										        <fieldset class="standard-login-box">
										            <div class="fblock">
										                <div class="focusbox">
										                <?= 
										                $form->field($model, 'name')->textInput(
										                	[
											                	'class' => 'light required hover-pop', 
											                	'placeholder' => 'Название магазина', 
											                	'title' => 'Название магазина',
											                	'data-text' => 'Придумайте уникальное название магазина.',
										                	])->label(false) 
										                ?>
										                </div>
										            </div>
										            <div class="fblock">
										                <div class="focusbox">
										                <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map($categories,'id', 'name'), [
										                	'class' => 'light required m-sel hover-pop',
										                	'data-text' => 'Выберите рубрику Вашего магазина',
										                	])->label(false) ?>
										                </div>
										            </div>
										             <div class="fblock">
										                <div class="focusbox">
										                <?= $form->field($model, 'period')->dropDownList(ArrayHelper::map($periods,'id', 'name'), ['class' => 'light required m-sel hover-pop',
										                	'data-text' => 'Выберите период действия Вашего магазина.',
										                ])->label(false) ?>
										                </div>
										            </div>
										        </fieldset>
										        </div>
										</div>

						            </li>
						        </ul>
						    </div>
						</div>

					</div>
				</div>

				<div class="background">
					<div class="container p-y-2">
					<div class="title">Тарифные планы</div>

					<div class="fleft tariff">
						<div class="item">
							<div class="name"></div>
							<div class="count">Количество объявлений</div>
							<div class="days">ТОП на 30 дней</div>
							<div class="check">Готовый дизайн</div>
							<div class="price">Цена</div>
						</div>
					</div>

					<div class="row tariff">
						<?php foreach ($plans as $key => $val): ?>
							<?php if(!empty(Yii::$app->request->post('Magazine')['tarif_plan'])) {$sub='firstPrice';}else{$sub = 'price';} ?>
						<div class="item <?php if(!empty(Yii::$app->request->post('Magazine')['tarif_plan'])){if(Yii::$app->request->post('Magazine')['tarif_plan'] == $val['id']){echo 'active';}}else if($val['id'] == 1) {echo 'active';} ?>" data-id="<?= $val['id'] ?>">
							<div class="name"><?= $val['name'] ?></div>
							<div class="count"><span class="span-count"><?= $val[$sub][0]['count_ads'] ?></span> шт.</div>
							<div class="days"><?= $val[$sub][0]['top_30_day'] ?></div>
							<div class="check">
								<i class="fa fa-<?php if(intval($val[$sub][0]['design']) === 1) echo 'check'; else echo 'close'; ?>" aria-hidden="true"></i>
							</div>
							<div class="price"><span class="line-through"></span><span class="span-old-price"><?= intval($val[$sub][0]['old_price']) ?></span> грн</div>
							<div class="price price-two"><span class="span-price"><?= intval($val[$sub][0]['price']) ?></span> грн</div>
							<a class="btn j-success check-mag-tarif" data-id="<?= $val['id'] ?>" href="#">Выбрать</a>
						</div>
						<?php endforeach; ?>
					</div>
				
				<style>
					.row.tariff .item > div.price,
					.fleft.tariff .item > div.price {
				    	display: block;
					    margin: 0 auto;
					    padding: 0;
					    line-height: 1.5;
					    height: 26px;
					}
					.price-two{
						margin-bottom: 20px;
					}
					.price .line-through{

					}
				</style>
					<input type="hidden" id="magazine-tarif_plan" class="light required" name="Magazine[tarif_plan]" title="Название магазина" placeholder="Название магазина" value="<?php if(!empty(Yii::$app->request->post('Magazine')['tarif_plan'])) {echo Yii::$app->request->post('Magazine')['tarif_plan'];}else{echo '1';} ?>">

					</div>
					
					<?= Html::submitButton('Далее', ['class' => 'btn j-success']) ?>

				</div>
				<?php ActiveForm::end(); ?>

			</div>