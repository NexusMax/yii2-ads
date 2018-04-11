<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="magazine-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'magazine_id')->dropDownList(ArrayHelper::map($magazines,'id', 'name')) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatus()) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
