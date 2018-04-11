<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

class Magazine extends \yii\db\ActiveRecord
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_BACKEND = 'backend';
    const SCENARIO_BACKEND_FULL = 'backend_full';
    const SCENARIO_UPDATE = 'update';

    public $verifyCode;
    public $deliveries;
    public $payments;

    public $public_key;
    public $private_key;
    public $card;

    public $imageFile;
    public $backgroundFile;

    public $count_ads;

    public $package;
    
    public static function tableName()
    {
        return '{{%magazine}}';
    }


    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ],
            TimestampBehavior::className(),
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'alias'
            ],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['name', 'category_id', 'period', 'tarif_plan'];
        
        $scenarios[self::SCENARIO_UPDATE] = [ 'period', 'tarif_plan'];

        $scenarios[self::SCENARIO_BACKEND] = ['contact', 'imageFile', 'name', 'category_id', 'period', 'tarif_plan', 'template', 'desc', 'alias', 'active', 'user_id', 'created_at', 'updated_at', 'validity_at', 'deliveries', 'payments', 'public_key', 'private_key', 'card', 'phone', 'phone_2', 'city_id', 'reg_id', 'worked_start_at', 'worked_end_at'];

        $scenarios[self::SCENARIO_BACKEND_FULL] = ['contact', 'imageFile', 'name', 'category_id', 'period', 'tarif_plan', 'template', 'desc', 'alias', 'active', 'user_id', 'created_at', 'updated_at', 'validity_at', 'deliveries', 'payments', 'public_key', 'private_key', 'card', 'phone', 'phone_2', 'city_id', 'reg_id', 'worked_start_at', 'worked_end_at', 'background', 'background_url', 'backgroundFile'];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'category_id', 'period', 'tarif_plan', 'template', 'desc', 'alias'], 'required'],
            [['active', 'category_id', 'period', 'tarif_plan', 'template', 'created_at', 'updated_at', 'validity_at', 'user_id', 'public_key', 'private_key', 'card'], 'integer'],
            ['deliveries', 'exist', 'allowArray' => true, 'targetClass' => MagazineDelivery::className(), 'targetAttribute' => 'id'],
            ['payments', 'exist', 'allowArray' => true, 'targetClass' => MagazinePayment::className(), 'targetAttribute' => 'id'],
            ['tarif_plan', 'default', 'value' => 1],
            [['worked_start_at', 'worked_end_at'], 'time', 'format' => 'php:H:i'],
            [['name'], 'unique'],
            [['alias'], 'string', 'max' => 255],
            [['name', 'contact', 'phone', 'phone_2'], 'string', 'max' => 70],
            [['background', 'background_url'], 'string', 'max' => 255],
            [['desc'], 'string'],
            ['verifyCode', 'captcha', 'captchaAction' => 'magazine/captcha'],
            [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['backgroundFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['city_id', 'reg_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'alias' => 'Алиас',
            'user_id' => 'Пользователь',
            'category_id' => 'Деятельность',
            'period' => 'Период',
            'desc' => 'Описание',
            'active' => 'Активность',
            'template' => 'Шаблон',
            'tarif_plan' => 'Тарифный план',
            'created_at' => 'Дата создания',
            'updated_at' => 'Последнее обновление',
            'validity_at' => 'Действует до',
            'verifyCode' => 'Код подтверждения',
            'deliveries' => 'Тип доставки',
            'payments' => 'Тип оплаты',
            'imageFile' => 'Лого',
            'contact' => 'Контактная информация',
            'phone' => 'Телефон',
            'phone_2' => 'Телефон №2',
            'city_id' => 'Город',
            'reg_id' => 'Регион',
            'worked_start_at' => 'Начало работы', 
            'worked_end_at' => 'Конец работы',
            'background' => 'Фоновый цвет',
            'backgroundFile' => 'Фоновое изображение',
        ];
    }

    public function getImagess()
    {
        return $this->hasOne(\rico\yii2images\models\Image::className(), ['itemId' => 'id'])->andWhere(['modelName' => 'Magazine']);
    }

    public function getImageRico()
    {
        return $this->hasOne(\rico\yii2images\models\Image::className(), ['itemId' => 'id'])->where(['isMain' => 1])->andWhere(['modelName' => 'Magazine']);
    }

    public function getCountAds()
    {
        return $this->hasMany(\frontend\models\MagazineAds::className(), ['magazine_id' => 'id']);
    }

    public function getTarif()
    {
        return $this->hasOne(\frontend\models\MagazinePlan::className(), ['id' => 'tarif_plan']);
    }

    public function getCity()
    {
        return $this->hasOne(\frontend\models\City::className(), ['id' => 'city_id']);
    }

    public function getTarifWithPlan()
    {
        return $this->hasOne(\frontend\models\MagazinePlan::className(), ['id' => 'tarif_plan'])->with('price');
    }

    public function getPeriodd()
    {
        return $this->hasOne(\frontend\models\MagazinePeriod::className(), ['id' => 'period']);
    }

    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(\frontend\models\MagazineCategory::className(), ['id' => 'category_id']);
    }

    public function getPaymentss()
    {
        return $this->hasMany(\frontend\models\MagazineHasPayment::className(), ['magazine_id' => 'id']);
    }

    public function getPayments()
    {
        return $this->hasMany(\frontend\models\MagazinePayment::className(), ['id' => 'payment_id'])
            ->viaTable('{{%magazine_has_payment}}', ['magazine_id' => 'id']);
    }

    public function getPaymentsss()
    {
        return $this->hasMany(\frontend\models\MagazinePayment::className(), ['id' => 'payment_id'])
            ->viaTable('{{%magazine_has_payment}}', ['magazine_id' => 'id']);
    }

    public function getDeliveriess()
    {
        return $this->hasMany(\frontend\models\MagazineHasDelivery::className(), ['magazine_id' => 'id']);
    } 

    public function getDeliveries()
    {
        return $this->hasMany(\frontend\models\MagazineDelivery::className(), ['id' => 'delivery_id'])
            ->viaTable('{{%magazine_has_delivery}}', ['magazine_id' => 'id']);
    }

    public function getDeliveriesss()
    {
        return $this->hasMany(\frontend\models\MagazineDelivery::className(), ['id' => 'delivery_id'])
            ->viaTable('{{%magazine_has_delivery}}', ['magazine_id' => 'id']);
    }

    public function getOrderss()
    {
        return $this->hasMany(\frontend\models\MagazineOrder::className(), ['magazine_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        $this->package = $this->getPackage();
        if (parent::beforeSave($insert)) {

            if($this->isNewRecord){

                $this->validity_at = strtotime('+' . $this->periodd->days . ' days');
                $this->user_id = Yii::$app->user->identity->id;

            }else{
                $old = self::find()->where(['id' => $this->id])->asArray()->one();

                if($old['period'] != $this->period){
                    $this->validity_at = strtotime('+' . $this->periodd->days . ' days');
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function getTemplates($id = null)
    {
        $templates = [ 
            [
                'id' => 4,
                'name' => 'Светлый',
            ],
            [
                'id' => 2,
                'name' => 'Серый',
            ],
            [
                'id' => 1,
                'name' => 'Темный',
            ],
            [
                'id' => 3,
                'name' => 'Индивидуальный',
            ],
        ];

        if(!$id)
            return $templates;
        else {
            foreach ($templates as $key) {
                if($key['id'] == $id)
                    return $key['name'];
            }
        }
    }

    public function upload()
    {
        if ($this->validate()) {

            $alias = Yii::getAlias('@appWeb') . '/uploads/magazine/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($alias);

            $this->removeImages();
            $this->attachImage($alias, true);
            @unlink($alias);

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes){
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');

        if(!empty($this->imageFile))
            $this->upload();

        $this->order();

        parent::afterSave($insert, $changedAttributes);
    }


    public static function isUserAuthorMagazine()
    {   
        $ads_user_id = self::find()->select('user_id')->where(['id' => Yii::$app->request->get('id')])->asArray()->limit(1)->one()['user_id'];

        if($ads_user_id == Yii::$app->user->identity->id)
            return true;
        else
            return false;
    }

    public function getTotalPrice()
    {
        $sum = $this->package['price'];

        if($this->template == 3)
            $sum = $sum + 250;

        return $sum;
    }

    public function getPackage()
    {
        return MagazinePrice::find()->where(['plan_id' => $this->tarif_plan])->andWhere(['period_id' => $this->period])->asArray()->one();
    }

    public function isIndTemp()
    {
        if($this->template == 3){
            return 1;
        }else{
            return 0;
        }
    }

    public function order()
    {
        $order = new \frontend\models\MagazineSuccessPayed();

        $order->magazine_id = $this->id;
        $order->user_id = $this->user_id;
        $order->tarif_id = $this->package['id'];
        $order->individual_template = $this->isIndTemp();
        $order->sum = $this->getTotalPrice();
        $order->save();
    }
}
