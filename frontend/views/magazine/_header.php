<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\widgets\Menu;
use common\widgets\Breadcrumbs;

$this->params['breadcrumbss-'][] = ['label' => 'Магазины', 'url' => Url::to(['magazine/shops'])];
$this->params['breadcrumbss-'][] = $model['name'];


$style = '';
if($model->template == 1){
	$style= '<style>.magazin-page_wrapper{background-color: #0f3752}</style>';
}
if($model->template == 2){
	$style= '<style>.magazin-page_wrapper{background-color: #f1f1f1}</style>';
}
if($model->template == 4){
	$style= '<style>.magazin-page_wrapper{background-color: #f3f0e1}</style>';
}
if(!empty($model->background)){
	$style= '<style>.magazin-page_wrapper{background-color: ' . $model->background . '}</style>';
}
if(!empty($model->background_url)){
	$style= '<style>
	.magazin-page_wrapper{
		background: url(/web/uploads/magazinebackground/' . $model->background_url . ');
		background-position: top center;
    	background-size: cover;
	}
	</style>';
}

?>

<?php Pjax::begin(['timeout' => 5000]); ?>
<?= $style ?>

<div class="container" style="    padding-top: 23px;">
<?= Breadcrumbs::widget([
'homeLink' => ['label' => 'Jandooo', 'url' => '/'],
'links' => isset($this->params['breadcrumbss-']) ? $this->params['breadcrumbss-'] : [],
]) ?>

</div>

<div class="magazin-page_wrapper">
<div class="container magazin-page_container">
<div class="container mag-view-wr mag-view-header">
	<div class="row">
		<div class="col-md-4 mag-view-header_col1">
			<div class="mag-view-header_logo">
				<img src="<?= !empty($model->imageRico) ? $model->imageRico->getUrl() : Yii::$app->params['placeholder'] ?>" alt="...">
			</div>
			<div class="mag-view-header_foundation">
				<p><img src="/images/jando_icon.png" alt=""><?= 'На Jandooo c ' . Yii::$app->formatter->asDate($model['created_at'], 'long') ?></p>
			</div>
		</div>
		<div class="col-md-4 text-center mag-view-header_col2">
			<h3 class="magazine-title"><?= $model['name'] ?></h3>
		</div>
		<div class="col-md-4 mag-view-header_col3">
			<div class="row">
				<div class="mag-view-header_phone col-md-12">
					<address><i class="iconer fa fa-phone"></i><?= $model['phone'] ?></address>
					<address><i class="iconer fa fa-phone"></i><?= $model['phone_2'] ?></address>
				</div>

				<div class="col-md-12 mag-view_working-hours">
					<p><i class="iconer fa fa-clock-o"></i>Режим работы:
					с <?= $model['worked_start_at'] ?>
					до <?= $model['worked_end_at'] ?></p>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="shop-magazine-wrap">
    <div class="search-block container">
        <div class="container col-md-12 p-y-2">
            <?php echo $this->render('../magazine-ads/_search', ['magazineCategories' => $main_categories, 'city' => $city]); ?>
        </div>
    </div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<p class="count-mag">Все объявленя <span><?= $dataProvider->getCount() ?></span></p>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ul class="magazine-cat">
				<?php foreach ($magazineCategories as $key): ?>
				<li><a href="<?= Url::to(['magazine/view', 'alias' => $model['alias'], 'category_id' => $key['id']]) ?>"><?= $key['name'] ?></a> <span class="count-cat"><?= intval($key['count']) ?></span></li>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
</div>

<div class="container mag-widget_wrapper">
	<div class="row">
		<div class="col-md-12">
			<?php 
				$menuItems = [
					[
						'label' => 'Товары', 
						'url' => Url::to(['magazine/view', 'alias' => $model['alias']]),
						'options' => ['class' => 'item-menu-mag'],
						'active'=> Yii::$app->controller->action->id == 'view',
					],
					[
        				'label' => 'О магазине', 
        				'url' => Url::to(['magazine/about', 'alias' => $model['alias']]),
        				'options' => ['class' => 'item-menu-mag'],
        				'active'=> Yii::$app->controller->action->id == 'about',
        			],
        			[
        				'label' => 'Контакты', 
        				'url' => Url::to(['magazine/contact', 'alias' => $model['alias']]),
        				'options' => ['class' => 'item-menu-mag'],
        				'active'=> Yii::$app->controller->action->id == 'contact',
        			],
				];

				echo Menu::widget([
			        'options' => ['class' => 'ul-menu-mag'],
			        'items' => $menuItems,
		    	]);
		    ?>

		    <div class="magazine-sort">
		    	<?php if(!empty($sort)): ?>
			    	<?= $sort->link('created_at') ?>
			    	<?= $sort->link('price') ?>
			    <?php endif; ?>
		    </div>
		</div>
	</div>
</div>