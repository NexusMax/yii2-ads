<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Sign In';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<style>
input.form-control{
    padding: 24px 10px;
    font-size: 16px;
}
.login-page, .register-page{
    background-color: #fff;
}
.login-box-body{
    text-align: center;
    background: #eee;
}
.login-img{
margin: 0 0 20px 0;
width: 70px;
}
.btn.btn-primary{
    background: #3498db;
    height: 50px;
    transition: ease .5s;
    border: none;
    font-size: 16px;
}
    .btn.btn-primary:hover{
            background: #6dbef5;
    }
    .scsmsg{
            background: #97cf26;
    color: #fff;
    text-align: center;
    padding: 20px 0;
    transition: 0.5s;
    margin-top: 0!important;
    /*position: absolute;*/
    width: 100%;
    top: 0;
    }
    .scsmsg p{
        margin: 16px 0;
        font-size: 16px; 
    }
    .form-group span{
        display: none;
    }
</style>
<div class="container scsmsg">
    <div class="row">
        <div class="col-md-12">
            <p>Добро пожаловать !</p>
        </div>
    </div>
</div>
<div class="login-box">
    <!-- /.login-logo -->
    <div class="login-box-body">
<!--         <p class="login-box-msg">Sign in to start your session</p> -->
        <img src="/admin/assets/jlogo.png" class="login-img" alt="">
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => 'Логин']) ?>

        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => 'Пароль']) ?>

        <div class="row">
            <div class="col-xs-8" style="display: none;">
                <?= $form->field($model, 'rememberMe')->checkbox()->label('Запомнить меня') ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-12">
                <?= Html::submitButton('Вход', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>


        <?php ActiveForm::end(); ?>

    </div>

</div>
