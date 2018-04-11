<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>


<?= $this->render('_header', [
	'magazineCategories' => $magazineCategories,
	'dataProvider' => $dataProvider,
	'model' => $model,
	'city' => $city,
]) ?>
<div class="container mb10">
	<div class="row">
		<div class="col-sm-4 col-md-6">
			<p class="shop-contacts_left-col">Телефон:</p><p> <?= $model['phone'] ?></p>
			<p class="shop-contacts_left-col">Телефон 2:</p><p> <?= $model['phone_2'] ?></p>
			<p class="shop-contacts_left-col">Режим работы:</p><p> с <?= $model['worked_start_at'] ?> до <?= $model['worked_end_at'] ?></p>
			<p class="shop-contacts_left-col">Адрес:</p><p> <?= $reg_name ?>,  <?= $city_name ?></p>
		</div>
		<div class="fad_map col-md-6">
			<?php if(!empty($city_name)): ?>
				<iframe style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA4ySfZlbdXq832ilqx-GcIk3tpmfnREHU
              &q=<?= $city_name ?>" allowfullscreen></iframe>
          <?php endif; ?>
        </div>
	</div>
</div>
<?= $this->render('_footer') ?>