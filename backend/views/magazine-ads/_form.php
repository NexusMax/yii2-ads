<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use frontend\models\MagazineEavFields;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineAds */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="magazine-ads-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group field-magazineads-category_id required">
        <label class="control-label" for="magazineads-category_id"><?= $model->attributeLabels()['category_id'] ?></label>
        <select id="magazineads-category_id" class="form-control" name="MagazineAds[category_id]" aria-required="true" aria-invalid="true">
            <option value="0">Выбрать категорию</option>
            <?= \backend\components\CategoryWidget::widget(['template' => 'widget-product-select', 'model' => $model, 'magazine_id' => $model->magazine_id]) ?>
        </select>
        <div class="help-block"></div>
    </div>


    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'price')->textInput(['type' => 'number']) ?>

    <?php // $form->field($model, 'bargain')->checkbox() ?>

    <?php // $form->field($model, 'negotiable')->checkbox() ?>

    <?php // $form->field($model, 'type_payment')->dropDownList($model->getTypePayment()) ?>

    <?php // $form->field($model, 'type_delivery')->dropDownList($model->getTypeDelivery()) ?>

    <div class="wrap-city">
        <?php echo $form->field($model, 'location')->textInput(['class' => 'form-control location-city', 'autocomplete' => 'off']) ?>
        <?php // $form->field($model, 'city_id')->hiddenInput(['id' => 'ads-city_id'])->label(false) ?>
        <?php // $form->field($model, 'reg_id')->hiddenInput(['id' => 'ads-reg_id'])->label(false) ?>
        <div id="select-city" name="city" style="display: none;"></div>
    </div>

    <?php // $form->field($model, 'contact')->textInput(['maxlength' => true, 'value' => Yii::$app->user->identity->username . ' ' . Yii::$app->user->identity->lastname]) ?>

    <?php // $form->field($model, 'email')->textInput(['maxlength' => true, 'value' => Yii::$app->user->identity->email]) ?>
    
    <?php // $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '+380999999999']) ?>

    <?php // $form->field($model, 'phone_2')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '+380999999999']) ?>

    <?php // $form->field($model, 'phone_3')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '+380999999999']) ?>

    <?php // $form->field($model, 'type_ads')->dropDownList($model->getTypeAds()) ?>

    <?php if(!$model->isNewRecord): ?>

        <?= $form->field($model, 'imagesFiles[]')->widget(FileInput::classname(), [
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
        ]); ?>

    <?php else: ?>

        <?= $form->field($model, 'imagesFiles[]')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*', 'multiple' => true],
                'language' => 'ru',
                'pluginOptions' => ['previewFileType' => 'any', 'uploadUrl' => yii\helpers\Url::to(['/site/file-upload']),]
        ]); ?> 

    <?php endif; ?>
    

    <?php if($model->isNewRecord): ?>
        <?php echo  MagazineEavFields::getHtmlFields($fields); ?>
    <?php else:?>
        <?php echo  MagazineEavFields::getHtmlField($model, false, $params); ?>
    <?php endif; ?>

    


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
