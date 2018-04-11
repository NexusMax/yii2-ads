<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Markdown;
?>


<?= $this->render('_header', [
	'magazineCategories' => $magazineCategories,
	'dataProvider' => $dataProvider,
	'model' => $model,
	'city' => $city,
]) ?>
<div class="container mb10  mag-about_wrapper">
	<div class="row">
		<div class="col-sm-4 col-md-12">
			<?= Markdown::process($model['desc']) ?>
			
		</div>
	</div>
</div>
<?= $this->render('_footer') ?>