<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbss'][] = ['label' => $magazine['name'], 'url' => ['/myaccount/magazine/view', 'id' => $magazine['id']]];
$this->params['breadcrumbss'][] = $this->title;
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-has-categories-index">

    <p>
        <?= Html::a('Добавить категорию', ['create', 'id' => $magazine['id']], ['class' => 'btn btn-sm j-success']) ?>
    </p>
    <div id="w0" class="grid-view">
        <table class="myoffersnew small active">
            <thead>
                <tr>
                    <th>#</th>
            
                    <th>Подкатегория</th>
                    <th>Категория</th>
                    <th><a href="/myaccount/magazine/magazine-has-categories?id=24&amp;sort=alias" data-sort="alias">Алиас</a></th>
                    <th class="action-column">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($model as $key => $value): ?>
                <tr class="tr row-elem" data-key="<?= $value['id'] ?>">
                    <td><?= $i++ ?> <?= !empty($value['childs']) ? '<i class="fa fa-plus click-childs" data-class="toggle_' . $value['id'] . '" aria-hidden="true"></i>' : '' ?></td>
            
                    <td><?= $value['name'] ?></td>
                    <td>Главная категория</td>
                    <td><?= $value['alias'] ?></td>
                    <td>
                        <a class="pay-a" href="/myaccount/magazine/magazine-has-categories/view?id=<?= $value['id'] ?>"><span class="span-rel"><i class="big-i fa fa-external-link" aria-hidden="true"></i><p>Просмотр</p></span></a>
                        <a class="pay-a" href="/myaccount/magazine/magazine-has-categories/update?id=<?= $value['id'] ?>"><span class="span-rel"><i class="big-i fa fa-pencil" aria-hidden="true"></i><p>Редактировать</p></span></a>
                        <a class="pay-a" href="/myaccount/magazine/magazine-has-categories/delete?id=<?= $value['id'] ?>" data-method="POST"><span class="span-rel"><i class="big-i fa fa-times" aria-hidden="true"></i><p>Удалить</p></span></a>
                    </td>
                </tr>
                <?php $y = 1; ?>
                    <?php foreach ($value['childs'] as $val => $vl): ?>
                        <tr class="tr row-elem childs toggle_<?= $value['id'] ?> toggle_class" data-key="<?= $vl['id'] ?>" style="display: none;">
                            <td style="background-color: #f1f1f1;"><?= $y++ ?></td>

                            <td style="background-color: #f1f1f1;"><?= $vl['name'] ?></td>
                            <td style="background-color: #f1f1f1;"><?= $value['name'] ?></td>
                            <td style="background-color: #f1f1f1;"><?= $vl['alias'] ?></td>
                            <td style="background-color: #f1f1f1;">
                                <a class="pay-a" href="/myaccount/magazine/magazine-has-categories/view?id=<?= $vl['id'] ?>"><span class="span-rel"><i class="big-i fa fa-external-link" aria-hidden="true"></i><p>Просмотр</p></span></a>
                                <a class="pay-a" href="/myaccount/magazine/magazine-has-categories/update?id=<?= $vl['id'] ?>"><span class="span-rel"><i class="big-i fa fa-pencil" aria-hidden="true"></i><p>Редактировать</p></span></a>
                                <a class="pay-a" href="/myaccount/magazine/magazine-has-categories/delete?id=<?= $vl['id'] ?>" data-method="POST"><span class="span-rel"><i class="big-i fa fa-times" aria-hidden="true"></i><p>Удалить</p></span></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>    
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>
