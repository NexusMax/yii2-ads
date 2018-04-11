<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<div class="magazine-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map($categories,'id', 'name'), ['class' => 'light required form-control']) ?>

    <?php
        echo $form->field($model, 'desc')->widget(\mihaildev\ckeditor\CKEditor::className(),[
            'editorOptions' => [
                'preset' => 'standart', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
                'inline' => false, //по умолчанию false
                'removePlugins' => 'image, about',
            ],
        ])->label('О магазине');

    ?>


    <?= $form->field($model, 'template')->dropDownList(ArrayHelper::map($model->getTemplates(),'id', 'name'), ['class' => 'light required form-control']) ?>

    <?php // $form->field($model, 'tarif_plan')->dropDownList(ArrayHelper::map($plans,'id', 'name'), ['class' => 'light required form-control']) ?>
    
    <?= $form->field($model, 'deliveries')->checkboxList($deliveries) ?>

    <?= $form->field($model, 'payments')->checkboxList($payments) ?>

    <?= $form->field($model, 'active')->checkbox(['class' => 'boot-checkbox']) ?>

    <?= $form->field($model, 'contact')->textInput(['value' => Yii::$app->user->identity->username . ' ' . Yii::$app->user->identity->lastname]) ?>

    <?= $form->field($model, 'phone')->textInput() ?>

    <?= $form->field($model, 'phone_2')->textInput() ?>

    <?= $form->field($model, 'reg_id')->dropDownList(ArrayHelper::map([['id' => 0, 'name' => 'Выберите область']] + $reg, 'id', 'name'), ['class' => 'dropdown light form-control']) ?> 

    <?= $form->field($model, 'city_id')->dropDownList(ArrayHelper::map([['id' => 0, 'name' => 'Выберите область']] + $city, 'id', 'name'), ['class' => 'dropdown light form-control']) ?>
    
    <?= $form->field($model, 'worked_start_at')->textInput(['type' => 'time']) ?>
    
    <?= $form->field($model, 'worked_end_at')->textInput(['type' => 'time']) ?>


    <?php // $form->field($model, 'period')->dropDownList(ArrayHelper::map($periods,'id', 'name'), ['class' => 'light required form-control']) ?>
    
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
