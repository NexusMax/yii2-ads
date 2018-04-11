<?php
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $products common\models\Product[] */
$this->params['breadcrumbs'][] = ['label' => 'Корзина', 'url' => Url::to(['cart/list'])];
$this->params['breadcrumbs'][] = 'Заказ';
$_total = 0;
?>
<div class="container cart-list-table">
<h1 class="cart-list-table_h1"">Заказ</h1>
<div class="cart-list-table_top-row">
<div class="row">
<div class="col-md-5">
<span>Название</span>
</div>
<div class="col-md-2">
<span>Цена</span>
</div>
<div class="col-md-2">
<span>Количество</span>
</div>
<div class="col-md-1">
<span>Стоимость</span>
</div>
</div>
</div>
<?php foreach ($products as $product):?>
<div class="row">
<div class="col-md-5">
<?= Html::encode($product->name) ?>
</div>
<div class="col-md-2">
<div class="cart-list-table_bold"><?= $product->price ?></div>
<?php $_total += $product->price * $product->getQuantity()?>
</div>
<div class="col-md-2">
<?= $quantity = $product->getQuantity()?>
</div>
<div class="col-md-1">
<div class="cart-list-table_bold"><?= $product->getCost() ?></div>
</div>
</div>
<?php endforeach ?>
<div class="row">
<div class="col-md-9">
</div>
<div class="col-md-2">
<div class="cart-list-table_bold">Всего: <?= $_total ?></div>
</div>
</div>
</div>
<div class="container order-form-wrapper">
<div class="row">
<div class="col-md-12">
<!-- <?php print_r($order->errors) ?> -->
<?php
/* @var $form ActiveForm */
$form = ActiveForm::begin([
'id' => 'order-form',
]) ?>
<?= $form->field($order, 'phone')->textInput(['required' => 'required']) ?>
<?= $form->field($order, 'email')->textInput(['required' => 'required']) ?>
<?= $form->field($order, 'notes')->textarea() ?>
<div class="form-group row">
<div class="col-md-12">
<?= Html::submitButton('Купить', ['class' => 'btn btn-primary']) ?>
</div>
</div>
<?php ActiveForm::end() ?>
</div>
</div>
</div>