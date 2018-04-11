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
        <label class="control-label" for="magazinehascategories-parent_id">Родительская категория</label>
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

    <?php if(!$model->isNewRecord): ?>

        <div class="wrap-user-ac-img">
            <img class="user-ac-img" src="<?= $model->image->getUrl() ?>" alt="">
            <?php if($model->image->id): ?>
                <i class="fa fa-times del-img-accc" aria-hidden="true" data-id="<?= $model->image->id ?>" data-model="<?= $model->id ?>"></i>
            <?php endif; ?>
        </div>

    <?php endif; ?>
    
    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
