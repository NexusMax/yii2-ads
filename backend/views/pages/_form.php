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
				<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>	
			</div>
		</div>

	    <div class="row">
	    	<div class="col-md-6">
	    		<div class="input-group">
			    	<span class="input-group-addon">URL</span>
			    	<input type="text" class="form-control alias-input" name="Pages[alias]" aria-required="true" aria-invalid="true" value="<?php if(isset($model->alias)): ?><?php echo $model->alias; ?><?php endif; ?>">
			    	<span class="input-group-addon"><i class="fa fa-unlock" aria-hidden="true"></i></span>
				</div>
	    	</div>
	    	<div class="col-md-6">
	    		<?= $form->field($model, 'active')->dropDownList([1 => 'Да', 0 => 'Нет']) ?>	
	    	</div>
	    </div>
	    <div class="row">
	    	<div class="col-md-6">
	    		<?= $form->field($model, 'position')->dropDownList([1 => 'В шапке сайта', 2 => 'В подвале сайта', 3 => 'Нигде']) ?>
	    	</div>
	    	<div class="col-md-6">
	    		<?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>	
	    	</div>
	    </div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="wrap-inputs">
				<div>
				  <ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Описание</a></li>
				  </ul>
				  <div class="tab-content">
				    <div role="tabpanel" class="tab-pane active" id="home">
						<?php
						    echo $form->field($model, 'text')->widget(\mihaildev\ckeditor\CKEditor::className(), [
						    'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions('elfinder',['preset' => 'full']),
						    ])->label(false);
					    ?>
				    </div>
				  </div>
				</div>
			</div>
		</div>
	</div>
	

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
