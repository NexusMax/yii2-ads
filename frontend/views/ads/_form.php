<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use \frontend\models\Ads;

/* @var $this yii\web\View */
/* @var $model backend\models\Categories */
/* @var $form yii\widgets\ActiveForm */
// mihaildev\elfinder\Assets::noConflict($this);

array_unshift ($reg, ['id' => 0, 'name' => 'Выберите область']);


?>

<section class="add-page container p-y-2 form-ads" style="overflow-x: hidden;">

	<?php $form = ActiveForm::begin([
		'options' => ['enctype'=>'multipart/form-data'],
		// 'validateOnType' => true,
		'validateOnBlur' => false,
		'validationDelay' => 1,
		'fieldConfig' => [
				'template' => "{label}\n{input}\n{hint}\n<div class='wrap-error-div'>\n{error}\n</div>",
			],
		// 'enableAjaxValidation' => true,
		]);?>

		<div class="title">Описание товара: </div>
		<div id="categories" class="fblock clr pdingright0 catSelector3">
			<div class="fleft label tright">
				<label class="fbold c000">Категория: <span class="red">*</span></label>
			</div>
			<div class="area fleft">

				<!-- EDIT FORM -->
				<div id="category-breadcrumb-container" class="vmiddle">
					<?php if($model->isNewRecord): ?>
					<?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map($categories, 'id', 'name'), ['class' => 'dropdown light subcategories-ajax'])->label(false) ?>
					<?php else: ?>
					<?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map($categories, 'id', 'name'),['options' => [$parent_child => ['selected' => 'selected']], 'class' => 'dropdown light subcategories-ajax'])->label(false) ?>	
					<?php endif; ?>

					<?php foreach (Yii::$app->params['myaccount']['cat_limit'] as $key => $value) {
						echo '<input type="hidden" id="cat_' . $key . '" name="cat_' . $key . '" value="' . $value['count'] . '">';
					} ?>

				</div>
				<!-- EDIT FORM END -->

				<!-- EDIT FORM -->
				<!-- EDIT FORM END -->

			</div>
		</div>

		<div id="categories" class="fblock clr pdingright0 catSelector3">
			<div class="help-login">
				<div class="help-login_2">
					<img src="/images/arrow_red.png" alt="" />
					<div class="text">Выберите рубрику</div>
					<div class="images">
						<a href="#"><img src="/images/transport.png" alt="" /></a>
						<a href="#"><img src="/images/animal.png" alt="" /></a>
						<a href="#"><img src="/images/10_fashion.png" alt="" /></a>
						<a href="#"><img src="/images/estate.png" alt="" /></a>
					</div>
				</div>
			</div>

			<div class="fleft label tright">
				<label class="fbold c000">Подкатегория: <span class="red">*</span></label>
			</div>
			<div class="area fleft">

				<!-- EDIT FORM -->

				<div id="category-breadcrumb-container" class="vmiddle">
					<?php if($model->isNewRecord): ?>
						<?= $form->field($model, 'sub_category')->dropDownList([''=>'Выберите категорию'], ['class' => 'dropdown light dropdownSubCat'])->label(false) ?>
					<?php else: ?>
						<?= $form->field($model, 'sub_category')->dropDownList(ArrayHelper::map($childs_cat, 'id', 'name'),['options' => [$model->category_id => ['selected' => 'selected']], 'class' => 'dropdown light dropdownSubCat', 'data-ajax-id' => $model->id])->label(false) ?>
					<?php endif; ?>
				</div>
				<!-- EDIT FORM END -->

				<!-- EDIT FORM -->
				<!-- EDIT FORM END -->

			</div>
		</div>
	
	
		<div id="categories" class="categories-three fblock clr pdingright0 catSelector3"
		<?php if($model->isNewRecord): ?>
				style="display: none"
		<?php elseif(empty($childs_childs_cat)): ?>
			style="display: none"
		<?php endif; ?> >
			<div class="fleft label tright">
				<label class="fbold c000">Подкатегория:</label>
			</div>
			<div class="area fleft">

				<!-- EDIT FORM -->
				<div id="category-breadcrumb-container" class="vmiddle">
					<?php if($model->isNewRecord): ?>
						<?= $form->field($model, 'sub_sub_category')->dropDownList([''=>'Выберите категорию'], ['class' => 'dropdown light dropdownSubSubCat'])->label(false) ?>
					<?php else: ?>
						<?php if(!empty($childs_childs_cat)): ?>
							<?= $form->field($model, 'sub_sub_category')->dropDownList(ArrayHelper::map($childs_childs_cat, 'id', 'name'),['options' => [current($childs_cat['parent_id']) => ['selected' => 'selected']], 'class' => 'dropdown light subcategories-ajax'])->label(false) ?>
						<?php endif; ?>	
					<?php endif; ?>
				</div>
				<!-- EDIT FORM END -->

				<!-- EDIT FORM -->
				<!-- EDIT FORM END -->

			</div>
		</div>

		<div id="title" class="fblock clr">
			<div class="fleft label tright">
				<label class="validation fbold c000" for="add-title">Заголовок: <span class="red">*</span></label>
			</div>
			<div class="area clr">
				<div class="fleft rel zi2 focusbox">

					<?= $form->field($model, 'name')->textInput(['class' => 'text x-normal light br4 xxx-long2 required', 'max' => 70, 'min' => 6, 'id' => 'add-title'])->label(false) ?>
					<p class="desc ca6">
						<small>
							<b id="add-title-counter" data-for-id="add-title" class="counter-placeholder">70</b> знаков осталось</small>
						</p>
					</div>
				</div>
			</div>
			<div class="area clr block-after"></div>
			<?php if(!$model->isNewRecord): ?>
					<?php print_r($startSubField); ?>
			<?php endif; ?>

			<div id="title" class="fblock clr">
				<div class="fleft label tright">
					<label class="validation fbold c000">Описание:</label>
				</div>
				<div class="area clr">
					<div class="fleft rel zi2 focusbox">
						<?= $form->field($model, 'text')->textarea(['id' => 'desc', 'rows' => 5, 'cols' => 50])->label(false) ?>
					</div>
				</div>
			</div>
			<div class="area clr"></div>

			<div id="categories" class="fblock clr pdingright0 catSelector3 price">
				<div class="fleft label tright">
					<label class="validation fbold c000" for="add-title">Цена: <span class="red">*</span></label>
				</div>
				<div class="price area clr">
					<div class="fleft rel zi2 focusbox">
						<?= $form->field($model, 'price')->textInput(['type' => 'number', 'step' => 'any', 'class' => 'text x-normal light br4 xxx-long2 required'])->label(false) ?>

						<?= $form->field($model, 'type_payment')->dropDownList(Ads::getTypePayment())->label(false) ?>
						<div class="torg">
							<?= $form->field($model, 'bargain')->checkbox(['id' => 'torg']) ?>
						</div>
						<!-- <a class="quest" href="#"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a> -->
						<div class="area clr"></div>
						<!-- <p class="desc ca6">
							<small>
								Цена в объявлении будет указана в гривнах, согласно закону <a href="#">"О рекламе"</a>.</small>
							</p> -->

							<div class="price-btn">
								<?= $form->field($model, 'negotiable')->checkbox(['id' => 'price-btn']) ?>
							</div>
							<div class="price-btn">
								<?php if(!$model->isNewRecord && empty($model->price)): ?>
									<?= $form->field($model, 'without_payment')->checkbox(['id' => 'without_payment', 'checked ' => true]) ?>
								<?php else: ?>
									<?= $form->field($model, 'without_payment')->checkbox(['id' => 'without_payment']) ?>
								<?php endif; ?>
									
								</div>

						</div>
					</div>
				</div>
				<div class="area clr"></div>

				<!-- <div id="categories" class="fblock clr pdingright0 catSelector3">
					<div class="fleft label tright">
						<label class="validation fbold c000">Тип доставки:</label>
					</div>
					<div class="area fleft">


						<div id="category-breadcrumb-container" class="vmiddle">

							<?= $form->field($model, 'type_delivery')->dropDownList(Ads::getTypeDelivery(),['class' => 'dropdown light'])->label(false) ?>

						</div>


					</div>
				</div> -->

				<div class="area clr"></div>

				<div id="categories" class="fblock clr pdingright0 catSelector3">
					<div class="fleft label tright">
						<label class="validation fbold c000">Тип обьявления:</label>
					</div>
					<div class="area fleft">


						<div id="category-breadcrumb-container" class="vmiddle">

							<?= $form->field($model, 'type_ads')->dropDownList(Ads::getTypeAds(),['class' => 'dropdown light'])->label(false) ?>

						</div>


					</div>
				</div>

				<div class="area clr"></div>

				<div class="photo title">Добавить фото: </div>
				<div id="categories" class="fblock clr pdingright0 catSelector3 files img-upl">
					<div class="help-login">
						<div class="help-login_3">
							<img src="/images/arrow_green.png" alt="" />
							<div class="text">Главное <br>изображение <br>Вашего <br>объявления</div>
						</div>
					</div>
