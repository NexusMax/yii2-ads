<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

?>

<div class="categories-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
	<div class="wrap-inputs">
		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>	
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>	
			</div>
		</div>	
		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>	
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>	
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>	
			</div>
		</div>
	</div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' =>  'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
