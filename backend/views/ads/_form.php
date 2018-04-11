<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model backend\models\Categories */
/* @var $form yii\widgets\ActiveForm */
// mihaildev\elfinder\Assets::noConflict($this);

$sub_fields = \backend\models\Fields::find()->select('name, name_field, type, value_sub_field, required')->where(['like', 'category_id', ':"'.$model->category_id.'"'])->orWhere(['like', 'category_id', ':"-1"'])->asArray()->all();
$values_sub_fields = \backend\models\FieldValue::find()->where(['ads_id' => $model->id])->asArray()->one();

$values_sub_fields = json_decode($values_sub_fields['value_sub_field'], true);

$ads_has_image = \backend\models\AdsHasImage::find()->where(['ads_id' => $id])->all();
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
			        <input type="checkbox" name="Ads[active]">
			        <span class="slider round"></span>
	            </label>
			</div>
		</div>

	    <div class="row">
	    	<div class="col-md-6">
	    		<div class="input-group">
			    	<span class="input-group-addon">URL</span>
			    	<input type="text" class="form-control alias-input" name="Ads[alias]" aria-required="true" aria-invalid="true" value="<?php if(isset($model->alias)): ?><?php echo $model->alias; ?><?php endif; ?>">
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

				 if(!$model->isNewRecord){

				 	echo $form->field($model, 'images[]')->widget(FileInput::classname(), [
					     'options' => ['accept' => 'image/*', 'multiple' => true],
					     'language' => 'ru',
					     'pluginOptions' => [
					     	'previewFileType' => 'any',
					        'initialPreview'=> $model->getAllImages(),
					        'initialPreviewAsData'=>true,
					        'overwriteInitial'=>false,
					        'uploadUrl' => yii\helpers\Url::to(['/site/file-upload']),
					        'initialPreviewConfig'=>$model->getInitImage(),
					    ],
					]);
				 }else{
				     echo $form->field($model, 'images[]')->widget(FileInput::classname(), [
					     'options' => ['accept' => 'image/*', 'multiple' => true],
					     'language' => 'ru',
					     'pluginOptions' => ['previewFileType' => 'any', 'uploadUrl' => yii\helpers\Url::to(['/site/file-upload']),]
					 ]);
				 }
				?>
			</div>
		</div>
		<div class="col-md-6 dropdown-category-field">
			<div class="wrap-inputs">
	            <div class="form-group field-ads-category_id has_success" data-id="<?php echo $model->id ?>">
			        <label for="ads-category_id" class="control-label">Категория</label>
			        <select name="Ads[category_id]" class="form-control" id="ads-category_id">
			        	<?php if($model->isNewRecord) echo '<option value="0">Выберите категорию</option>'; ?>
			            <?= \backend\components\CategoryWidget::widget(['template' => 'widget-product-select', 'model' => $model]) ?>
			        </select>
			    </div>
			</div>
		</div>
	</div>

	<div class="wrap-inputs">
		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>	
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
			</div>
		</div>

	    <div class="row">
	    	<div class="col-md-6">
				<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
	    	</div>
	    	<div class="col-md-6">
	    		<?= $form->field($model, 'contact')->textInput(['maxlength' => true]) ?>
	    	</div>
	    </div>

	    <div class="row">
	    	<div class="col-md-6">
				<?= $form->field($model, 'price')->textInput(['maxlength' => true, 'type' => 'number', 'step' => 'any']) ?>
	    	</div>
	    	<div class="col-md-6">

	    	</div>
	    </div>
		<div class="row">
	    	<div class="col-md-6">
	    		<?= $form->field($model, 'type_payment')->dropDownList(\frontend\models\Ads::getTypePayment()) ?>
	    	</div>
	    	<div class="col-md-6">
	    		<?= $form->field($model, 'type_delivery')->dropDownList(\frontend\models\Ads::getTypeDelivery()) ?>
	    	</div>
	    </div>
	    <div class="row">
	    	<div class="col-md-3">
	    		<?= $form->field($model, 'bargain')->checkbox() ?>
	    	</div>
	    	<div class="col-md-3">
	    		<?= $form->field($model, 'negotiable')->checkbox() ?>
	    	</div>
	    </div>
	</div>

	<div class="row insert_fields">
			<div class="col-md-6">
				<div class="wrap-inputs">
				<?php //$list = ['1' => 'Элемент А','2' => 'Вип','3' => 'Элемент В','4' => 'Элемент В','5' => 'Элемент В', '6' => 'Элемент В',];?>
				<?php //echo $form->field($model, 'ads_has_image')->checkboxList($list);
				?>

				<p>Поднятие в верх списка</p>
				<?= $form->field($model, 'up')->dropDownList([0 => 'Выбрать', 3 => '3 дней', 7 => '7 дней'])->label(false) ?>
				<p>VIP-объявление</p>
				<?= $form->field($model, 'vip')->dropDownList([0 => 'Выбрать', 7 => '7 дней', 14 => '14 дней'])->label(false) ?>
				<p>Топ-объявление</p>
				<?= $form->field($model, 'top_')->dropDownList([0 => 'Выбрать', 3 => '3 дней', 7 => '7 дней', 14 => '14 дней', 30 => '30 дней'])->label(false) ?>
				<?= $form->field($model, 'fire')->checkbox() ?>
				<?= $form->field($model, 'once_up')->checkbox() ?>
			</div>
		</div>

		<?php 
			foreach ($sub_fields as $sub_field){
                $j_values = array_values(json_decode($sub_field['value_sub_field'], true));
                $j_keys = array_keys(json_decode($sub_field['value_sub_field'], true));


                if($sub_field['type'] == 'text' || $sub_field['type'] == 'number'){
                    $form_control = 'form-control';

                    $required = '';
                    if($sub_field['required'] == '1'){
                        $required = 'required="required"';
                    }

                    if(in_array($sub_field['name_field'], array_keys($values_sub_fields)))
                        $render_html .= '<div class="col-md-6 inputs_js"><div class="wrap-inputs"><div class="form-group"><label> ' . $sub_field['name'] . ' </label><input '.$required.' class="' . $form_control . '" name="Ads[sub_fields][' . $sub_field['name_field'] . ']" type="' . $sub_field['type'] . '"  value="'.$values_sub_fields[$sub_field['name_field']].'"></div></div></div>';
                }
                elseif($sub_field['type'] == 'radio' || $sub_field['type'] == 'checkbox'){

                    $required = '';
                    if($sub_field['required'] == '1'){
                        $required = 'required="required"';
                    }

                    $j_sub_files = '';
                    for($j = 0; $j < count($j_values); $j++)
                        if($sub_field['type'] == 'radio')
                            if(in_array($j_keys[$j], $values_sub_fields))
                                $j_sub_files .= '<label><input '.$required.' name="Ads[sub_fields]['. $j_values[$j] . ']" type="'. $sub_field['type'] . '" value="'.$j_keys[$j].'" checked> '. $j_keys[$j] . '</label>';
                            else $j_sub_files .= '<label><input '.$required.' name="Ads[sub_fields]['. $j_values[$j] . ']" type="'. $sub_field['type'] . '" value="'.$j_keys[$j].'"> '. $j_keys[$j] . '</label>';
                        else                    
                            if(in_array($j_values[$j], array_keys($values_sub_fields)))
                                $j_sub_files .= '<label><input '. $required .' name="Ads[sub_fields]['. $j_values[$j] . ']" type="'. $sub_field['type'] . '" value="'.$j_keys[$j].'" checked> '. $j_keys[$j] . '</label>';
                            else $j_sub_files .= '<label><input '. $required .' name="Ads[sub_fields]['. $j_values[$j] . ']" type="'. $sub_field['type'] . '" value="'.$j_keys[$j].'"> '. $j_keys[$j] . '</label>';

                    
                    
                    $render_html .= '<div class="col-md-6 inputs_js"><div class="wrap-inputs"><div class="form-group"><div><label>' . $sub_field['name'] .' </label></div> '.$j_sub_files.' </div></div></div>';
                }
                elseif($sub_field['type'] == 'select'){

                    $required = '';
                    if($sub_field['required'] == '1'){
                        $required = 'required="required"';
                    }

                    $j_sub_files = '<select '.$required.' class="form-control" name="Ads[sub_fields]['. $sub_field['name_field'] . ']">';
                    for($j = 0; $j < count($j_values); $j++){
                        if(strcmp($j_values[$j], $values_sub_fields[$sub_field['name_field']]) == 0)
                            $j_sub_files .= '<option value="'. $j_values[$j] .'" selected> '. $j_keys[$j] . '</option>';
                        else $j_sub_files .= '<option value="'. $j_values[$j] .'"> '. $j_keys[$j] . '</option>';
                    }
                    $j_sub_files .= '</select>';
                    
                    $render_html .= '<div class="col-md-6 inputs_js"><div class="wrap-inputs"><div class="form-group"><div><label> '. $sub_field['name'] .' </label></div> '. $j_sub_files. ' </div></div></div>';

                }
                elseif($sub_field['type'] == 'select["multiple" => true]'){

                    $required = '';
                    if($sub_field['required'] == '1'){
                        $required = 'required="required"';
                    }

                    $j_sub_files = '<select '.$required.' class="form-control" name="Ads[sub_fields]['. $sub_field['name_field'] . '][]" multiple="multiple">';
                    for($j = 0; $j < count($j_values); $j++){
                        if(in_array($j_values[$j], $values_sub_fields[$sub_field['name_field']]))
                            $j_sub_files .= '<option value="'. $j_values[$j] .'" selected> '. $j_keys[$j] . '</option>';
                        else $j_sub_files .= '<option value="'. $j_values[$j] .'"> '. $j_keys[$j] . '</option>';
                    }
                    $j_sub_files .= '</select>';
                    
                    $render_html .= '<div class="col-md-6 inputs_js"><div class="wrap-inputs"><div class="form-group"><div><label> '. $sub_field['name'] .' </label></div> '. $j_sub_files. ' </div></div></div>';
                }
            }
            print_r($render_html);
		?>

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
						    'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions('elfinder',['preset' => 'basic']),
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
