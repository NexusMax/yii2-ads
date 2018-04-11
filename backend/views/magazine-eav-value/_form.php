<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineEavValue */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="magazine-eav-value-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'field_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'option_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
