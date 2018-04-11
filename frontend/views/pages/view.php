<?php 
use yii\helpers\Markdown;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container pahes">
	<?= Markdown::process($page['text']) ?>
	<div class="clearfix"></div>	
</div>	