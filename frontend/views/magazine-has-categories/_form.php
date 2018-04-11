<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineHasCategories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="magazine-has-categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group field-magazinehascategories-parent_id">
        <label class="control-label" for="magazinehascategories-parent_id">Категория</label>
        <select id="magazinehascategories-parent_id" class="form-control" name="MagazineHasCategories[parent_id]" aria-invalid="false">
            <option value="0">Корневая категория</option>
            <?= \backend\components\CategoryWidget::widget(['template' => 'widget-category-select', 'model' => $model, 'magazine_id' => $model->magazine_id]) ?>
        </select>
        <div class="help-block"></div>
    </div>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'active')->checkbox() ?>

    <?php if(!$model->isNewRecord): ?>
        <?= $form->field($model, 'sort')->textInput() ?>
    <?php endif; ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' =>'btn j-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
