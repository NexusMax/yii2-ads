<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\color\ColorInput;
?>

<div class="magazine-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map($categories,'id', 'name'), ['class' => 'light required']) ?>

    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'template')->dropDownList(ArrayHelper::map($model->getTemplates(),'id', 'name'), ['class' => 'light required']) ?>

    <?= $form->field($model, 'tarif_plan')->dropDownList(ArrayHelper::map($plans,'id', 'name'), ['class' => 'light required']) ?>
    
    <?= $form->field($model, 'deliveries')->checkboxList($deliveries) ?>

    <?= $form->field($model, 'payments')->checkboxList($payments) ?>

    <?= $form->field($model, 'active')->checkbox(['class' => 'boot-checkbox']) ?>

    <?= $form->field($model, 'period')->dropDownList(ArrayHelper::map($periods,'id', 'name'), ['class' => 'light required']) ?>

    <?= $form->field($model, 'reg_id')->dropDownList(ArrayHelper::map([['id' => 0, 'name' => 'Выберите область']] + $reg, 'id', 'name'), ['class' => 'dropdown light']) ?> 

    <?= $form->field($model, 'city_id')->dropDownList(ArrayHelper::map([['id' => 0, 'name' => 'Выберите область']] + $city, 'id', 'name'), ['class' => 'dropdown light']) ?>

    <?= $form->field($model, 'worked_start_at')->textInput(['type' => 'time']) ?>
    
    <?= $form->field($model, 'worked_end_at')->textInput(['type' => 'time']) ?>

    <?php 
    echo $form->field($model, 'background')->widget(ColorInput::classname(), [
        'options' => ['placeholder' => 'Select color ...'],
    ]);
    ?>


    <?php if(!$model->isNewRecord): ?>

        <div class="wrap-user-ac-img-ag">
            <?php if(!empty($model->background_url)): ?>
                <img class="user-ac-img" src="/web/uploads/magazinebackground/<?= $model->background_url ?>" alt="">
                <i class="fa fa-times del-img-acc-ag" aria-hidden="true" data-id="<?= $model->id ?>" data-model="<?= $model->id ?>"></i>
            <?php endif; ?>
            
        </div>

    <?php endif; ?>

    <?php echo $form->field($model, 'backgroundFile')->fileInput() ?>
    
    <?php if(!$model->isNewRecord): ?>

        <div class="wrap-user-ac-img">
            <img class="user-ac-img" src="<?= $model->image->getUrl() ?>" alt="">
            <?php if($model->image->id): ?>
                <i class="fa fa-times del-img-acc" aria-hidden="true" data-id="<?= $model->image->id ?>" data-model="<?= $model->id ?>"></i>
            <?php endif; ?>
        </div>

    <?php endif; ?>
    
    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
