<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineSuccessPayed */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="magazine-success-payed-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'magazine_id')->textInput(['type' => 'number'])->label('ID магазина') ?>

    <?= $form->field($model, 'user_id')->textInput(['type' => 'number'])->label('ID пользователя') ?>

    <?= $form->field($model, 'sum')->textInput() ?>

    <?= $form->field($model, 'tarif_id')->dropDownList($tarif) ?>

    <?= $form->field($model, 'individual_template')->dropDownList([0 => 'Нет', 1 => 'Да']) ?>

    <?= $form->field($model, 'payed')->dropDownList([0 => 'Нет', 1 => 'Да']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
