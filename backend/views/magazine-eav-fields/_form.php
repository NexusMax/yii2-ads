<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineEavFields */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="magazine-eav-fields-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_field')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_id')->dropDownList(ArrayHelper::map($types, 'id', 'name')) ?>

    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map($categories, 'id', 'name')) ?>

    <?= $form->field($model, 'active')->checkbox() ?>

    <?= $form->field($model, 'search')->checkbox() ?>

    <div id="drag" class="dropdown-fields-wrap text-center">
        <a href="#" class="dropdown-fields btn-sm btn-primary">Добавить значения</a>

        <?php if(!empty($model->opts)): ?>

            <?php for ($i = 0; $i < count($model->opts); $i++): ?>
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group text-left">
                            <label for="exampleInputName1">Название</label>
                            <input type="text" name="Fields[sub_field][]" value="<?php echo $model['opts'][$i]['name']; ?>" class="form-control" id="exampleInputName1">
                        </div>
                    </div>
                    <div class="col-md-1"><span class="glyphicon glyphicon-move" aria-hidden="true"></span></div>
                    <div class="col-md-1"><a href="#" class="dropdown-field-delete btn-sm btn-danger">X</a></div>
                </div>
            <?php endfor; ?>

        <?php endif; ?> 
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
