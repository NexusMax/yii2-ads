<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = $model->name;
$this->params['breadcrumbss'][] = ['label' => $this->title];
$this->params['breadcrumbss'][] = ['label' => 'Тарифный план'];
?>
<?= $this->render('../myaccount/_header', ['search' => $search]) ?>
<div class="magazine-view">
    
    <?php 
    $form = ActiveForm::begin([
        'validateOnBlur' => false,
        'fieldConfig' => [
                'template' => "{label}\n{input}\n{hint}\n<div class='wrap-error-div'>\n{error}\n</div>",
            ],
    
    ]);
    ?>
   
        <div class="container">
                <h3 class="text-center">Период</h3>
                <div class="login-tabs">
                    <ul class="login-tabs__content">
                        <li class="active" data-content="login">
                            <div class="login-form no-margin m-form">
                                <div class="inner-form">
                                    <fieldset class="standard-login-box">
                                         <div class="fblock">
                                            <div class="focusbox">
                                            <?= $form->field($model, 'period')->dropDownList(ArrayHelper::map($periods,'id', 'name'), ['class' => 'light required m-sel',
                                                'data-text' => 'Выберите период действия Вашего магазина.',
                                            ])->label(false) ?>
                                            </div>
                                        </div>
                                    </fieldset>
                                    </div>
                            </div>

                        </li>
                    </ul>
                </div>
        </div>


    <div class="background">
        <div class="container">
        <h3 class="text-center">Тарифные планы</h3>

        <div class="fleft tariff">
            <div class="item">
                <div class="name"></div>
                <div class="count">Количество объявлений</div>
                <div class="days">ТОП на 30 дней</div>
                <div class="check">Готовый дизайн</div>
                <div class="price">Цена</div>
            </div>
        </div>

        <div class="row tariff">
            <?php foreach ($plans as $key => $val): ?>
                <?php if(!empty(Yii::$app->request->post('Magazine')['tarif_plan'])) {$sub='firstPrice';}else{$sub = 'priceIndex';} ?>
            <div class="item <?php if($model->tarif_plan == $val['id']){echo 'active';}?>" data-id="<?= $val['id'] ?>">
                <div class="name"><?= $val['name'] ?></div>
                <div class="count"><span class="span-count"><?= $val[$sub][$model->tarif_plan]['count_ads'] ?></span> шт.</div>
                <div class="days"><?= $val[$sub][$model->tarif_plan]['top_30_day'] ?></div>
                <div class="check">
                    <i class="fa fa-<?php if(intval($val[$sub][$model->period]['design']) === 1) echo 'check'; else echo 'close'; ?>" aria-hidden="true"></i>
                </div>
                <div class="price"><span class="span-price"><?= intval($val[$sub][$model->tarif_plan]['price']) ?></span> грн</div>
                <a class="btn j-success check-mag-tarif" data-id="<?= $val['id'] ?>" href="#">Выбрать</a>
            </div>
            <?php endforeach; ?>
        </div>

        <input type="hidden" id="magazine-tarif_plan" class="light required" name="Magazine[tarif_plan]" title="Название магазина" placeholder="Название магазина" value="<?= $model->tarif_plan ?>">

        </div>
        
        <?= Html::submitButton('Оплатить', ['class' => 'pay-btn btn j-success']) ?>

    </div>
    <?php ActiveForm::end(); ?>

</div>
<?= $this->render('../myaccount/_footer', ['search' => $search]) ?>