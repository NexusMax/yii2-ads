<?php
/* @var $order common\models\Order */
use yii\helpers\Html;
?>

<h1>Новый заказ #<?= $order->id ?></h1>

<h2>Contact</h2>

<ul>
    <li>Телефон: <?= Html::encode($order->phone) ?></li>
    <li>Емейл: <?= Html::encode($order->email) ?></li>
</ul>

<h2>Заметки</h2>

<?= Html::encode($order->notes) ?>

<h2>Товары</h2>

<ul>
<?php
$sum = 0;
foreach ($order->item as $item): ?>
    <?php $sum += $item->quantity * $item->price ?>
    <li><?= Html::encode($item->name . ' x ' . $item->quantity . ' x ' . $item->price . '$') ?></li>
<?php endforeach ?>
</ul>

<p><string>Сумма : </string> <?php echo $sum?>$</p>