<?php 

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => Url::to(['myaccount/index'])];
$this->params['breadcrumbs'][] = ['label' => htmlspecialchars_decode($ad['name']), 'url' => ['view', 'alias' => $ad['alias']]];
$this->params['breadcrumbs'][] = ['label' => 'Рекламировать', 'url' => ['ads/reklama', 'alias' => $ad['alias']]];
$this->params['breadcrumbs'][] = 'Пакет услуг';
?>

			<div class="p-y-2">
				<div class="container no_padding">
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-warning" style="text-align: center;">
								<p>Спасибо за размещение объявления</p>
								<p>Вы сможете снова разместить бесплатное объявление в этой рубрике ноябрь 28. Если вам необходимо разместить объявления уже сейчас, пожалуйста, выберите один из пакетов ниже:</p>
							</div>
						</div>
						<div class="col-md-12">
							<div class="line-with-circle">
								<div class="blue-circle">1</div>
								<span>Выбрать пакет</span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="select-package">
								<div class="select-package__header">
									<span>Старт</span>
									<select name="" id="">
										<option value="">40 размещений</option>
										<option value="">80 размещений</option>
										<option value="">120 размещений</option>
									</select>
								</div>
								<div class="select-package__main clearfix">
									<ul>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>Статистика объявлений за 60 дней</li>
										<li><i class="fa fa-check-circle" aria-hidden="true"></i>Бизнес-страница</li>
										<li class="not-active"><i class="fa fa-check-circle" aria-hidden="true"></i>Рекомендованные объявления на странице магазина</li>
										<li class="not-active"><i class="fa fa-check-circle" aria-hidden="true"></i>Фильтр объявлений в профиле</li>
										<li class="not-active"><i class="fa fa-check-circle" aria-hidden="true"></i>XML-импорт объявлений</li>
										<p>Срок действия пакета 30 дней</p>
									</ul>

									<div class="select-package__main-right">
										<div class="s-item__price">26.23<span class="s-item__price-currency">грн.</span></div>
										<p>за размещение</p>
										<div class="s-item__pricesm">1049<span class="s-item__price-currencysm">грн./пакет</span></div>
										<div class="btn j-primary">Купить</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="select-package">
							<div class="select-package__header">
								<span>премиум</span>
								<select name="" id="">
									<option value="">50 размещений</option>
									<option value="">10 размещений</option>
									<option value="">150 размещений</option>
								</select>
							</div>
							<div class="select-package__main clearfix">
								<ul>
									<li><i class="fa fa-check-circle" aria-hidden="true"></i>Статистика объявлений за 90 дней</li>
									<li><i class="fa fa-check-circle" aria-hidden="true"></i>Бизнес-страница</li>
									<li><i class="fa fa-check-circle" aria-hidden="true"></i>Рекомендованные объявления на странице магазина</li>
									<li><i class="fa fa-check-circle" aria-hidden="true"></i>Фильтр объявлений в профиле</li>
									<li><i class="fa fa-check-circle" aria-hidden="true"></i>XML-импорт объявлений</li>
									<p>Срок действия пакета 30 дней</p>
								</ul>

								<div class="select-package__main-right">
									<div class="s-item__price">25.98<span class="s-item__price-currency">грн.</span></div>
									<p>за размещение</p>
									<div class="s-item__pricesm">1299<span class="s-item__price-currencysm">грн./пакет</span></div>
									<div class="btn j-primary">Купить</div>
								</div>
								</div>
							</div>
						</div>
							<div class="row">
								<div class="col-md-12">
									<a href="<?= Url::to(['myaccount/index']) ?>" class="btn j-primary" style="float: right; margin: 20px 9px;">Нет, спасибо</a>
								</div>
							</div>		
				</div> <!--end container -->
			</div>
			