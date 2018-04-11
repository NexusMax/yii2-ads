<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\MagazineEavFields;
/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineAds */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="magazine-ads-form">
    

    <?php if(Yii::$app->controller->action->id == 'copy'): ?>
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'],'action' => '/myaccount/magazine/ads/copy-save?id=' . $old_model->id]); ?>
    <?php else: ?>
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?php endif; ?>

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

    <?= $form->field($model, 'count')->textInput(['type' => 'number']) ?>

    <?php // $form->field($model, 'bargain')->checkbox() ?>

    <?php // $form->field($model, 'negotiable')->checkbox() ?>

    <?php // $form->field($model, 'type_payment')->dropDownList($model->getTypePayment()) ?>

    <?php // $form->field($model, 'type_delivery')->dropDownList($model->getTypeDelivery()) ?>

    <!-- <div class="wrap-city"> -->
        <?= $form->field($model, 'location')->textInput(['class' => 'form-control location-city']) ?>
        <?php // $form->field($model, 'city_id')->hiddenInput(['id' => 'ads-city_id'])->label(false) ?>
        <?php // $form->field($model, 'reg_id')->hiddenInput(['id' => 'ads-reg_id'])->label(false) ?>
        <!-- <div id="select-city" name="city" style="display: none;"></div> -->
    <!-- </div> -->

    <?php // $form->field($model, 'contact')->textInput(['maxlength' => true, 'value' => Yii::$app->user->identity->username . ' ' . Yii::$app->user->identity->lastname]) ?>

    <?php // $form->field($model, 'email')->textInput(['maxlength' => true, 'value' => Yii::$app->user->identity->email]) ?>
    
    <?php // $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'phone_2')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'phone_3')->textInput(['maxlength' => true]) ?>

    <?php //$form->field($model, 'type_ads')->dropDownList($model->getTypeAds()) ?>

    <?php $count = 0; ?>
    <?php if(!$model->isNewRecord || !empty($images)): ?>
        
        <?php 
            if(empty($images)){
                $images = $model->getImages();
            } 
        ?>

        <?php foreach ($images as $key): ?>
            <?php $count = $count + 1; ?>

            <div class="wrap-user-ac-img">
                <img class="user-ac-img" src="<?= $key->getUrl() ?>" alt="">
                <?php if($key->id): ?>
                    <i class="fa fa-times del-img-acccc" aria-hidden="true" data-id="<?= $key->id ?>" data-old="<?= $old_model->id ?>" data-model="<?= $model->id ?>"></i>
                <?php endif; ?>
            </div>

        <?php endforeach ?>

    <?php endif; ?>
        <div class="insert-kartinka">
            <?php for($i = 0; $i < 6 - $count; $i++): ?>
                <?= $form->field($model, 'imagesFiles[]')->fileInput(['accept' => 'image/*']) ?>
            <?php endfor; ?>
        </div>

    <?php if($model->isNewRecord): ?>
        <?php echo  MagazineEavFields::getHtmlFields($fields); ?>
    <?php else:?>
        <?php echo  MagazineEavFields::getHtmlField($model, false, $params); ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => 'btn j-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
