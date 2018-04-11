<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\OrderP */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Order Ps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-p-view">

    <h1><?= Html::encode('View order #' . $model->id) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'created_at',
            'updated_at',
            'qty',
            'sum',
            [
                'attribute' => 'status',
                'value' => $model->status == 1 ? '<span class="text-success">Обработан</span>' : '<span class="text-danger">Необработан</span>',
                'format' => 'html',
            ],
//            'status',
            'name',
            'email:email',
            'phone',
            'address',
        ],
    ]) ?>

    <?php $item = $model->orderItem; ?>
    <div class="cart-page-area">
                <div class="table-responsive mb-20">
                    <table class="shop_table-2 cart table">
                        <thead>
                        <tr>
                            <th class="product-thumbnail ">Image</th>
                            <th class="product-name ">Product Name</th>
                            <th class="product-price ">Unit Price</th>
                            <th class="product-quantity">Quantity</th>
                            <th class="product-subtotal ">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($item as $product): ?>
                            <tr class="cart_item">
                                <td class="item-img">
<!--                                    <a href="--><?//= \yii\helpers\Url::to(['product/view', 'id' => $product['id']]);?><!--">-->
<!--                                        --><?php //echo \yii\helpers\Html::img("@web/images/product/{$product['image']}", ['alt' => $product['name']]);?>
<!--                                    </a>-->
                                </td>
                                <td class="item-title"> <a href="<?= \yii\helpers\Url::to(['/product/view', 'id' => $product['id_product']]);?>"><?=$product['name'];?></a></td>
                                <td class="item-price"> $<?=$product['price'];?> </td>
                                <td class="item-qty"><?=$product['qty_item'];?></td>
                                <td class="total-price"><strong> $<?=$product['price'] * $product['qty_item'];?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
    </div>
</div>
