<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Смена пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container" style="height: 42.5vh;">
    <div class="site-reset-password">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>Пожалуйста введите свой новый пароль!</p>

        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                    <?= $form->field($model, 'password')->passwordInput(['autofocus' => true])->label(false) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn j-success']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
