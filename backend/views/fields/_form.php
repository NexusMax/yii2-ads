<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model backend\models\Categories */
/* @var $form yii\widgets\ActiveForm */
// mihaildev\elfinder\Assets::noConflict($this);

if(!empty($model->value_sub_field)){
            $value_sub_field = json_decode($model->value_sub_field, true);
            $sub_fields = array_keys($value_sub_field);
        }
?>

<!-- <script src="//cdn.jsdelivr.net/sortable/latest/Sortable.min.js"></script> -->
<!-- <script src="//rubaxa.github.io/Sortable/Sortable.min.js"></script> -->

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
			        <input type="checkbox" name="Fields[active]" value="<?php echo $model->active; ?>">
			        <span class="slider round"></span>
	            </label>
			</div>
		</div>

	    <div class="row">
	    	<div class="col-md-9">
	    		<?php echo $form->field($model, 'type')->dropDownList($model->getType(), ['prompt' => 'Выберите тип', 'class' => 'form-control select-add-new-fields']);
	    		 ?>
	    	</div>
	    	<div class="col-md-3">
	    		Обязательность:
				<label class="switch switch-cu">
			        <input type="checkbox" name="Fields[required]" value="<?php echo $model->required; ?>">
			        <span class="slider round"></span>
	            </label>
	    	</div>
	    </div>
	    <div class="row">
			<div class="col-md-9">
				<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>	
			</div>
			<div class="col-md-3">
				Поиск:
				<label class="switch switch-cu">
			        <input type="checkbox" name="Fields[search]" value="<?php echo $model->search; ?>">
			        <span class="slider round"></span>
	            </label>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-8">
			<div class="wrap-inputs add-new-fields">
	    		 <div id="drag" class="dropdown-fields-wrap text-center">
					<a href="#" class="dropdown-fields btn-sm btn-primary">Добавить значения</a>
			
					<?php if(!empty($model->value_sub_field)): ?>

						<?php for ($i = 0; $i < count($sub_fields); $i++): ?>
							<div class="row"><div class="col-md-10"><div class="form-group text-left"><label for="exampleInputName1">Название</label><input type="text" name="Fields[sub_field][]" value="<?php echo $sub_fields[$i]; ?>" class="form-control" id="exampleInputName1"></div></div><div class="col-md-1"><span class="glyphicon glyphicon-move" aria-hidden="true"></span></div><div class="col-md-1"><a href="#" class="dropdown-field-delete btn-sm btn-danger">X</a></div></div>
						<?php endfor; ?>

					<?php endif; ?>	
				</div>
			</div>
		</div>
		<div class="col-md-4 list-category">
			<div class="wrap-inputs">
				<div class="form-group field-fields-category_id has_success">
			        <label for="fields-category_id" class="control-label">Категория</label>
			        <select name="Fields[category_id][]" class="form-control" id="fields-category_id" multiple="multiple">
			            <option value="-1" <?php if(in_array('-1', $model->category_id)) echo 'selected' ?>>Все</option>
			            <?= \backend\components\CategoryWidget::widget(['template' => 'widget-product-fields-select', 'model' => $model]) ?>
			        </select>
			    </div>
			</div>
		</div>
	</div>
	



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
