<?php

namespace backend\models;

use Yii;
use yii\db\Query;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\helpers\Html;
/**
 * This is the model class for table "{{%categories}}".
 *

 */
class Ads extends \yii\db\ActiveRecord
{
     public $ads_has_image;
     public $images;
     public $images2;

    public $vip;
    public $top_;
    public $up;
    public $fire;
    public $once_up;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ],
            'CachedBehavior' => [
                'class' => \common\components\behaviors\CachedBehavior::className(),
                'cache_id' => ['categories_cache'],
            ],
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // если вместо метки времени UNIX используется datetime:
                // 'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'user_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(\backend\models\Categories::className(), ['id' => 'category_id']);
    }

    public function getPromotion()
    {
        return $this->hasMany(\backend\models\AdsHasImage::className(), ['ads_id' => 'id']);
    }

    public function getOncePromotion()
    {
        return $this->hasMany(\frontend\models\AdsHasImage::className(), ['ads_id' => 'id'])->where('validity_at > ' . time())->orderBy('type DESC')->groupBy('type');
        // return $this->hasMany(\backend\models\AdsHasImage::className(), ['ads_id' => 'id'])->groupBy('ads_id,type')->orderBy('validity_at DESC');
    }

    public function getMainImg()
    {
        return $this->hasOne(\rico\yii2images\models\Image::className(), ['itemId' => 'id'])->andWhere(['modelName' => 'Ads'])->andWhere(['isMain' => '1']);
    }

    public static function tableName()
    {
        return '{{%ads}}';
    }

    public static function getDayAdsCount()
    {
        return Ads::find()->select('count(*) as k')->where(['<=', 'created_at', time()])->andWhere(['>=', 'created_at', strtotime('-1 day')])->asArray()->orderBy('id DESC')->one()['k'];
    }

    public static function getLastAds($count = 5)
    {
        return Ads::find()->orderBy('id DESC')->asArray()->orderBy('id DESC')->limit($count)->all();
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'location', 'price', 'contact', 'category_id', 'type_payment', ], 'required'],
            [['category_id',], 'integer'],
            [['name', 'contact', 'location', 'email'], 'string', 'max' => 500],
            [['price'], 'double'],
            [['images'], 'file', 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 6],
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/((\+[0-9]{6})|0)[-]?[0-9]{9}/'],
            ['type_delivery', 'in', 'range' => [0,5, 10, 15]],
            ['type_payment', 'in', 'range' => [5, 10, 15]],
            [['bargain', 'negotiable'], 'boolean'],
            [['ads_has_image'], 'safe'],

            [['vip', 'top_', 'up'], 'integer'],
            [['fire', 'once_up'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'name' => 'Заголовок',
            'location' => 'Местоположение',
            'phone' => 'Номер телефона',
            'email' => 'Email-адрес',
            'contact' => 'Контактное лицо',
            'type_delivery' => 'Тип доставки',
            'type_payment' => 'Тип оплаты',
            'price' => 'Цена',
            'text' => 'Описание',
            'contact' => 'Контактное лицо',
            'bargain' => 'торг',
            'negotiable' => 'договорная',
            'fire' => 'Срочно (7 дней)',
            'once_up' => 'Единоразовое поднятие',
            'active' => 'Активность',
            'created_at' => 'Создано',
            'updated_at' => 'Редактировано',
            'validity_at' => 'Поднятие вверх (до)',
        ];
    }


    public static function getHash($str){

        $str = $str . strtotime(date('H:i:s'));
        $file = md5($str);
        return $file;

    }

    public static function getImagesForTablesAllAds($img, $width = 70, $height = 70)
    {
        if(!empty($img['mainImg'])){
            $str = explode('/', $img['mainImg']['filePath']);
            $alias_mini = $str[0] . '/' . $str[1] . '/mini_' . $str[2];

            return Html::img(Yii::getAlias('@images') . '/store/' . $alias_mini, ['alt' => $img['name'], 'width' => $width, 'height' => $height]);
        }

        return Html::img(Yii::getAlias('@images') . '/noimage-min.jpg' , ['alt' => 'default']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            $this->name = htmlspecialchars($this->name, ENT_QUOTES);
            $this->contact = htmlspecialchars($this->contact, ENT_QUOTES);
            $this->location = htmlspecialchars($this->location, ENT_QUOTES);

            if(!Yii::$app->user->isGuest){
                if(empty($this->user_id))
                    $this->user_id = Yii::$app->user->identity->id;
            }

            $this->alias = $this->str2url($this->name);
            if(empty($this->created_at))
                $this->created_at = time();

            if(empty($this->validity_at))
                $this->validity_at = strtotime('+1 month');

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        
        $this->images = UploadedFile::getInstances($this, 'images');

        if(!empty($this->images))
            $this->uploads();
    }

    public function uploads()
    {
        if ($this->validate(false)) {
            $aliases = [];
            for($i = 0; $i < count($this->images); $i++){
                $alias = Yii::getAlias('@appWeb') . '/uploads/ads/' . $this->getHash($this->images[$i]->baseName) . '.' . $this->images[$i]->extension;
                $aliases[] = $alias;
                $this->images[$i]->saveAs($alias);
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

                    // $watermark = new \abeautifulsite\SimpleImage($alias_mini);
                    // $watermark->text('JANDOOO', Yii::getAlias('@frontend') . '/web/fonts/DIN.otf', 12, '#FFFFFF', 'top left', 0, 0);
                    // $watermark->save($alias_mini);
                }

                $image = new \Imagick();
                $image->readImage($alias);
                $watermark = new \Imagick();
                $watermark->readImage( Yii::getAlias('@frontend') . '/web/images/jandooows.png');

                // $sizeMainImg = $image->getImageGeometry(); 
                // $sizeWatermarkImg = $watermark->getImageGeometry();


                // $sizeWatermarkImgWithMainW = ($sizeMainImg['width'] * 15)/100;
                // $sizeWatermarkImgWithMainH = ($sizeMainImg['height'] * 15)/100;

                // if($sizeWatermarkImgWithMainW > $sizeWatermarkImg['width'])
                //     $watermarkSizeW = $sizeWatermarkImg['width'];
                // else
                //     $watermarkSizeW = $sizeWatermarkImgWithMainW;


                // if($sizeWatermarkImgWithMainH > $sizeWatermarkImg['height'])
                //     $watermarkSizeH = $sizeWatermarkImg['height'];
                // else
                //     $watermarkSizeH = $sizeWatermarkImgWithMainH;

                // $watermark->adaptiveResizeImage($watermarkSizeW, $watermarkSizeH, true);
                //$watermark->scaleImage($watermark_Width / $watermarkResizeFactor, $watermark_Height / $watermarkResizeFactor);

                $position = $this->gravity2coordinates($image, $watermark, 'lowerLeft', 10, 10);
                // // compose watermark onto image
                // $image->compositeImage( $watermark, $watermark->getImageCompose(), $position['x'], $position['y'] );
                $image->compositeImage($watermark, \imagick::COMPOSITE_OVER, $position['x'], $position['y']); ///old
                $image->writeImage($alias);
                        
                // $watermark = new \abeautifulsite\SimpleImage($alias);
                // $watermark->text('JANDOOO', Yii::getAlias('@frontend') . '/web/fonts/DIN.otf', 46, '#FFFFFF', 'top left', 0, 0);
                // $watermark->save($alias);
            }

            return true;
        } else {
            return false;
        }
    }

    public function uploadsss()
    {
        if ($this->validate(false)) {
            $aliases = [];
            for($i = 0; $i < count($this->images); $i++){
                $alias = Yii::getAlias('@appWeb') . '/uploads/ads/' . $this->getHash($this->images[$i]->baseName) . '.' . $this->images[$i]->extension;
                $aliases[] = $alias;
                $this->images[$i]->saveAs($alias);
                $this->attachImage($alias);

                if($i == 0){
                    $str = explode('/', $this->getImage()->filePath);
                    $alias_mini = Yii::getAlias('@appWeb') . '/images/store/' . $str[0] . '/' . $str[1] . '/mini_' . $str[2];

                    if(!file_exists($alias_mini)){
                        $image = new \Imagick($alias);
                        $image->setImageCompressionQuality(75);
                        $image->cropThumbnailImage(220, 120);
                        $image->writeImage($alias_mini);

                        $watermark = new \abeautifulsite\SimpleImage($alias_mini);
                        $watermark->text('JANDOOO', Yii::getAlias('@frontend') . '/web/fonts/DIN.otf', 12, '#FFFFFF', 'top left', 0, 0);
                        $watermark->save($alias_mini);
                    }
                }
                @unlink($alias);
            }

            $images = $this->getImages();
            for($i = 0; $i < count($images); $i++){

                $watermark_alias = Yii::getAlias('@appWeb') . '/images/store/' . $images[$i]->filePath;
                $watermark = new \abeautifulsite\SimpleImage($watermark_alias);
                $watermark->text('JANDOOO', Yii::getAlias('@frontend') . '/web/fonts/DIN.otf', 46, '#FFFFFF', 'top left', 0, 20);
                $watermark->save($watermark_alias);
            }

            return true;
        } else {
            return false;
        }
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

    
    public function rus2translit($string) {
	    $converter = array(
	        'а' => 'a',   'б' => 'b',   'в' => 'v',
	        'г' => 'g',   'д' => 'd',   'е' => 'e',
	        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
	        'и' => 'i',   'й' => 'y',   'к' => 'k',
	        'л' => 'l',   'м' => 'm',   'н' => 'n',
	        'о' => 'o',   'п' => 'p',   'р' => 'r',
	        'с' => 's',   'т' => 't',   'у' => 'u',
	        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
	        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
	        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
	        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
	        
	        'А' => 'A',   'Б' => 'B',   'В' => 'V',
	        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
	        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
	        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
	        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
	        'О' => 'O',   'П' => 'P',   'Р' => 'R',
	        'С' => 'S',   'Т' => 'T',   'У' => 'U',
	        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
	        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
	        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
	        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
	    );
	    return strtr($string, $converter);
	}
	public function str2url($str) {
	    // переводим в транслит
	    $str = $this->rus2translit($str);
	    // в нижний регистр
	    $str = strtolower($str);
	    // заменям все ненужное нам на "-"
	    $str = preg_replace('~[^-a-z0-9_а-яА-Я]+~u', '-', $str);
	    // удаляем начальные и конечные '-'
	    $str = trim($str, "-");
	    $old_str = Ads::find()->where(['alias' => $str])->one();
	    if(!empty($old_str))
	    	$str = $str . '_' . mb_substr(Yii::$app->security->generateRandomString(), 0, 6);;
	    return $str;
	}

	public static function getDate($unix)
	{
		// return date('N F Y H:i', $unix);

		return strftime('%e %B %C %H:%M', $unix);
	}

    public function getInitImage($urldel = '/admin/ads/delete-img/')
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

}