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
		<?php if($model->isNewRecord): ?>
			<div class="col-md-6">
				<?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>	
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>	
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>	
			</div>
		</div>
	</div>
	<?php if(!$model->isNewRecord): ?>
	<div class="wrap-inputs">
		<div class="row">
			<div class="col-md-6">
				<label for="date-in">Дата регистрации</label>
				<input id="date-in" class="form-control" type="text" value="<?php echo Yii::$app->formatter->asDate($model['created_at'], 'php:d/m/Y H:i') ?>" disabled >
			</div>
		</div>
	</div>
	<?php endif;?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
