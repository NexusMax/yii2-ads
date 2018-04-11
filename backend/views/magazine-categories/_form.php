<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

?>

<div class="magazine-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'active')->checkbox(['class' => 'boot-checkbox']) ?>

    <?php 

    if(!empty($model->image)){
        echo $form->field($model, 'imageFile')->widget(FileInput::classname(), [
             'options' => ['accept' => 'image/*'],
             'pluginOptions' => [
                'initialPreview'=> [$model->getImage()],
                'initialPreviewAsData' => true,
                'initialPreviewConfig'=> $model->getInitImage(),
            ],
         ]);
     }else{
         echo $form->field($model, 'imageFile')->widget(FileInput::classname(), [
             'options' => ['accept' => 'image/*']
         ]);
     }
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
