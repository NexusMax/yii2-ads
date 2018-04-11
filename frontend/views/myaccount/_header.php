<?php 
use yii\helpers\Url;

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Menu;
use yii\widgets\Pjax;
use common\widgets\Breadcrumbs;

?>
<div class="background">
	<div class="container p-y-2">
		<?php Pjax::begin(['id' => 'myaccount', 'timeout' => 2000]); ?>
		<div class="account">

			<div class="pageinfo">
				<h2 class="x-large fbold lheight20"><?= Yii::$app->params['myaccount']['title'] ?></h2>
				<p class="small"><?= Yii::$app->params['myaccount']['sub_title'] ?></p>
			</div>

			<div class="balance">
				<small>Ваш счет:</small>
	
					<strong><?= Yii::$app->user->identity->balance ?> грн.</strong>
					<a href="#">Пополнить счет</a>
		
			</div>

			<div>


				<div class="empty">

						<?php if(!empty(Yii::$app->params['messages']['count'])){$messa = '('. Yii::$app->params['messages']['count'] . ')';} ?>
					<?php

					$menuItemsFavorite = [
				        [
				        	'label' => 'Избранные обьявления',
				        	'url' => ['/myaccount/favorite'], 
				        	'active'=> Yii::$app->controller->action->id == 'favorite',
				        ],
				    ];

				    $menuItems = [
				        [
				        	'label' => 'Объявления', 
				        	'url' => ['/myaccount/index'],
				        	'active' => 
				        		(Yii::$app->controller->action->id == 'index' && Yii::$app->controller->action->controller->id == 'myaccount') 
				        		|| (Yii::$app->controller->action->id == 'archive' && Yii::$app->controller->action->controller->id == 'myaccount'),
				        ],
				        [
				        	'label' => 'Магазины', 
				        	'url' => ['/myaccount/magazine'],
				        	'active' => Yii::$app->controller->action->controller->id == 'myacc-magazine' ||
				        		 Yii::$app->controller->action->controller->id == 'magazine-ads' ||
				        		 Yii::$app->controller->action->controller->id == 'magazine-order' ||
				        		 Yii::$app->controller->action->controller->id == 'magazine-order-item' ||
				        		 Yii::$app->controller->action->controller->id == 'magazine-has-categories',
				        ],
				        [
				        	'label' => 'Сообщения',
				        	'url' => ['/myaccount/messages'], 
				        	'active'=> (Yii::$app->controller->action->id == 'view' && Yii::$app->controller->action->controller->id == 'myaccount') || (Yii::$app->controller->action->id == 'messages' && Yii::$app->controller->action->controller->id == 'myaccount'),
				        	'template' => '<a href="{url}">{label}'.$messa.'</a>'."\n",
				        ],
				        ['label' => 'Платежи и счёт', 'url' => ['/myaccount/profile']],
				        ['label' => 'Настройки', 'url' => ['/myaccount/settings']],
				    ];

				    echo Menu::widget([
				        'options' => ['class' => 'nav nav-tabs'],
				        'items' => Yii::$app->controller->action->id == 'favorite' ? $menuItemsFavorite : $menuItems,
				    ]);
				    ?>

					<div id="box"></div>
					<!-- home -->
					<div class="tab-content">
					<?php if(Yii::$app->controller->action->controller->id == 'myaccount'): ?>
						<?php if(Yii::$app->controller->action->id == 'index' || Yii::$app->controller->action->id == 'archive'): ?>
							<div class="sub-nav">

								<?php 

									if(!empty(Yii::$app->params['myaccount']['active_ads'])){
										$active_ads = '(' . Yii::$app->params['myaccount']['active_ads'] . ')';
									}
									if(!empty(Yii::$app->params['myaccount']['disactive_ads'])){
										$disactive_ads = '(' . Yii::$app->params['myaccount']['disactive_ads'] . ')';
									}

									$menuItems = [
										[
											'label' => 'Активные', 
											'url' => ['/myaccount/index'],
											'template' => '<a href="{url}">{label} '.$active_ads.'</a>'."\n",
										],
					        			[
					        				'label' => 'Неактивные', 
					        				'url' => ['/myaccount/archive'],
											'template' => '<a href="{url}">{label} '.$disactive_ads.'</a>'."\n",
					        			],
									];

									echo Menu::widget([
								        'options' => ['class' => ''],
								        'items' => $menuItems,
							    	]);
							    ?>
								
								
								<form method="get" data-pjax="1" action="/<?= 'myaccount/' . Yii::$app->controller->action->id ?>">
									<input type="text" name="SearchMyaccount[q]" value="<?= Yii::$app->request->get('SearchMyaccount')['q'] ?>" placeholder="Поиск..">
									<button><i class="fa fa-search" aria-hidden="true"></i></button>
								</form>

							</div>
							<div class="ads-information"><?= Yii::$app->params['myaccount']['inform'] ?></div>
						<?php endif; ?>
					<?php endif; ?>

					<?php if(
						Yii::$app->controller->action->controller->id == 'myacc-magazine' ||
						Yii::$app->controller->action->controller->id == 'magazine-ads' ||
						Yii::$app->controller->action->controller->id == 'magazine-order' ||
						Yii::$app->controller->action->controller->id == 'magazine-order-item' ||
						Yii::$app->controller->action->controller->id == 'magazine-has-categories'
						): 
					?>
					
					<?= Breadcrumbs::widget([
						'options' => ['class' => 'breadcrumb magazine'],
					    'homeLink' => ['label' => 'Мои магазины', 'url' => '/myaccount/magazine'],
					    'links' => isset($this->params['breadcrumbss']) ? $this->params['breadcrumbss'] : [],
					]) ?>

					<?php endif; ?>