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
			<div class="col-md-3">
				Активность:
				<label class="switch switch-cu">
			        <input type="checkbox" name="Blog[active]">
			        <span class="slider round"></span>
	            </label>
			</div>
		</div>

	    <div class="row">
	    	<div class="col-md-6">
	    		<div class="input-group">
			    	<span class="input-group-addon">URL</span>
			    	<input type="text" class="form-control alias-input" name="Blog[alias]" aria-required="true" aria-invalid="true" value="<?php if(isset($model->alias)): ?><?php echo $model->alias; ?><?php endif; ?>">
			    	<span class="input-group-addon"><i class="fa fa-unlock" aria-hidden="true"></i></span>
				</div>
	    	</div>
	    	<div class="col-md-3">
	    		
	    	</div>
	    </div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="wrap-inputs">
				 <?php 
				 if(!empty($model->image)){
				 	$img = '/web/uploads/blog/' . $model->image;
				 	echo $form->field($model, 'image')->widget(FileInput::classname(), [
					     'options' => ['accept' => 'image/*'],
					     'pluginOptions' => [
					        'initialPreview'=>[
					            "$img",
					        ],
					        'initialPreviewAsData'=>true,
					    ],
					 ]);
				 }else{
				     echo $form->field($model, 'image')->widget(FileInput::classname(), [
					     'options' => ['accept' => 'image/*']
					 ]);
				 }
				?>
				<?php //echo $form->field($model, 'image')->fileInput() ?>
			</div>
		</div>
		<div class="col-md-6">
			
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="wrap-inputs">
				<div>
				  <ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Краткое описание</a></li>
				    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Полное описание</a></li>
				  </ul>
				  <div class="tab-content">
				    <div role="tabpanel" class="tab-pane fade in active" id="home">
						<?php
						    echo $form->field($model, 'intro_text')->widget(\mihaildev\ckeditor\CKEditor::className(), [
						    'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions('elfinder',['preset' => 'full']),
						    ])->label(false);
					    ?>
				    </div>
				    <div role="tabpanel" class="tab-pane fade" id="profile">
				    	<?php
						    echo $form->field($model, 'full_text')->widget(\mihaildev\ckeditor\CKEditor::className(), [
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
