<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\MagazineOrderItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="magazine-order-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group field-magazineorderitem-product_id has-success">
		<label class="control-label" for="magazineorderitem-product_id">Товар</label>
		<?= $model->product->name ?>
	</div>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
