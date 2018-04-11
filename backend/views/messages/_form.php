<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model backend\models\Categories */
/* @var $form yii\widgets\ActiveForm */
// mihaildev\elfinder\Assets::noConflict($this);
?>

<div class="categories-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
	<div class="wrap-inputs">

	<div class="row">
			<div class="col-md-9">
				<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>	
			</div>
		</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="wrap-inputs">
				<div>

					<?php
					    echo $form->field($model, 'text')->widget(\mihaildev\ckeditor\CKEditor::className(), [
					    'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions('elfinder',['preset' => 'basic']),
					    ])->label(false);
				    ?>

				</div>
			</div>
		</div>
	</div>
	

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
