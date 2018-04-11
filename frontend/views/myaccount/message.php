<?= $this->render('_header') ?>
<?php 
$app = Yii::$app;

use yii\helpers\Url;

if(!empty(Yii::$app->params['messages']['count_my'])){
	$my_messa = '('. Yii::$app->params['messages']['count_my'] . ')';
}

if(!empty(Yii::$app->params['messages']['count_send'])){
	$send_messa = '('. Yii::$app->params['messages']['count_send'] . ')';
}

?>
<div role="tab-messages">
	<!-- messages -->
	<ul class="nav nav-messages" role="tablist">
		<li role="presentation" class="active"><a href="#answer" class="active" aria-controls="answer" role="tab" data-toggle="tab">Полученные<?= $my_messa ?></a></li>
		<li role="presentation"><a href="#send" aria-controls="send" role="tab" data-toggle="tab">Отправленные<?= $send_messa ?></a></li>
		<li role="presentation"><a href="#archived" aria-controls="archived" role="tab" data-toggle="tab">Архивные</a></li>
	</ul>
	<div class="tab-content tab-messages">
		<div role="tab-messages" class="tab-pane active" id="answer">

			<div class="content">
				<?php if(empty($my_messages)): ?>
				<div class="row">
					<div class="non-sms-margin">
						<i class="fa fa-5x fa-envelope" aria-hidden="true"></i>
						<h3 class="x-large2">Нет сообщений</h3>
					</div>
				</div>
				<?php else: ?>

					<div class="row myaccount-top-row">
						<div class="col-md-2">Дата</div>
						<div class="col-md-2">От кого</div>
						<div class="col-md-4">Объявление</div>
						<div class="col-md-2 mb">Сообщения</div>
						<div class="col-md-2">Статус</div>
					</div>

					<?php foreach ($my_messages as $key): ?>

						<?php 

							time() - $key['created_at'] > 604800 ? $time = $app->formatter->asDate($key['created_at'], 'php:d/m/Y H:i') : $time = $app->formatter->format($key['created_at'], 'relativeTime');
				            $key['unread'] == 0 ? $read = 'Не прочитано' : $read = 'Прочитано';

				            if(!empty(Yii::$app->params['messages'][$key['ads_id']]['unread'])){
				            	$messa = '('. Yii::$app->params['messages'][$key['ads_id']]['unread'] . ')';
				            }

				            if(mb_strlen($key['name']) > 25)
				            	$key['name'] = mb_substr($key['name'], 0, 25) . '...';

						?>


					<div class="row myaccount-msg-row">
						<div class="col-md-2 mb"><?= $time ?></div>
						<div class="col-md-2 mb">
							<?php if(!empty($key['magazine_name'])): ?>
								Магазин (<?= $key['magazine_name'] ?>) 
							<?php else: ?>
								<?= $key['username'] ?> <?= $key['lastname'] ?>
							<?php endif; ?>
						</div>
						<div class="col-md-4 mb">
							<?php if(!empty($key['magazine_name'])): ?>
							<a href="<?= Url::to(['magazine/product', 'alias' => $key['alias']]) ?>" title="<?= $key['name'] ?>"><?= $key['name'] ?></a>
							<?php else: ?>
							<a href="<?= Url::to(['ads/view', 'alias' => $key['alias']]) ?>" title="<?= $key['name'] ?>"><?= $key['name'] ?></a>
							<?php endif; ?>
						</div>
						<div class="col-md-2 mb my-account-messages"><a href="/myaccount/messages/<?= $key['id_messages'] ?>"><i class="fa fa-envelope fa-hover-hidden fa-fw" aria-hidden="true"></i><i class="fa fa-envelope-open fa-hover-show fa-fw" aria-hidden="true"></i></a>
						<a href="<?= Url::to(['myaccount/delete-message', 'id' => $key['id_messages']]) ?>" title="Удалить" aria-label="Удалить" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><i class="fa fa fa-trash" aria-hidden="true"></i></a>
						</div>
						<div class="col-md-2 mb"><?= $read ?> <?= $messa ?></div>
					</div>

					<?php endforeach; ?>
					
				<?php endif; ?>

			</div>

		</div>

		<div role="tab-messages" class="tab-pane" id="send">


			<div class="content">
				<?php if(empty($send_messages)): ?>
				<div class="row">
					<div class="non-sms-margin">
						<i class="fa fa-5x fa-envelope" aria-hidden="true"></i>
						<h3 class="x-large2">Нет сообщений</h3>
					</div>
				</div>
				<?php else: ?>

					<div class="row myaccount-top-row">
						<div class="col-md-2">Дата</div>
						<div class="col-md-2">Кому</div>
						<div class="col-md-4">Объявление</div>
						<div class="col-md-2 mb">Сообщения</div>
						<div class="col-md-2">Статус</div>
					</div>

					<?php foreach ($send_messages as $key): ?>

						<?php 

							time() - $key['created_at'] > 604800 ? $time = $app->formatter->asDate($key['created_at'], 'php:d/m/Y H:i') : $time = $app->formatter->format($key['created_at'], 'relativeTime');
				            $key['unread'] == 0 ? $read = 'Не прочитано' : $read = 'Прочитано';

				            if(!empty(Yii::$app->params['messages'][$key['ads_id']]['unread'])){
				            	$messa = '<span class="badge my-badge">'. Yii::$app->params['messages'][$key['ads_id']]['unread'] . '</span>';
				            }

				            if(mb_strlen($key['name']) > 25)
				            	$key['name'] = mb_substr($key['name'], 0, 25) . '...';

						?>


					<div class="row myaccount-msg-row">
						<div class="col-md-2 mb"><?= $time ?></div>
						<div class="col-md-2 mb">
							<?php if(!empty($key['magazine_name'])): ?>
								Магазин (<?= $key['magazine_name'] ?>) 
							<?php else: ?>
								<?= $key['username'] ?> <?= $key['lastname'] ?>
							<?php endif; ?>
								
						</div>
						<div class="col-md-4 mb">
							<?php if(!empty($key['magazine_name'])): ?>
							<a href="<?= Url::to(['magazine/product', 'alias' => $key['alias']]) ?>" title="<?= $key['name'] ?>"><?= $key['name'] ?></a>
							<?php else: ?>
							<a href="<?= Url::to(['ads/view', 'alias' => $key['alias']]) ?>" title="<?= $key['name'] ?>"><?= $key['name'] ?></a>
							<?php endif; ?>
						</div>
						<div class="col-md-2 mb my-account-messages"><a href="/myaccount/messages/<?= $key['id_messages'] ?>"><i class="fa fa-envelope fa-hover-hidden fa-fw" aria-hidden="true"></i><i class="fa fa-envelope-open fa-hover-show fa-fw" aria-hidden="true"></i></a>
						<a href="<?= Url::to(['myaccount/delete-message', 'id' => $key['id_messages']]) ?>" title="Удалить" aria-label="Удалить" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><i class="fa fa fa-trash" aria-hidden="true"></i></a>
						</div>
						<div class="col-md-2 mb"><?= $read ?> <?= $messa ?></div>
					</div>

					<?php endforeach; ?>
					
				<?php endif; ?>

			</div>

		
		</div>
		<div role="tab-messages" class="tab-pane" id="archived">

			<div class="content">
					<div class="row">
						<div class="non-sms-margin">
							<i class="fa fa-5x fa-envelope" aria-hidden="true"></i>
							<h3 class="x-large2">Нет сообщений</h3>
						</div>
					</div>
				</div>

		</div>
	</div>
</div>
<?= $this->render('_footer'); ?>