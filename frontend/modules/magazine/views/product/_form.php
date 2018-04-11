<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?php
//    echo $form->field($model, 'description')->widget(\mihaildev\ckeditor\CKEditor::className(),[
//        'editorOptions' => [
//            'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
//            'inline' => false, //по умолчанию false
//        ],
//    ]);
    ?>
    <?php
    echo $form->field($model, 'description')->widget(\mihaildev\ckeditor\CKEditor::className(), [
    'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions('elfinder',[/* Some CKEditor Options */]),
    ]);
    ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'hot')->dropDownList([ 'new' => 'New', 'sale' => 'Sale' ]) ?>

    <?= $form->field($model, 'image')->fileInput() ?>
    <?= $form->field($model, 'gallery[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <?= $form->field($model, 'image_2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image_3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image_4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image_5')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rating')->textInput() ?>

    <?= $form->field($model, 'new_arrival')->checkbox(['new_arrival' => 'New Arrival']) ?>

    <?= $form->field($model, 'best_seller')->checkbox(['best_seller' => 'Best Seller']) ?>

    <?= $form->field($model, 'special_offer')->checkbox(['special_offer' => 'Speacial offer']) ?>

    <?php //echo $form->field($model, 'category_id')->textInput() ?>
    <div class="form-group field-product-category_id has_success">
        <label for="product-category_id" class="control-label">Родительская категория</label>
        <select name="Product[category_id]" class="form-control" id="product-category_id">
            <?= \app\components\CategoryWidget::widget(['template' => 'widget-product-select', 'model' => $model]) ?>
        </select>
    </div>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'keyword')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
