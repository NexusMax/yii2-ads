<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
?>
<?php $form = ActiveForm::begin([
'enableAjaxValidation' => false,
'enableClientValidation' => true,
'options' => ['class' => 'form-inline container-fluid search', 'data-pjax' => '#p0',],
'fieldConfig' => [
'options' => [
'class' => 'form-group p-x-1'
],
],
'enableClientValidation' => false,
'id' => 'search-form'
]);?>
<?php
$q = strtr(mb_substr(Yii::$app->request->get('q'), 2), ['-' => ' ']);
$c = strtr(mb_substr(Yii::$app->request->get('reg'), 2), ['-' => ' ']);
?>
<?= $form->field($model, 'q')->textInput(['class' => 'form-control', 'placeholder' => 'Название товара или услуги', 'value' => $q])->label(false) ?>
<?= $form->field($model, 'city')->textInput(['class' => 'form-control ', 'placeholder' => 'Вся Украина', 'value' => $c])->label(false) ?>
<input type="hidden" name="cat" value="<?= Yii::$app->request->get('cat') ?>">
<input type="hidden" name="subcat" value="<?= Yii::$app->request->get('subcat') ?>">
<div class="regions-layer" style="display: none;">
<div class="regionSearchBox">
<a href="" class="all-ukr">Вся Украина</a>
</div>
<div class="regionList" >
<ul>
<li><a href="" rel="nofollow" class="search-link" data-id="279">Винницкая область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="280">Волынская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="281">Днепропетровская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="282">Донецкая область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="283">Житомирская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="284">Закарпатская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="285">Запорожская область</a></li>
</ul>
<ul>
<li><a href="" rel="nofollow" class="search-link" data-id="286">Ивано-Франковская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="287">Киевская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="288">Кировоградская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="289">Крым</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="290">Луганская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="291">Львовская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="292">Николаевская область</a></li>
</ul>
<ul>
<li><a href="" rel="nofollow" class="search-link" data-id="293">Одесская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="294">Полтавская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="295">Ровенская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="296">Сумская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="297">Тернопольская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="298">Харьковская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="299">Херсонская область</a></li>
</ul>
<ul>
<li><a href="" rel="nofollow" class="search-link" data-id="300">Хмельницкая область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="301">Черкасская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="302">Черниговская область</a></li>
<li><a href="" rel="nofollow" class="search-link" data-id="303">Черновицкая область</a></li>
</ul>
</div>
<div class="cityList">
</div></div>
<div class="form-group simple_search p-x-1">
<?= Html::submitButton('Поиск', ['class' => 'btn j-primary']) ?>
</div>
                <?php if($data): ?>
                    <?php $list = \backend\components\CategoryWidget::widget(['template' => 'widget-category-ul', 'category_id' => $category['category_id']]); ?>

                    
                    <?php if(!empty(Yii::$app->params['Category']['selected_id'])): ?>
                            <?php if( Yii::$app->params['Category']['selected_id'] == $category['category_id']): ?>
                                <a href="#" class="cat-search"><?= $category['category_name'] ?> <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                            <?php endif; ?>
                            <?php if( Yii::$app->params['Category']['selected_id'] == $category['parent_category_id']): ?>
                                <a href="#" class="cat-search"><?= $category['parent_category_name'] ?> <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                            <?php endif; ?>
                    <?php else:?>
                        <a href="#" class="cat-search">Выбрать категорию <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                    <?php endif; ?>

                    
                        <?= $list ?>
                    
                    
                    <?php if(!empty(Yii::$app->request->get('img'))): ?>
                        <div class="form-group form-group-inline">
                            <label for="search-img"><input id="search-img" type="checkbox" name="Search[img]" value="1" checked>Только с фото</label>
                        </div>
                    <?php else: ?>
                        <div class="form-group form-group-inline">
                            <label for="search-img"><input id="search-img" type="checkbox" name="Search[img]" value="0">Только с фото</label>
                        </div>
                    <?php endif; ?> 
                        
                        <?php if(Yii::$app->params['Category']['selected_id'] != 40 &&
                        		Yii::$app->params['Category']['selected_id'] != 41 &&
                        		Yii::$app->params['Category']['selected_id'] != 848 &&
                        		Yii::$app->params['Category']['selected_id'] != 885 &&
                        		Yii::$app->params['Category']['selected_id'] != 886 &&
                        		Yii::$app->params['Category']['selected_id'] != 18 &&
                        		Yii::$app->params['Category']['selected_id'] != 21 &&
                        		Yii::$app->params['Category']['selected_id'] != 141 &&
                        		Yii::$app->params['Category']['selected_id'] != 143 &&
                        		Yii::$app->params['Category']['selected_id'] != 144 &&
                        		Yii::$app->params['Category']['selected_id'] != 145 &&
                        		Yii::$app->params['Category']['selected_id'] != 147 &&
                        		Yii::$app->params['Category']['selected_id'] != 151 &&
                        		Yii::$app->params['Category']['selected_id'] != 153 &&
                        		Yii::$app->params['Category']['selected_id'] != 24 &&
                        		Yii::$app->params['Category']['selected_id'] != 200 &&
                        		Yii::$app->params['Category']['selected_id'] != 202 &&
                        		Yii::$app->params['Category']['selected_id'] != 205) :?>
	                        <?php if((Yii::$app->params['Category']['selected_id'] < 30 || Yii::$app->params['Category']['selected_id'] > 41) || empty(Yii::$app->params['Category']['selected_id'])) : ?>
	                            <?php if((Yii::$app->params['Category']['selected_id'] < 226 || Yii::$app->params['Category']['selected_id'] > 266) || empty(Yii::$app->params['Category']['selected_id'])): ?>
	                                <?php if((Yii::$app->params['Category']['selected_id'] < 451 || Yii::$app->params['Category']['selected_id'] > 778) || empty(Yii::$app->params['Category']['selected_id'])) :?>
	                                    <div class="form-group form-group-inline form-price">

                                            <?php 
                                            $val = 'грн';
                                                if(!empty($_GET['course'])){
                                                    if($_GET['course'] === 'usd')
                                                        $val = '$';
                                                    else
                                                        $val = '€';
                                                }
                                            ?>
	                                        <input type="number" name="Search[sprice]" value="<?= Yii::$app->request->get('sprice') ?>" min="0" placeholder="Цена от (<?= $val ?>)">
	                                    
	                                        <input type="number" name="Search[eprice]" value="<?= Yii::$app->request->get('eprice') ?>" min="0" placeholder="Цена до (<?= $val ?>)">
	                                    </div>
	                                <?php endif; ?>
	                            <?php endif; ?>

	                        <?php endif; ?>
                        <?php endif; ?>
 <div class="sub-fields-search">
<?php $sub_fields = \backend\models\Fields::find()->indexBy('id')->where(['like', 'category_id', ':"-1"'])->orWhere(['like', 'category_id', ':"'.Yii::$app->params['Category']['selected_id'].'"'])->andWhere('search = 1')->asArray()->all();
print_r(\frontend\models\Ads::renderSubFields($sub_fields, null, 1));
?>
</div>
<?php endif; ?>
<?php ActiveForm::end(); ?>