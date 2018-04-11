<?php

namespace frontend\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;

class MagazineAds extends \yii\db\ActiveRecord implements CartPositionInterface
{   
    use CartPositionTrait;

    const SCENARIO_PRODUCT = 'product';

    public $imagesFiles;
    public $without_payment;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%magazine_ads}}';
    }


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_PRODUCT] = [
                    'imagesFiles', 
                    'location', 
                    // 'contact', 
                    'name', 
                    // 'type_payment', 
                    'category_id', 
                    'text', 
                    // 'phone',
                    // 'email', 
                    // 'phone_2', 
                    // 'phone_3', 
                    // 'reg_id', 
                    'price', 
                    // 'city_id',  
                    'views',
                    // 'number_views',
                    'alias', 
                    'active', 
                    'user_id', 
                    'created_at', 
                    'updated_at', 
                    'validity_at',
                    'updated_by',
                    'count',
                    // 'negotiable',
                ];
        return $scenarios;
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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imagesFiles'], 'file', 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 6],

            [['name', 'location', 'contact', 'type_payment', 'text', 'phone'], 'required'],
            [['category_id',  'city_id', 'reg_id', 'views', 'number_views', 'updated_by', 'count'], 'integer'],
            [['text', 'contact', 'location', 'email'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 70, 'min' => 6],
            ['category_id', 'required', 'message' => 'Выбирайте рубрику строго соответствующую тематике Вашего товара, услуги или объекта.'],
            [['text'], 'string', 'max' => 255, 'min' => 15],
            [['price'], 'double', 'min' => 0],
            ['email', 'email'],

            // [['name'], 'unique', 'targetAttribute' => ['magazine_id', 'name', 'category_id']],
            // phone email url
            [['text','name'], 'match', 'pattern' => '/^((?!((\+[0-9]{6})|0)[-]?[0-9]{9}|([A-Za-z0-9]+[\_\-\.])*([A-Za-z0-9]+)@([A-Za-z0-9]+[\_\-\.])*([A-Za-z]{2,})|(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?).)*$/'],
            [['phone','phone_2', 'phone_3'], 'match', 'pattern' => '/((\+[0-9]{6})|0)[-]?[0-9]{9}/'],
            [['phone','phone_2', 'phone_3'], 'string', 'min' => 10, 'max' => 13],
            ['type_delivery', 'in', 'range' => [0, 5, 10, 15]],
            ['type_payment', 'in', 'range' => [5, 10, 15]],
            ['type_ads', 'in', 'range' => [5, 10, 0]],
            [['bargain', 'negotiable', 'without_payment'], 'boolean'],
            [['price', 'number_views', 'views', 'count'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'magazine_id' => 'Магазин',
            'user_id' => 'Пользователь',
            'alias' => 'Алиас',
            'name' => 'Название',
            'category_id' => 'Категория',
            'text' => 'Описание',
            'active' => 'Активность',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'validity_at' => 'По какое число',
            'price' => 'Цена',
            'bargain' => 'Торг',
            'negotiable' => 'Договорённая',
            'type_payment' => 'Тип оплаты',
            'type_delivery' => 'Тип доставки',
            'location' => 'Месторасположение',
            'phone' => 'Телефон',
            'contact' => 'Контактная информация',
            'email' => 'Емейл',
            'views' => 'Просмотры',
            'number_views' => 'Просмотры телефона',
            'phone_2' => 'Телефон 2',
            'city_id' => 'Город',
            'reg_id' => 'Регион',
            'phone_3' => 'Телефон 3',
            'type_ads' => 'Тип объявления',
            'publish' => 'Публикация',
            'imagesFiles' => 'Изображения',
            'count' => 'Колличество',
        ];
    }


    public function getVals()
    {
        return $this->hasMany(\frontend\models\MagazineEavValue::className(), ['product_id' => 'id'])->with('field')->indexBy('field_id')->asArray();
    }

    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    public function getFire()
    {
        return $this->hasOne(\frontend\models\MagazineAdsLog::className(), ['ads_id' => 'id'])->andWhere('type = 2')->andWhere(['>', 'validity_at', time()]);
    }

    public function getLog()
    {
        return $this->hasMany(\frontend\models\MagazineAdsLog::className(), ['ads_id' => 'id']);
    }

    public function getCategory()
    {
        return $this->hasOne(\frontend\models\MagazineHasCategories::className(), ['id' => 'category_id']);
    }

    public function getMagazin()
    {
        return $this->hasOne(\frontend\models\Magazine::className(), ['id' => 'magazine_id']);
    }

    public function getFavorite()
    {
        return $this->hasOne(\frontend\models\AdsFavorites::className(), ['ads_id' => 'id']);
    }

    public function getCostaImages()
    {
        return $this->hasMany(\rico\yii2images\models\Image::className(), ['itemId' => 'id'])->andWhere(['modelName' => 'MagazineAds']);
    }

    public function getMainImage()
    {
        return $this->hasOne(\rico\yii2images\models\Image::className(), ['itemId' => 'id'])->andWhere(['modelName' => 'MagazineAds']);
    }

    public function beforeSave($insert)
    {
        // echo '<pre>';
        // print_r($this);die;
        if (parent::beforeSave($insert)) {
            if($this->isNewRecord){
                $this->user_id = Yii::$app->user->identity->id;
                $this->validity_at = strtotime('+' . $this->magazin->periodd->days . ' days');
                $this->updated_by = time();
            }

            if(!empty($this->without_payment) || empty($this->price))
                $this->price = 0;

            return true;
        } else {
            return false;
        }
    }


    public function afterSave($insert, $changedAttributes){
        
        if($insert){
            $this->alias = $this->alias . '-' . $this->id;
            $this->save();
        }

        $this->imagesFiles = UploadedFile::getInstances($this, 'imagesFiles');

        if(!empty($this->imagesFiles))
            $this->uploads();

        parent::afterSave($insert, $changedAttributes);
       
    }

    public function uploads()
    {

        if ($this->validate()) {

            $aliases = [];
            for($i = 0; $i < count($this->imagesFiles); $i++){
                $alias = Yii::getAlias('@appWeb') . '/uploads/ads/' . Yii::$app->security->generateRandomString(6) . '.' . $this->imagesFiles[$i]->extension;
                $aliases[] = $alias;
                $this->imagesFiles[$i]->saveAs($alias);
                $this->attachImage($alias);
                @unlink($alias);
            }

            $images = $this->getImages();

            for($i = 0; $i < count($images); $i++){

                $str = explode('/', $images[$i]->filePath);
                $alias = Yii::getAlias('@appWeb') . '/images/store/' . $images[$i]->filePath;
                $alias_mini = Yii::getAlias('@appWeb') . '/images/store/' . $str[0] . '/' . $str[1] . '/mini_' . $str[2];

                if(!file_exists($alias_mini)){
                    $image = new \Imagick($alias);
                    $image->setImageCompressionQuality(75);
                    $image->cropThumbnailImage(220, 120);
                    $image->writeImage($alias_mini);

                    $watermark = new \Imagick();
                    $watermark->readImage( Yii::getAlias('@frontend') . '/web/images/jandooows_mini.png');
                    $image->compositeImage($watermark, \imagick::COMPOSITE_OVER, 10, 10);
                    $image->writeImage($alias_mini);

                }

                $image = new \Imagick();
                $image->readImage($alias);
                $watermark = new \Imagick();
                $watermark->readImage( Yii::getAlias('@frontend') . '/web/images/jandooows.png');


                $position = $this->gravity2coordinates($image, $watermark, 'lowerLeft', 10, 10);

                $image->compositeImage($watermark, \imagick::COMPOSITE_OVER, $position['x'], $position['y']); ///old
                $image->writeImage($alias);

            }

            return true;
        } else {
            return false;
        }
    }

    public static function getTypeAds()
    {
        return [
            0 => 'Выбрать',
            5 => 'Частное',
            10 => 'Бизнес',
        ];
    }

    public static function getBargain()
    {
        return [
            0 => 'Нет',
            1 => 'Да',
        ];
    }

    public static function getNegotiable()
    {
        return [
            0 => 'Да',
            1 => 'Нет',
        ];
    }

    public static function getTypePayment()
    {
        return [
            5 => 'Грн',
            10 => 'Руб',
            15 => 'USD',
        ];
    }

    public static function getTypeDelivery()
    {
        return [
            0 => 'Выбрать',
            5 => 'Почта',
            10 => 'Самовывоз',
            15 => 'Транспортировка поездом',
        ];
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getId()
    {
        return $this->id;
    }

    public static function isUserAuthor()
    {   

        $ads_user_id = Ads::find()->select('user_id')->where(['alias' => Yii::$app->request->get('alias')])->asArray()->limit(1)->one()['user_id'];

        if($ads_user_id == Yii::$app->user->identity->id)
            return true;
        else
            return false;
    }

    public static function isUserAuthorId()
    {   

        $ads_user_id = Ads::find()->select('user_id')->where(['id' => Yii::$app->request->get('id')])->asArray()->limit(1)->one()['user_id'];

        if($ads_user_id == Yii::$app->user->identity->id)
            return true;
        else
            return false;
    }

    public function getInitImage($urldel = '/admin/magazine-ads/delete-img/')
    {
        $return_json = [];
        $images = $this->getImages();
        foreach ($images as $k) {

            $return_json[] = [
              'caption'=>$k->getUrl(),
              'width'=> '100px',
              'url'=> $urldel,
              'key'=>$k['id'],
              'extra'=>['id'=>$this->id]
            ];

      }

      return $return_json;
    }

    public function getAllImages()
    {
        $images = $this->getImages();
        $img = [];
        foreach ($images as $key) {
            $img[] = $key->getUrl();
        }

        return $img;
    }

    public function gravity2coordinates($image, $watermark, $gravity, $xOffset=0, $yOffset=0) {
        // theoretically this should work
        // $im->setImageGravity( Imagick::GRAVITY_SOUTHEAST );
        // but it doesn't so here goes the workaround
        
        switch ($gravity) {
            case 'upperLeft':
                $x = $xOffset;
                $y = $yOffset;
                break;
            
            case 'upperRight':
                $x = $image->getImageWidth() - $watermark->getImageWidth() - $xOffset;
                $y = $yOffset;
                break;
            
            case 'lowerRight':
                $x = $image->getImageWidth() - $watermark->getImageWidth() - $xOffset;
                $y = $image->getImageHeight() - $watermark->getImageHeight() - $yOffset;
                break;
            
            case 'lowerLeft':
                $x = $xOffset;
                $y = $image->getImageHeight() - $watermark->getImageHeight() - $yOffset;
                break;
        }
        return array(
            'x' => $x, 
            'y' => $y
        );
    }

    public function getStatistic()
    {

        return '<div class="myaccount-statistic__item del-view-ad">
                        <i class="fa fa-eye statistic-icons" aria-hidden="true"></i> Просмотры: <span>' . intval($this->views) . '</span>
                    </div>
                    <div class="delete-ad-count delete-ad-countt"><a data-model="magazine" href="#">Обнулить</a><span> количество просмотров</span></div>
                    
                    <div class="myaccount-statistic__item">
                        <i class="fa fa-star statistic-icons" aria-hidden="true"></i> В избранном: ' . intval(count($this->favorite->ads_id)) . '
                    </div>';

    }
}
