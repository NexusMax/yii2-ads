<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<div class="magazine-price-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'plan_id')->dropDownList(ArrayHelper::map($plans,'id', 'name'), ['class' => 'light required']) ?>

    <?= $form->field($model, 'period_id')->dropDownList(ArrayHelper::map($periods,'id', 'name'), ['class' => 'light required']) ?>

    <?= $form->field($model, 'count_ads')->textInput() ?>

    <?= $form->field($model, 'top_30_day')->textInput() ?>

    <?= $form->field($model, 'design')->checkbox(['class' => 'boot-checkbox']) ?>

    <?= $form->field($model, 'price')->textInput() ?>
    
    <?= $form->field($model, 'old_price')->textInput() ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <?= $form->field($model, 'per_consult')->dropDownList([0 => 'Нет', 1 => 'Да'])->label('Персональный консультант (грн)') ?>

    <?= $form->field($model, 'ind_design')->textInput()->label('Индивидуальный дизайн (грн)') ?>

    <?= $form->field($model, 'dop_tov')->textInput()->label('Дополнительные товары, за штуку (грн)') ?>

    <?= $form->field($model, 'fire')->textInput()->label('Срочный товар') ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
