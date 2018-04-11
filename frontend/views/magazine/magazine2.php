<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

?>
<div class="col-md-12 page-magazine too">
<div class="background">
<div class="container p-y-2">
<div class="title mb-50">
<h3 class="mb-15">Создание интернет-магазина на портале Jandooo</h3>
<div class="description">Достаточно только указать название магазина, выбрать категорию деятельности и<br> период, на который Вы желаете открыть магазин.</div>
</div>
<div class="login-box">
<div class="login-tabs">
<div class="title m-y-1">Этап второй</div>
<ul class="login-tabs__content">
<li class="active" data-content="login">
<div class="help-login">
<div class="help-login_3">
<img src="/images/arrow_blue2.png" alt="" />
<div>Выберите категорию деятельности</div>
</div>
<div class="help-login_1">
<img src="/images/arrow_green2.png" alt="" />
<div>Укажите описание магазина</div>
</div>
<div class="help-login_2 help-finish-key">
<img src="/images/arrow_red.png" alt="" />
<div>Введите код с картинки</div>
</div>
</div>
<div class="login-form no-margin m-form">
<?php 
$form = ActiveForm::begin([
'options' => ['enctype'=>'multipart/form-data'],
'action' => Url::to(['magazine/save']),
'validateOnBlur' => false,
'validationDelay' => 1,
'fieldConfig' => [
'template' => "{label}\n{input}\n{hint}\n<div class='wrap-error-div'>\n{error}\n</div>",
],
'enableAjaxValidation' => false,
'enableClientValidation'=>true,
'validationUrl' => Url::to(['magazine/ajax-finish']),
]);
?>
<?= $form->field($model, 'name')->textInput(['class' => 'light required', 'type' => 'hidden'])->label(false) ?>
<?= $form->field($model, 'category_id')->textInput(['class' => 'light required', 'type' => 'hidden'])->label(false) ?>
<?= $form->field($model, 'period')->textInput(['class' => 'light required', 'type' => 'hidden'])->label(false) ?>
<?= $form->field($model, 'tarif_plan')->textInput(['class' => 'light required', 'type' => 'hidden'])->label(false) ?>
<fieldset class="standard-login-box">
<div class="fblock">
<div class="focusbox">
<?=
$form->field($model, 'template')->dropDownList(ArrayHelper::map($model->getTemplates(),'id', 'name'), [
'class' => 'light required hover-pop m-sel',
'data-text' => 'Укажите шаблон внешнего вида'
])->label(false) ?>
</div>
</div>
<div class="fblock">
<div class="focusbox">
<?= 
$form->field($model, 'desc')->textarea(
[
'class' => 'light required m-sel hover-pop',
'data-text' => 'Укажите короткое описание Вашего магазина.',
'placeholder' => 'Описание (до 120 символов)'
])->label(false) 
?>
</div>
</div>
<div class="fblock">
<div class="focusbox">
<?= $form->field($model, 'verifyCode',['enableClientValidation' => true, 'enableAjaxValidation' => false])->widget(yii\captcha\Captcha::className(), ['captchaAction' => '/magazine/captcha/','options' => ['placeholder' => 'Введите текст с картинки']])->label(false) ?>
</div>
</div>
</fieldset>
<?= Html::submitButton('Назад', ['class' => 'without-val btn j-success', 'formaction' => Url::to(['magazine/create'])]) ?>
<?= Html::submitButton('Далее', ['class' => 'btn j-success']) ?>
<?php ActiveForm::end(); ?>
</div>
</li>
</ul>
</div>
</div>
</div>
</div>
</div>