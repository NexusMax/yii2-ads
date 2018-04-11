<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineHasPayment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="magazine-has-payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if(!$model->isNewRecord): ?>
        <!-- <label>Магазин </label> <?php // $magazines['name'] ?><br> -->
        <!-- <label>Тип оплаты </label> <?php // $payments['name'] ?> -->
    <?php else: ?>
        <?= $form->field($model, 'magazine_id')->dropDownList(ArrayHelper::map($magazines,'id', 'name')) ?>
        <?= $form->field($model, 'payment_id')->checkboxList(ArrayHelper::map($payments,'id', 'name')) ?>
    <?php endif; ?>

    <?= $form->field($model, 'public_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'private_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'card')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
