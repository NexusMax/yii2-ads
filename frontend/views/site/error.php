<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <div class="error-text-404 col-md-12">
        <br>
        <span class="error"><?= Html::encode($this->title) ?></span>
    	<br>
    	<?= nl2br(Html::encode($message)) ?>
    	<br>

    	Пожалуйста, начните поиск с <a href="/">главной страницы</a>
    </div>

</div>
