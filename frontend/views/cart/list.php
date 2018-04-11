<?php
use \yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $products common\models\Product[] */
$this->params['breadcrumbs'][] = 'Корзина';
?>
<div class="container cart-list-table">
<h1 class="cart-list-table_ cart-list-table_h1">Корзина</h1>
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
<div class="col-md-2">
<span>Стоимость</span>
</div>
<div class="col-md-1">
</div>
</div>
</div>
<?php if(!empty($magazineProduct)): ?>
<?php foreach ($magazineProduct as $key => $value): ?>
<?php $_total = 0; ?>
<div class="row"><h3 class="cart-list-table_title cart-list-table_title_h3">Магазин: <?= $magazines[$key]['name'] ?></h3></div>
<?php foreach ($value as $product): ?>
<div class="row">
<div class="col-md-5">
<?= Html::encode($product->name) ?>
</div>
<div class="col-md-2">
<div class="cart-list-table_bold"><?= $product->price ?></div>
</div>
<div class="col-md-2">
<div class="cart-list-table_quantity-wrapper">
<span class="cart-list-table_quantity_span">
<?= $quantity = $product->getQuantity()?>
<?php $_total += $product->getQuantity() * $product->price; ?>
</span>
<div class="cart-list-table_quantity">
<?= Html::a('+', ['cart/update', 'id' => $product->getId(), 'quantity' => $quantity + 1], ['class' => 'btn cart-list-table_btn-green'])?>
<?= Html::a('-', ['cart/update', 'id' => $product->getId(), 'quantity' => $quantity - 1], ['class' => 'btn cart-list-table_btn-red', 'disabled' => ($quantity - 1) < 1])?>
</div>
</div>
</div>
<div class="col-md-2">
<div class="cart-list-table_bold"><?= $product->getCost() ?></div>
</div>
<div class="col-md-1">
<?= Html::a('×', ['cart/remove', 'id' => $product->getId()], ['class' => 'btn cart-list-table_danger'])?>
</div>
</div>
<?php endforeach; ?>
<div class="row">
<div class="col-md-9">
</div>
<div class="col-md-2">
<div class="cart-list-table_bold">Всего: <?= $_total ?></div>
</div>
<div class="col-md-1 cart-list-table_nopadding">
<?= Html::a('Купить', ['cart/order', 'id' => $key], ['class' => 'btn btn-success'])?>
</div>
</div>
<?php endforeach; ?>
<div class="row">
<div class="col-md-9">
</div>
<div class="col-md-2">
<div class="cart-list-table_bold">Всего: <?= $total ?></div>
</div>
<div class="col-md-1">
</div>
</div>
<?php else: ?>
<h3 class="text-center">В корзине сейчас пусто</h3>
<?php endif; ?>
</div>