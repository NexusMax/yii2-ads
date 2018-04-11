<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use \frontend\models\Ads;
?>
<section class="add-page container p-y-2 ">

	<?php $form = ActiveForm::begin([
		'options' => ['enctype'=>'multipart/form-data']
		]);?>

		<div class="title">Описание товара: </div>
		<div id="categories" class="fblock clr pdingright0 catSelector3">
			<div class="fleft label tright">
				<label class="fbold c000">Категория: <span class="red">*</span></label>
			</div>
			<div class="area fleft">

				<!-- EDIT FORM -->
				<span class="area_arrow"></span>
				<div id="category-breadcrumb-container" class="vmiddle">
					<?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map($categories, 'id', 'name'), ['class' => 'dropdown light subcategories-ajax'])->label(false) ?>
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
					<?= $form->field($model, 'sub_category')->dropDownList([''=>'Выберите категорию'], ['class' => 'dropdown light dropdownSubCat'])->label(false) ?>

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

					<?= $form->field($model, 'name')->textInput(['class' => 'text x-normal light br4 xxx-long2 required', 'data-maxtext' => 70, 'data-mintext' => 6, 'id' => 'add-title'])->label(false) ?>
					<p class="desc ca6">
						<small class="title-placeholder">
							<b id="add-title-counter" data-for-id="add-title" class="counter-placeholder">70</b> знаков осталось</small> 
						</p>
					</div>
				</div>
			</div>
			<div class="area clr"></div>

			<div id="title" class="fblock clr">
				<div class="fleft label tright">
					<label class="validation fbold c000">Описание:</label>
				</div>
				<div class="area clr">
					<div class="fleft rel zi2 focusbox">
						<?= $form->field($model, 'text')->textarea(['class' => 'description', 'id' => 'desc', 'rows' => 5, 'cols' => 50])->label(false) ?>
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
						<a class="quest" href="#"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a>
						<div class="area clr"></div>
						<p class="desc ca6">
							<small>
								Цена в объявлении будет указана в гривнах, согласно закону <a href="#">"О рекламе"</a>.</small>
							</p>

							<div class="price-btn">
								<?= $form->field($model, 'negotiable')->checkbox(['id' => 'price-btn']) ?>
							</div> 

						</div>
					</div>
				</div>
				<div class="area clr"></div>

				<div id="categories" class="block-after fblock clr pdingright0 catSelector3">
					<div class="fleft label tright">
						<label class="validation fbold c000">Тип доставки:</label>
					</div>
					<div class="area fleft">

						<!-- EDIT FORM -->

						<div id="category-breadcrumb-container" class="vmiddle">

							<?= $form->field($model, 'type_delivery')->dropDownList(Ads::getTypeDelivery(),['class' => 'dropdown light'])->label(false) ?>

						</div>
						<!-- EDIT FORM END -->

						<!-- EDIT FORM -->
						<!-- EDIT FORM END -->

					</div>
				</div>
				<div class="area clr"></div>

				<div class="photo title">Добавить фото: </div>
				<div id="categories" class="fblock clr pdingright0 catSelector3 files">
					<div class="help-login">
						<div class="help-login_3">
							<img src="/images/arrow_green.png" alt="" />
							<div class="text">Главное изображение Вашего объявления</div>
						</div>
					</div>
					<p class="desc ca6">
						<small>Чтобы выбрать несколько фото удерживайте клавишу Ctrl.</small>
						<small>Максимальный размер одного фото 5 Мб. Форматы фото: JPEG, JPG, PNG.</small>
						<small>Не стоит указывать на фото номера телефонов, адрес эл. почты или ссылки на другие сайты.</small>
					</p>

					<div class="row">
						<div class="area fleft">
							<div class="file1">
								<?= $form->field($model, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*', 'id' => 'file1', 'class' => 'btn j-file', 'style' => 'display: none;'])->label(false) ?>
								<label class="file-i" for="file1"><img src="/images/add-min.png" alt="" /></label>
							</div>

						</div>
						<div class="area fleft">
							<div class="file2">
								<?= $form->field($model, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*', 'id' => 'file2', 'class' => 'btn j-file', 'style' => 'display: none;'])->label(false) ?>
								<label class="file-i" for="file2"><img src="/images/add-min.png" alt="" /></label>
							</div>

						</div>
						<div class="area fleft">
							<div class="file3">
								<?= $form->field($model, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*', 'id' => 'file3', 'class' => 'btn j-file', 'style' => 'display: none;'])->label(false) ?>
								<label class="file-i" for="file3"><img src="/images/add-min.png" alt="" /></label>
							</div>

						</div>
					</div>
					<div class="row">
						<div class="area fleft">
							<div class="file4">
								<?= $form->field($model, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*', 'id' => 'file4', 'class' => 'btn j-file', 'style' => 'display: none;'])->label(false) ?>
								<label class="file-i" for="file4"><img src="/images/add-min.png" alt="" /></label>
							</div>

						</div>
						<div class="area fleft">
							<div class="file5">
								<?= $form->field($model, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*', 'id' => 'file5', 'class' => 'btn j-file', 'style' => 'display: none;'])->label(false) ?>
								<label class="file-i" for="file5"><img src="/images/add-min.png" alt="" /></label>
							</div>

						</div>
						<div class="area fleft">
							<div class="file6">
								<?= $form->field($model, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*', 'id' => 'file6', 'class' => 'btn j-file', 'style' => 'display: none;'])->label(false) ?>
								<label class="file-i" for="file6"><img src="/images/add-min.png" alt="" /></label>
							</div>

						</div>
					</div>

				</div>
				<script type="text/javascript">
					$(document).ready(function() {
						function readURL(input) {
							if (input.files && input.files[0]) {
								var reader = new FileReader();

								reader.onload = function (e) {
									$("#"+input.id).parent().parent().find("img").attr('src', e.target.result).parent().append('<i class="fa fa-times" aria-hidden="true"></i>');
									$("#"+input.id).parent().parent().find("img").addClass("selected");
								}

								reader.readAsDataURL(input.files[0]);
							}
						}
						$(".files input[type=file]").change(function(){
							readURL(this);
						});

						$(document).on('click', '.file-i i', function(e){
							e.preventDefault();
							$(this).parent().parent().find('.form-group input[type=file]').val("");
							$(this).parent().find("img").attr('src', '/images/add-min.png').removeClass('selected');
							$(this).remove();
						});
					});

				</script>
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
							<div class="fleft">Местоположение <span class="red">*</span></div>
							<?= $form->field($model, 'location')->textInput(['class' => 'btn text x-normal light br4'])->label(false) ?>
						</div>
					</div>
					<div class="area clr display-none"></div>

					<div class="row">
						<div class="area">
							<div class="fleft"><i class="fa fa-phone" aria-hidden="true"></i> Номер телефона</div>
							<?= $form->field($model, 'phone')->textInput(['class' => 'btn text x-normal light br4', 'value' => ''. $user->phone])->label(false) ?>
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
					<a href="#" class="btn j-link">Предпросмотр</a>
					<?= Html::submitButton('Подать объявление', ['class' => 'btn j-success']) ?>
				</div>
				<div class="area clr"></div>
				<?php ActiveForm::end(); ?>
			</section>