<!-- 					<p class="desc ca6">
						<small>Чтобы выбрать несколько фото удерживайте клавишу Ctrl.</small>
						<small>Максимальный размер одного фото 5 Мб. Форматы фото: JPEG, JPG, PNG.</small>
						<small>Не стоит указывать на фото номера телефонов, адрес эл. почты или ссылки на другие сайты.</small>
					</p> -->
					

					<?php for($i = 1, $j = 0; $i <= 6; $i++, $j++): ?>
						<?php if($i == 1 || $i == 4): ?>
						<div class="row">
						<?php endif; ?>
							<div class="area fleft upl-img">
								<div class="file<?= $i ?>">
							<?php if(isset($images) && !empty($images) && !empty($images[$j]['urlAlias'])): ?>
									<?= $form->field($model, 'image_' . $i .'[]')->fileInput(['multiple' => true, 'accept' => 'image/*', 'id' => 'file' . $i, 'class' => 'btn j-file', 'style' => 'display: none;', 'data-error' => 'Разрешена загрузка файлов только со следующими расширениями: png, jpg, jpeg, gif.','data-text' => '<strong>Фотографии в объявлении привлекают большее количество клиентов</strong> Загрузите фото размером не больше 5 МБ в формате .jpg, .jpeg, .png, .gif. Рекомендуемый размер 450px и больше. Не стоит на фото указывать контактную информацию - такие объявления удаляются модертором'])->label(false) ?>
									<label class="file-i" for="file<?= $i ?>"><img src="<?= '/web/images/store/'.$images[$j]['filePath'] ?>" alt="" class="selected" /><i data-ajax-delete="<?= $images[$j]['id'] ?>" data-id="<?= $model->id ?>" class="fa fa-times" aria-hidden="true"></i></label>
							<?php else: ?>
									<?= $form->field($model, 'image_' . $i .'[]')->fileInput(['multiple' => true, 'accept' => 'image/*', 'id' => 'file' . $i, 'class' => 'btn j-file', 'style' => 'display: none;', 'data-error' => 'Разрешена загрузка файлов только со следующими расширениями: png, jpg, jpeg, gif.', 'data-text' => '<strong>Фотографии в объявлении привлекают большее количество клиентов</strong> Загрузите фото размером не больше 5 МБ в формате .jpg, .jpeg, .png, .gif. Рекомендуемый размер 450px и больше. Не стоит на фото указывать контактную информацию - такие объявления удаляются модертором'])->label(false) ?>
									<label class="file-i" for="file<?= $i ?>"><img src="/images/add-min.png" alt="" /></label>
							<?php endif; ?>
								</div>
							</div>
						<?php if($i == 3 || $i == 6): ?>
						</div>

						<?php endif; ?>
					<?php endfor; ?>
				</div>
				<div class="area clr"></div>


				<div class="contacts title">Ваши контактные данные</div>
				<div id="contacts" class="fblock clr pdingright0 catSelector3 contacts-foot">

					<div class="help-login">
						<div class="help-login_3">
							<img src="/images/arrow_blue2.png" alt="" />
							<div class="text">Контактная информация</div>
						</div>
					</div>

					<div class="row">
						<div class="area">
							<div class="fleft">Область <span class="red">*</span></div>
							<?= $form->field($model, 'reg_id')->dropDownList(ArrayHelper::map($reg, 'id', 'name'), ['class' => 'dropdown light'])->label(false) ?>
						</div>
					</div>

					<div class="row">
						<div class="area">
							<div class="fleft">Город <span class="red">*</span></div>
							<?php if($model->isNewRecord): ?>
								<?= $form->field($model, 'city_id')->dropDownList(['Выберите область'], ['class' => 'dropdown light'])->label(false) ?>
							<?php else: ?>
								<?= $form->field($model, 'city_id')->dropDownList(ArrayHelper::map($city, 'id', 'name'), ['class' => 'dropdown light'])->label(false) ?>
							<?php endif; ?>
						</div>
					</div>

					<div class="row">
						<div class="area">
							<div class="fleft"><i class="fa fa-phone" aria-hidden="true"></i> Номер телефона <span class="red">*</span></div>
							<?php if(!$model->isNewRecord): ?>
								<?= $form->field($model, 'phone')->textInput(['class' => 'btn text x-normal light br4', 'value' => ''. $model->phone])->label(false) ?>
							<?php else: ?>
								<?= $form->field($model, 'phone')->textInput(['class' => 'btn text x-normal light br4', 'value' => ''. $user->phone])->label(false) ?>
							<?php endif; ?>
						</div>
					</div>
					<div class="area clr display-none"></div>

					<div class="row">
						<div class="area">
							<div class="fleft"><i class="fa fa-phone" aria-hidden="true"></i> Второй номер телефона</div>
							<?= $form->field($model, 'phone_2')->textInput(['class' => 'btn text x-normal light br4', 'value' => $model->phone_2])->label(false) ?>
						</div>
					</div>

					<div class="row">
						<div class="area">
							<div class="fleft"><i class="fa fa-phone" aria-hidden="true"></i> Третий номер телефона</div>
							<?= $form->field($model, 'phone_3')->textInput(['class' => 'btn text x-normal light br4', 'value' => $model->phone_3])->label(false) ?>
						</div>
					</div>

					<div class="area clr display-none"></div>

					<div class="row">
						<div class="area">
							<div class="fleft">Email-адрес</div>
							<?= $form->field($model, 'email')->textInput(['class' => 'btn text x-normal light br4', 'value' => $user->email])->label(false) ?>
						</div>
					</div>
					<div class="area clr display-none"></div>

					<div class="row">
						<div class="area">
							<div class="fleft">Контактное лицо <span class="red">*</span></div>
							<?= $form->field($model, 'contact')->textInput(['class' => 'btn text x-normal light br4', 'value' => ''. $user->username . ' ' . $user->lastname . ''])->label(false) ?>
						</div>
					</div>

				</div>
				<div class="area clr display-none"></div>

				<div class="buttons">
					<?php if($model->isNewRecord):?>			

					<a href="/ads/create" id="predview">Предпросмотр</a>

					<?php endif; ?>
					
					<?= Html::submitButton($model->isNewRecord ? 'Подать объявление' : 'Сохранить', ['class' => 'btn j-success']) ?>
				</div>
				<div class="area clr"></div>
				<?php ActiveForm::end(); ?>

			</section>
						<?php \yii\bootstrap\Modal::begin([
							    'id' => 'preview-modal',
							    'size' => 'modal-lg',
							    'header' => '<h4>Предпросмотр обьявления</h4>',
							    'footer' => '<a href="#" data-dismiss="modal" class=" btn btn-default">Продолжить редактирование</a>' . Html::submitButton('Подать объявление', ['class' => 'btn j-success'])
							]);
						
						

						\yii\bootstrap\Modal::end();?>