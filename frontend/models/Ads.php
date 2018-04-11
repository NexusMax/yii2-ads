<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\Pagination;
use yii\data\Sort;

/**
 * This is the model class for table "{{%ads}}".
 *

 */
class Ads extends \yii\db\ActiveRecord
{
     public $images;
     public $sub_category;
     public $sub_sub_category;
     public $without_payment;

     public $image_1;
     public $image_2;
     public $image_3;
     public $image_4;
     public $image_5;
     public $image_6;


     public $parent_category_name;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
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

    public static function tableName()
    {
        return '{{%ads}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'contact', 'type_payment', 'text', 'phone', 'city_id', 'reg_id'], 'required'],
            [['category_id', 'sub_category', 'sub_sub_category', 'views', 'number_views', 'city_id', 'reg_id'], 'integer'],
            [['contact', 'location', 'email'], 'string', 'max' => 500],
            [['name'], 'string', 'max' => 70, 'min' => 6],
            ['category_id', 'required', 'message' => 'Выбирайте рубрику строго соответствующую тематике Вашего товара, услуги или объекта.'],
            ['sub_category', 'required', 'message' => 'Выбирайте рубрику строго соответствующую тематике Вашего товара, услуги или объекта.'],
            [['text'], 'string', 'max' => 700, 'min' => 15],
            [['price'], 'double', 'min' => 0],
            [['images', 'image_1' , 'image_2', 'image_3', 'image_4', 'image_5', 'image_6'], 'file', 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 6],
            ['email', 'email'],
            // phone email url
            [['name'], 'match', 'pattern' => '/^((?!((\+[0-9]{6})|0)[-]?[0-9]{9}|([A-Za-z0-9]+[\_\-\.])*([A-Za-z0-9]+)@([A-Za-z0-9]+[\_\-\.])*([A-Za-z]{2,})|(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?).)*$/'],
            [['phone','phone_2', 'phone_3'], 'match', 'pattern' => '/((\+[0-9]{6})|0)[-]?[0-9]{9}/'],
            [['phone','phone_2', 'phone_3'], 'string', 'min' => 10, 'max' => 13],
            ['type_delivery', 'in', 'range' => [0, 5, 10, 15]],
            ['type_payment', 'in', 'range' => [5, 10, 15]],
            ['reg_id', 'integer', 'min' => 1, 'tooSmall' => 'Необходимо заполнить "Область"'],
            ['type_ads', 'in', 'range' => [5, 10, 0]],
            [['bargain', 'negotiable', 'without_payment'], 'boolean'],
            // [['city_id', 'reg_id'], 'safe'],
        ];
    }


        // ((\+[0-9]{6})|0)[-]?[0-9]{9}
        // ([A-Za-z0-9]+[\_\-\.])*([A-Za-z0-9]+)@([A-Za-z0-9]+[\_\-\.])*([A-Za-z]{2,})
        // (https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'sub_category' => 'Подкатегория',
            'name' => 'Заголовок',
            'location' => 'Местоположение',
            'phone' => 'Номер телефона',
            'phone_2' => 'Второй номер телефона',
            'phone_3' => 'Третий номер телефона',
            'email' => 'Email-адрес',
            'contact' => 'Контактное лицо',
            'type_delivery' => 'Тип доставки',
            'type_payment' => 'Тип оплаты',
            'price' => 'Цена',
            'text' => 'Описание',
            'contact' => 'Контактное лицо',
            'bargain' => 'торг',
            'negotiable' => 'договорная',
            'reg_id' => 'Область',
            'city_id' => 'Город',
            'without_payment' => 'Без предварительной оплаты',
            'type_ads' => 'Тип обьявления'
        ];
    }



    public static function getHash($str){

        $str = $str . strtotime(date('H:i:s'));
        $file = md5($str);
        return $file;

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

    public function getCategory()
    {
        return $this->hasOne(\frontend\models\Categories::className(), ['id' => 'category_id']);
    }

    public function getMainImg()
    {
        return $this->hasOne(\rico\yii2images\models\Image::className(), ['itemId' => 'id'])->andWhere(['modelName' => 'Ads'])->andWhere(['isMain' => '1']);
    }

    public function getVipImg()
    {
        return $this->hasOne(\frontend\models\AdsHasImage::className(), ['ads_id' => 'id'])->andWhere(['type' => 2]);
    }

    public function getPromotion()
    {
        return $this->hasOne(\frontend\models\AdsHasImage::className(), ['ads_id' => 'id']);
    }

    public function getAllPromotion()
    {
        return $this->hasMany(\frontend\models\AdsHasImage::className(), ['ads_id' => 'id']);
    }

    public function getAllPromotionOrder()
    {
        return $this->hasMany(\frontend\models\AdsHasImage::className(), ['ads_id' => 'id'])->where('validity_at > ' . time())->orderBy('type DESC')->groupBy('type');
    }

    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            $this->name = mb_strtoupper(mb_substr($this->name, 0, 1, "UTF-8"), "UTF-8").mb_substr($this->name, 1, mb_strlen($this->name), "UTF-8" );
            $this->text = $this->text;
            $this->contact = htmlspecialchars($this->contact, ENT_QUOTES);

            if(!empty($this->reg_id) || !empty($this->city_id)){

                $reg = Yii::$app->db->createCommand('Select id, db_rootid, db_defnamelang as name from jandoo_vd_region WHERE id = ' . $this->reg_id)->queryOne()['name'];
                $city = Yii::$app->db->createCommand('Select id, db_rootid, db_defnamelang as name from jandoo_vd_city WHERE id = ' . $this->city_id)->queryOne()['name'];

                $this->location = $city . ', ' . $reg;
            }
            

            if(empty($this->user_id)){
                $this->user_id = Yii::$app->user->identity->id;
            }

            if(empty($this->created_at))
                $this->created_at = time();
            if(empty($this->validity_at))
                $this->validity_at = strtotime('+1 month');

            if(!empty($this->without_payment) || empty($this->price))
                $this->price = 0;

            if(!empty($this->sub_sub_category)){
                $this->category_id = $this->sub_sub_category;
            }elseif(!empty($this->sub_category)){
                $this->category_id = $this->sub_category;
            }


            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        $this->image_1 = UploadedFile::getInstances($this, 'image_1');
        $this->image_2 = UploadedFile::getInstances($this, 'image_2');
        $this->image_3 = UploadedFile::getInstances($this, 'image_3');
        $this->image_4 = UploadedFile::getInstances($this, 'image_4');
        $this->image_5 = UploadedFile::getInstances($this, 'image_5');
        $this->image_6 = UploadedFile::getInstances($this, 'image_6');

        $this->images[] = !empty($this->image_1[0]) ? $this->image_1[0] : '';
        $this->images[] = !empty($this->image_2[0]) ? $this->image_2[0] : '';
        $this->images[] = !empty($this->image_3[0]) ? $this->image_3[0] : '';
        $this->images[] = !empty($this->image_4[0]) ? $this->image_4[0] : '';
        $this->images[] = !empty($this->image_5[0]) ? $this->image_5[0] : '';
        $this->images[] = !empty($this->image_6[0]) ? $this->image_6[0] : '';


        if(!empty($this->images))
            $this->uploads();

        if(!empty(Yii::$app->request->post()['Ads']['sub_fields'])){
            $fieldValue = new \backend\models\FieldValue();
            $fieldValue->ads_id = $this->id;
            $fieldValue->value_sub_field = json_encode(Yii::$app->request->post()['Ads']['sub_fields']);
            $fieldValue->save();
        }
       
    }

    public function uploads()
    {
        // echo '<pre>';
        // print_r($this);
        // die;
        if ($this->validate(false)) {
            $aliases = [];
            for($i = 0; $i < count($this->images); $i++){
                if(!empty($this->images[$i])){
                    $alias = Yii::getAlias('@appWeb') . '/uploads/ads/' . $this->getHash($this->images[$i]->baseName) . '.' . $this->images[$i]->extension;
                    $aliases[] = $alias;
                    $this->images[$i]->saveAs($alias);
                    $this->attachImage($alias);
                    @unlink($alias);
                }
            }

            $images = $this->getImages();
            for($i = 0; $i < count($images); $i++){

                if(!empty($this->images[$i])){
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
                    // compose watermark onto image
                    // $image->compositeImage( $watermark, $watermark->getImageCompose(), $position['x'], $position['y'] );
                    $image->compositeImage($watermark, \imagick::COMPOSITE_OVER, $position['x'], $position['y']); ///old
                    $image->writeImage($alias);
                            
                    // $watermark = new \abeautifulsite\SimpleImage($alias);
                    // $watermark->text('JANDOOO', Yii::getAlias('@frontend') . '/web/fonts/DIN.otf', 46, '#FFFFFF', 'top left', 0, 0);
                    // $watermark->save($alias);
                }
            }

            return true;
        } else {
            return false;
        }
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
	    	$str = $str . '_' . mb_substr(Yii::$app->security->generateRandomString(), 0, 8);

        if(empty($str))
            $str = '-' . time();
	    return $str;
	}

    public function str2url2($str, $id) {

        // переводим в транслит
        $str = $this->rus2translit($str);
        // в нижний регистр
        $str = strtolower($str);
        // заменям все ненужное нам на "-"
        $str = preg_replace('~[^-a-z0-9_а-яА-Я]+~u', '-', $str);
        // удаляем начальные и конечные '-'
        $str = trim($str, "-");
        if(empty($str))
            $str = '-' . time();

        return $str . '-' . $id;
    }

	public static function getDate($unix)
	{
		// return date('N F Y H:i', $unix);

		return strftime('%e %B %C %H:%M', $unix);
	}

    public static function getPromotionUl()
    {
        return '<ul class="abs nowrap globalopt bgfff fbold" id="globalActions">
                                <li class="fleft hidden" id="promoteme">
                                    <a href="#" class="link">
                                        <span>рекламировать выбранные</span>
                                    </a>
                                </li>
                                <li class="fleft hidden" id="confirmme">
                                    <a href="#" class="link">
                                        <span>Подтвердить выбранные</span>
                                    </a>
                                </li>
                                <li class="fleft hidden" id="activateme">
                                    <a href="#" class="link">
                                        <span>Активировать выбранные</span>
                                    </a>
                                </li>
                                                                <li class="fleft hidden" id="activatemelimited">
                                        <a href="#" class="link">
                                            <span>Активировать выбранные</span>
                                        </a>
                                    </li>
                                                            <li class="fleft hidden" id="removeme" style="display: none;">
                                    <a href="#" class="link">
                                        <span>Удалить выбранные</span>
                                    </a>
                                </li>
                                <li class="fleft hidden first" id="resignme">
                                    <a href="#" class="link">
                                        <span>Отменить публикацию</span>
                                    </a>
                                </li>
                                                                                        <li class="fleft hidden" id="addFlagAds">
                                    <a href="#" class="link">
                                        <span>Рекомендовать на бизнес-странице</span>
                                    </a>
                                </li>
                                <li class="fleft hidden" id="removeFlagAds">
                                    <a href="#" class="link">
                                        <span>Удалить с "Рекомендованные предложения"</span>
                                    </a>
                                </li>
                                <li class="fleft hidden" id="deactivateme">
                                    <a href="#" class="link">
                                        <span>Деактивировать выбранные</span>
                                    </a>
                                </li>
                            </ul>';
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


    public static function getTypeAds()
    {
        return [
            0 => 'Выбрать',
            5 => 'Частное',
            10 => 'Бизнес',
        ];
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


    public static function getImagesForTablesAllAds($img, $width = 70, $height = 70)
    {
        if( \Yii::$app->devicedetect->isMobile() ){
            $width = 280;
            $height = auto;
        }
        if(!empty($img['mainImg'])){
            $str = explode('/', $img['mainImg']['filePath']);
            $alias_mini = $str[0] . '/' . $str[1] . '/mini_' . $str[2];

            return Html::img(Yii::getAlias('@images') . '/store/' . $alias_mini, ['alt' => $img['name'], 'width' => $width, 'height' => $height]);
        }

        return Html::img(Yii::getAlias('@images') . '/noimage-min.jpg' , ['alt' => 'default']);
    }


    public static function getMiniImage($img, $name = '', $width = 70, $height = 70)
    {
        if(!empty($name)){
            $img['name'] = $name;
        }

        $str = explode('/', $img['filePath']);
        $alias_mini = Yii::getAlias('@images') . '/store/' . $str[0] . '/' . $str[1] . '/mini_' . $str[2];

        if(!empty($img['filePath'])){
            return Html::img($alias_mini, ['alt' => $img['name'], 'width' => $width, 'height' => $height, 'title' => $img['name']]);
        }

        return Html::img(Yii::getAlias('@images') . '/nophoto.jpg' , ['alt' => 'default']);
    }


    public static function getTableAllAds($ads, $sort)
    {
        // echo '<pre>';
        // print_r($ads);die;
        $app = Yii::$app;

        if( \Yii::$app->devicedetect->isMobile() ){
            if(Yii::$app->controller->action->id != 'favorite')

                $renderAds .= '<div class="" id="adsTable">';

            foreach ($ads as $ad){

                $time = $app->formatter->asDate($ad['created_at'], 'php:d M');
                $time_for = $app->formatter->asDate($ad['validity_at'], 'php:d M');

                empty($app->params['messages'][$ad['id']]['unread']) ? $message = 0 : $message = $app->params['messages'][$ad['id']]['unread'];


                empty($ad['favorite_view']) ? $ad['favorite_view'] = 0 : 0;
                empty($ad['phone_view']) ? $ad['phone_view'] = 0 : 0;
                empty($ad['views']) ? $ad['views'] = 0 : 0;

                if(Yii::$app->controller->action->id == 'index'){
                    $links = '
                    <a class="tdnone marginright5 editme" href="' . Url::to(['ads/update', 'alias' => $ad['alias']]) . '" title="Редактировать"><i class="fa fa-pencil" aria-hidden="true"></i>Редактировать</a>';

                    $links .= Html::a(
                        '<i class="fa fa-times" aria-hidden="true"></i>Деактивировать',
                        Url::to(['myaccount/disactive', 'id' => $ad['id']]),
                        [
                            'data-confirm' => "Вы действительно хотите деактивировать?", // <-- confirmation works...
                            'data-method' => 'post',
                            'class' => 'tdnone marginright5 editme'
                        ]);

                    $button_active = Html::a(
                            '<i class="fa fa-refresh" aria-hidden="true"></i>Обновить',
                            Url::to(['myaccount/refresh', 'id' => $ad['id']]),
                            [
                                'data-method' => 'post',
                                'class' => 'btn j-primary m-y-1'
                            ]) .
                        Html::a(
                            '<i class="fa fa-bullhorn" aria-hidden="true"></i>Рекламировать',
                            Url::to(['ads/reklama', 'alias' => $ad['alias']]),
                            [
                                'class' => 'btn j-primary m-y-1'
                            ]);


                }
                if(Yii::$app->controller->action->id == 'archive'){
                    $links = '
                    <a class="tdnone marginright5 editme" href="' . Url::to(['ads/update', 'alias' => $ad['alias']]) . '" title="Редактировать"><i class="fa fa-pencil" aria-hidden="true"></i>Редактировать</a>';

                    $links .= Html::a(
                        '<i class="fa fa-times" aria-hidden="true"></i>Удалить',
                        Url::to(['myaccount/delete', 'id' => $ad['id']]),
                        [
                            'class' => 'tdnone marginright5 editme delete-click'
                        ]);

                    $button_active = Html::a(
                        'Активировать',
                        Url::to(['myaccount/active', 'id' => $ad['id']]),
                        [
                            'data-method' => 'post',
                            'class' => 'btn j-primary m-y-1'
                        ]);
                }

                if(Yii::$app->controller->action->id != 'favorite') {
                    if(empty($ad['parent_parent_category_name']))
                        $free_ads = '<i class="fa fa-star statistic-icons" aria-hidden="true"></i> В категории: ' . $ad['parent_category_name'] . ' у Вас обьявлений: ' . Yii::$app->params['myaccount']['cat_limit'][$ad['parent_category_alias']]['count'];
                    else
                        $free_ads = '<i class="fa fa-star statistic-icons" aria-hidden="true"></i> В категории: ' . $ad['parent_parent_category_name'] . ' у Вас обьявлений: ' . Yii::$app->params['myaccount']['cat_limit'][$ad['parent_parent_category_alias']]['count'];


                    $myaccount_statistic = '
                        <div class="ads__item--elem myaccount-statistic">
                          
                            <div class="myaccount-statistic__item del-view-ad">
                                <i class="fa fa-eye statistic-icons" aria-hidden="true"></i> Просмотры: <span>'.$ad['views'].'</span>
                               <!-- <i class="fa fa-angle-right" aria-hidden="true"></i> -->
                            </div>
                            <div class="delete-ad-count"><a href="#">Обнулить</a><span> количество просмотров</span></div>
                            
                            <div class="myaccount-statistic__item del-view-phone">
                                <i class="fa fa-phone statistic-icons" aria-hidden="true"></i> Тел.: <span>'.$ad['phone_view'].'</span>
                               <!-- <i class="fa fa-angle-right" aria-hidden="true"></i> -->
                            </div>
                            <div class="delete-phone-count"><a href="#">Обнулить</a><span> количество просмотров</span></div>
                            
                            <div class="myaccount-statistic__item">
                                <i class="fa fa-star statistic-icons" aria-hidden="true"></i> В избранном: '.$ad['favorite_view'].'
                            </div>
                            
                            <div class="myaccount-statistic__item">
                                ' . $free_ads . '
                            </div>
                        </div>';

                    $ad_info = '
                        <div class="ads__item--elem price" data-price="0">  
                            <span class="nowrap ">
                                Цена: ' . $ad['price'] . ' ' . self::getTypePayment()[$ad['type_payment']] . '
                            </span>
                        </div>
                        <div class="ads__item--elem myacc-msgs">
                            <span class="message-envelope">
                                <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                сообщений: 
                                '. $message.'
                            </span>
                        </div>';
                }

                if(Yii::$app->controller->action->id == 'favorite'){
                    if(strcmp(Yii::$app->request->cookies->getValue('ad_' . $ad['id'], 'en'), 'en') !== 0)
                        $button_active = '<p class="like"><span class="favorite-text">Удалить из<br> избранного</span> <a href="#" data-id="'. $ad['id'] .'" data-icon="star-filled" class="favorite-out"></a></p>';
                    else
                        $button_active = '<p class="like"><span class="favorite-text">В избранное</span> <a href="#" data-id="'. $ad['id'] .'"  class="favorite"><i class="fa fa-star-o" aria-hidden="true"></i></a></p>';

                }

                $renderAds .=
                    '<div class="ads__ietm">   
                    <div class="ads__item--elem name">
                        <h3 class="normal inlblk brkword fbold" title="' . $ad['name'] . '">' . $ad['name'] . '</h3>
                    </div>
                    <div class="ads__item--elem img">
                        ' . self::getImagesForTablesAllAds($ad) . '
                    </div>
                   ' . $ad_info . '
                   <div class="ads__item--elem but">
                        '.$button_active.'
                   </div>
                   <div class="ads__item--info">  
                        <a class="tdnone marginright5" title="Просмотреть" href="' . Url::to(['ads/view', 'alias' => $ad['alias']]) . '">
                            <i class="fa fa-external-link" aria-hidden="true"></i>
                            Просмотреть
                        </a>
                        '.$links.'
                   </div>
                   <div class="ads__item--elem date">
                        <p class="date dateContainer nowrap marginleft5 marginright5">
                            C: ' . $time . ' По: '.$time_for.'
                        </p>
                   </div>                    
                   ' . $myaccount_statistic . '
                </div>';
            }

            $renderAds .='</div>';
        }
        else{

            if(Yii::$app->controller->action->id != 'favorite')
                $title = '<th width="95" class="th normal"><span class="globalActionHide" style="visibility: visible;">Цена</span></th>
                    <th class="th normal"><span class="globalActionHide marginright10" style="visibility: visible;">Сообщения</span></th>';

            $renderAds .= '<table width="100%" class="myoffersnew small active" id="adsTable">
                <tr>
                    <th width="30" class="th tcenter normal vtop">
                        <span class="abs" style="width:0px; height:0px; overflow:hidden; font-size:0px; zoom:1;"><input type="checkbox" class="checkbox" id="globalCheckbox" name="global"></span><label for="globalCheckbox" class="icon f_checkbox inlblk vtop" relname="global">&nbsp;</label>
                    </th>
                    <th width="100" class="th normal selected"><div class="rel height16" style="height: auto;">' . $sort->link('created_at') . '<!-- $this->getPromotionUl() --></div></th>
                    <th width="100" class="th normal selected"></th>
                    <th class="th normal"><div class="pdingleft10"><span class="globalActionHide" style="visibility: visible;">' . $sort->link('name') . '</span></div></th>
                    '.$title.'
                </tr>';
                // echo '<pre>';
                // print_r($ads);
                // die;
            foreach ($ads as $ad){

                // echo '<pre>';
                // print_r($ad);
                // die;
                $order_ = '';
                if(!empty($ad['allPromotionOrder'])){
                    $order_ = '';
                    foreach ($ad['allPromotionOrder'] as $key_) {
                        $dt = $key_['validity_at'] - time();
                        $day_ = floor( $dt / 86400 );

                        $order_ .= '(' . Promotion::getNameTypes()[$key_['type']] . ') - осталось кол-во (' . $day_ . ') дней <br>';
                    }
                    $order_ = '<div class="myaccount-statistic__item del-view-ad">
                                <i class="fa fa-eye statistic-icons" aria-hidden="true"></i> Продвижение
                               <!-- <i class="fa fa-angle-right" aria-hidden="true"></i> -->
                            </div>
                            <div class="delete-ad-count">' . $order_ . '</div>';
                }

                


                //if(time() - $ad['created_at'] > 604800)
                $time = $app->formatter->asDate($ad['created_at'], 'php:d M');
                $time_for = $app->formatter->asDate($ad['validity_at'], 'php:d M');

                empty($app->params['messages'][$ad['id']]['unread']) ? $message = 0 : $message = $app->params['messages'][$ad['id']]['unread'];
                // else
                //  $time = $app->formatter->format($ad['created_at'], 'relativeTime');

                empty($ad['favorite_view']) ? $ad['favorite_view'] = 0 : 0;
                empty($ad['phone_view']) ? $ad['phone_view'] = 0 : 0;
                empty($ad['views']) ? $ad['views'] = 0 : 0;

                if(Yii::$app->controller->action->id == 'index'){

                 if(Yii::$app->user->identity->ban){
                        $url__ = '#';
                    }else{
                        $url__ = Url::to(['ads/update', 'alias' => $ad['alias']]);
                    }
                    
                        $promo_rel .= '<div class="reklama-info">';
                        $promo_rel .= \frontend\models\AdsHasUpdate::getInfo($ad['adsHasUpdate']);
                        $promo_rel .= '</div>';
                   
                        $promko_count = count($ad['adsHasUpdate']);
                        ( $promko_count >= 2 ) ? $promko_count = 'seruy-a' : '';
                    $links = '  
                    <a class="tdnone marginright5 editme" href="' . $url__ . '" title="Редактировать"><i class="fa fa-pencil" aria-hidden="true"></i>Редактировать</a>';

                    $links .= Html::a(
                        '<i class="fa fa-times" aria-hidden="true"></i>Деактивировать',
                        Url::to(['myaccount/disactive', 'id' => $ad['id']]),
                        [
                            'data-confirm' => "Вы действительно хотите деактивировать?", // <-- confirmation works...
                            'data-method' => 'post',
                            'class' => 'tdnone marginright5 editme'
                        ]);

                    $button_active = Html::a(
                            'Обновить',
                            Url::to(['myaccount/refresh', 'id' => $ad['id']]),
                            [
                                'data-method' => 'post',
                                'class' => 'btn j-primary m-y-1 reklama-info-a ' .  $promko_count
                            ]) .
                        $promo_rel;

                            if(Yii::$app->user->identity->ban){
                                $button_active .= Html::a(
                                    'Рекламировать',
                                    '#',
                                    [
                                        'class' => 'btn j-primary m-y-1'
                                    ]);
                            }else{
                                    $button_active .= Html::a(
                                    'Рекламировать',
                                    Url::to(['ads/reklama', 'alias' => $ad['alias']]),
                                    [
                                        'class' => 'btn j-primary m-y-1'
                                    ]);
                            }


                }
                if(Yii::$app->controller->action->id == 'archive'){

                    if(Yii::$app->user->identity->ban){
                        $url__ = '#';
                    }else{
                        $url__ = Url::to(['ads/update', 'alias' => $ad['alias']]);
                    }
                    $links = '
                    <a class="tdnone marginright5 editme" href="' . $url__ . '" title="Редактировать"><i class="fa fa-pencil" aria-hidden="true"></i>Редактировать</a>';

                    $links .= Html::a(
                        '<i class="fa fa-times" aria-hidden="true"></i>Удалить',
                        Url::to(['myaccount/delete', 'id' => $ad['id']]),
                        [
                            'class' => 'tdnone marginright5 editme delete-click'
                        ]);

                    if(Yii::$app->user->identity->ban){
                        $button_active = Html::a(
                        'Активировать',
                        '#',
                        [
                            'data-method' => 'post',
                            'class' => 'btn j-primary m-y-1',
                        ]);
                    }else{
                        $button_active = Html::a(
                        'Активировать',
                        Url::to(['myaccount/active', 'id' => $ad['id']]),
                        [
                            'data-method' => 'post',
                            'class' => 'btn j-primary m-y-1'
                        ]);
                    }
                }
                $free_ads ='';
                if(Yii::$app->controller->action->id != 'favorite') {
                    if(empty($ad['parent_parent_category_name']))
                        $free_ads = '<i class="fa fa-star statistic-icons" aria-hidden="true"></i> В категории: ' . $ad['parent_category_name'] . ' у Вас обьявлений: ' . Yii::$app->params['myaccount']['cat_limit'][$ad['parent_category_alias']]['count'];
                    else
                        $free_ads = '<i class="fa fa-star statistic-icons" aria-hidden="true"></i> В категории: ' . $ad['parent_parent_category_name'] . ' у Вас обьявлений: ' . Yii::$app->params['myaccount']['cat_limit'][$ad['parent_parent_category_alias']]['count'];

           
                $free_ads .= '
                <div class="product-social_bar myaccount">
                    <ul>
                        <li>
                            <a href="#" class="product-social_bar-item" 
                            onclick="Share.facebook(\'https://jandooo.com/ads/' .  $ad['alias'] . '\', \'' . $ad['name'] . '\', \'/web/images/store/' .  $ad['mainImg']['filePath'] . '\', \'' . $ad['name'] . '\')">
                                <i class="fa fa-facebook-square"></i>
                            </a>
                        </li>
                        <li><a href="#" class="product-social_bar-item" onclick="Share.google(\'https://jandooo.com/ads/' . strip_tags($ad['alias']) . '\')"><i class="fa fa-google-plus-square"></i></a></li>
                        <li><a href="#" class="product-social_bar-item" onclick="Share.twitter(\'https://jandooo.com/ads/' . $ad['alias'] . '\', \'' . strip_tags($ad['name']) . '\')"><i class="fa fa-twitter-square"></i></a></li>
                    </ul>
                </div>
                ';

                    $myaccount_statistic = '
                       <tr>
                        <td colspan="3">' . $order_ . '</td>
                      
                        <td class="myaccount-statistic" data-ad-id="'.$ad['id'].'" colspan="4">
                            <div class="myaccount-statistic__item del-view-ad">
                                <i class="fa fa-eye statistic-icons" aria-hidden="true"></i> Просмотры: <span>'.$ad['views'].'</span>
                               <!-- <i class="fa fa-angle-right" aria-hidden="true"></i> -->
                            </div>
                            <div class="delete-ad-count"><a href="#">Обнулить</a><span> количество просмотров</span></div>


                            <div class="myaccount-statistic__item del-view-phone">
                                <i class="fa fa-phone statistic-icons" aria-hidden="true"></i> Тел.: <span>'.$ad['phone_view'].'</span>
                               <!-- <i class="fa fa-angle-right" aria-hidden="true"></i> -->
                            </div>
                            <div class="delete-phone-count"><a href="#">Обнулить</a><span> количество просмотров</span></div>

                            <div class="myaccount-statistic__item">
                                <i class="fa fa-star statistic-icons" aria-hidden="true"></i> В избранном: '.$ad['favorite_view'].'
                            </div>
                            <div class="myaccount-statistic__item">
                                ' . $free_ads . '
                            </div>
                        </td>
                        <td></td>
                    </tr>';

                    $ad_info = '<td class="td" data-price="0"><p class="large lheight18 pdingright5 myacc-price"><span class="nowrap ">' . $ad['price'] . ' ' . self::getTypePayment()[$ad['type_payment']] . '</span></p></td>
                <td class="td nowrap myacc-msgs"><span class="message-envelope"><i class="fa fa-envelope-o" aria-hidden="true"></i>'. $message.'</span></td>';
                }

                if(Yii::$app->controller->action->id == 'favorite'){
                    if(strcmp(Yii::$app->request->cookies->getValue('ad_' . $ad['id'], 'en'), 'en') !== 0)
                        $button_active = '<p class="like"><span class="favorite-text">Удалить из<br> избранного</span> <a href="#" data-id="'. $ad['id'] .'" data-icon="star-filled" class="favorite-out"></a></p>';
                    else
                        $button_active = '<p class="like"><span class="favorite-text">В избранное</span> <a href="#" data-id="'. $ad['id'] .'"  class="favorite"><i class="fa fa-star-o" aria-hidden="true"></i></a></p>';

                }

                $renderAds .='<tr class="tr row-elem">

            <td class="td tcenter"><input type="checkbox" class="checkbox adID forsale" value="448122602" name="id[]" id="renderCheckbox1-1"></td>
            <td class="td"><p class="date dateContainer nowrap marginleft5 marginright5">C: ' . $time . '<br>По: '.$time_for.'</p></td>
            <td>' . self::getImagesForTablesAllAds($ad) . '</td>
            <td class="td">
                <div class="title pdingleft8 waiting">
                    <h3 class="normal inlblk brkword fbold" title="' . $ad['name'] . '">' . $ad['name'] . '</h3>
                    <div class="x-small lheight16 margintop3 rel">     
                        <a class="tdnone marginright5" title="Просмотреть" href="' . Url::to(['ads/view', 'alias' => $ad['alias']]) . '"><i class="fa fa-external-link" aria-hidden="true"></i>Просмотреть</a>
                        '.$links.'
                    </div>
                </div>
            </td>
            ' . $ad_info . '
            <td width="200" class="td tright myacc-last-col">'.$button_active.'</td>
            </tr>
            ' . $myaccount_statistic . '';


            }
            $renderAds .='</table>';
        }

        return $renderAds;
    }


    public static function listSubFields($sub_fields, $values_sub_fields = null)
    {
        $render_html = '';

        foreach ($sub_fields as $sub_field){
            $j_values = array_values(json_decode($sub_field['value_sub_field'], true));
            $j_keys = array_keys(json_decode($sub_field['value_sub_field'], true));

            if($sub_field['type'] == 'text' || $sub_field['type'] == 'number'){
                $render_html .= '<li><b>' . $sub_field['name'] . ': </b>'.$values_sub_fields[$sub_field['name_field']].'</li>';
            }
            elseif($sub_field['type'] == 'radio' || $sub_field['type'] == 'checkbox'){

                $j_sub_files = '';
                for($j = 0; $j < count($j_values); $j++)
                    if($sub_field['type'] == 'radio'){
                        if(in_array($j_keys[$j], $values_sub_fields))
                            $j_sub_files .= $j_keys[$j];
                    }
                    else{              
                        if(in_array($j_values[$j], array_keys($values_sub_fields)))
                            $j_sub_files .= '<span class="sub_field_checkbox">' . $j_keys[$j] . '</span>';

                    }

                if(empty($j_sub_files)) continue;
                $render_html .= '<li><b>' . $sub_field['name'] . ': </b>' . $j_sub_files . '</li>';
            }
            elseif($sub_field['type'] == 'select'){

                $j_sub_files = '';
                for($j = 0; $j < count($j_values); $j++){
                    if(strcmp($j_values[$j], $values_sub_fields[$sub_field['name_field']]) == 0){
                        $j_sub_files .= $j_keys[$j];
                    }
                }

                if(empty($j_sub_files)) continue;
                $render_html .= '<li><b>' . $sub_field['name'] . ': </b>'.$j_sub_files.'</li>';
            }
            elseif($sub_field['type'] == 'select["multiple" => true]'){

                $j_sub_files = '';
                for($j = 0; $j < count($j_values); $j++){
                    if(in_array($j_values[$j], $values_sub_fields[$sub_field['name_field']])){
                        $j_sub_files .= $j_keys[$j];$render_html .= '<li><b>' . $sub_field['name'] . ': </b>' . $j_sub_files . '</li>';
                    }
                }
            }
        }
        return $render_html;
    }

    public static function renderSubFields($sub_fields, $values_sub_fields = null, $search = null)
    {
        $render_html = '';
        foreach ($sub_fields as $sub_field){
            $j_values = array_values(json_decode($sub_field['value_sub_field'], true));
            $j_keys = array_keys(json_decode($sub_field['value_sub_field'], true));


            if($sub_field['type'] == 'text' || $sub_field['type'] == 'number'){

                $required = '';
                if($sub_field['required'] == '1'){
                    $required = 'required="required"';
                }

                $render_html .= '<div id="categories" class="inputs_js fblock clr pdingright0 catSelector3"><div class="fleft label tright"><label class="validation fbold c000">' . $sub_field['name'] . ':</label></div><div class="area fleft"><div id="category-breadcrumb-container" class="vmiddle"><input '.$required.' class="" name="Ads[sub_fields][' . $sub_field['name_field'] . ']" type="' . $sub_field['type'] . '"  value="'.$values_sub_fields[$sub_field['name_field']].'"></div></div></div>';
            }
            elseif($sub_field['type'] == 'radio' || $sub_field['type'] == 'checkbox'){

                $required = '';
                if($sub_field['required'] == '1'){
                    $required = 'required="required"';
                }


                $j_sub_files = '';
                for($j = 0; $j < count($j_values); $j++)
                    if($sub_field['type'] == 'radio')
                        if(in_array($j_keys[$j], $values_sub_fields))
                            $j_sub_files .= '<label><input '.$required.' name="Ads[sub_fields]['. $j_values[$j] . ']" type="'. $sub_field['type'] . '" value="'.$j_keys[$j].'" checked> '. $j_keys[$j] . '</label>';
                        else $j_sub_files .= '<label><input '.$required.' name="Ads[sub_fields]['. $j_values[$j] . ']" type="'. $sub_field['type'] . '" value="'.$j_keys[$j].'"> '. $j_keys[$j] . '</label>';
                    else                    
                        if(in_array($j_values[$j], array_keys($values_sub_fields)))
                            $j_sub_files .= '<label><input '. $required .' name="Ads[sub_fields]['. $j_values[$j] . ']" type="'. $sub_field['type'] . '" value="'.$j_keys[$j].'" checked> '. $j_keys[$j] . '</label>';
                        else $j_sub_files .= '<label><input '. $required .' name="Ads[sub_fields]['. $j_values[$j] . ']" type="'. $sub_field['type'] . '" value="'.$j_keys[$j].'"> '. $j_keys[$j] . '</label>';

                
                $render_html .= '<div id="categories" class="inputs_js fblock clr pdingright0 catSelector3"><div class="fleft label tright"><label class="validation fbold c000">' . $sub_field['name'] . ':</label></div><div class="area fleft"><div id="category-breadcrumb-container" class="vmiddle">'.$j_sub_files.'</div></div></div>';
            }
            elseif($sub_field['type'] == 'select'){

                $required = '';
                if($sub_field['required'] == '1'){
                    $required = 'required="required"';
                }

                $j_sub_files = '<select '.$required.' class="dropdown light" name="Ads[sub_fields]['. $sub_field['name_field'] . ']">';
                if($search !== null)
                    $j_sub_files .= '<option value="ne-ukazano">'.$sub_field['name'].'</option>';
                for($j = 0; $j < count($j_values); $j++){
                    if(strcmp($j_values[$j], $values_sub_fields[$sub_field['name_field']]) == 0)
                        $j_sub_files .= '<option value="'. $j_values[$j] .'" selected> '. $j_keys[$j] . '</option>';
                    else $j_sub_files .= '<option value="'. $j_values[$j] .'"> '. $j_keys[$j] . '</option>';
                }
                $j_sub_files .= '</select>';


                if($search !== null)
                    $render_html .= '<div id="categories" class="inputs_js fblock clr pdingright0 catSelector3"><div class="area fleft"><div id="category-breadcrumb-container" class="vmiddle">'.$j_sub_files.'</div></div></div>';
                else
                    $render_html .= '<div id="categories" class="inputs_js fblock clr pdingright0 catSelector3"><div class="fleft label tright"><label class="validation fbold c000">' . $sub_field['name'] . ':</label></div><div class="area fleft"><div id="category-breadcrumb-container" class="vmiddle">'.$j_sub_files.'</div></div></div>';
            }
            elseif($sub_field['type'] == 'select["multiple" => true]'){

                $required = '';
                if($sub_field['required'] == '1'){
                    $required = 'required="required"';
                }

                $j_sub_files = '<select '.$required.' class="dropdown light" name="Ads[sub_fields]['. $sub_field['name_field'] . '][]" multiple="multiple">';
                for($j = 0; $j < count($j_values); $j++){
                    if(in_array($j_values[$j], $values_sub_fields[$sub_field['name_field']]))
                        $j_sub_files .= '<option value="'. $j_values[$j] .'" selected> '. $j_keys[$j] . '</option>';
                    else $j_sub_files .= '<option value="'. $j_values[$j] .'"> '. $j_keys[$j] . '</option>';
                }
                $j_sub_files .= '</select>';


                $render_html .= '<div id="categories" class="inputs_js fblock clr pdingright0 catSelector3"><div class="fleft label tright"><label class="validation fbold c000">' . $sub_field['name'] . ':</label></div><div class="area fleft"><div id="category-breadcrumb-container" class="vmiddle">'.$j_sub_files.'</div></div></div>';
            }
        }
        return $render_html;
    }

    public function getFullHtmlProducts($last_ads, $row = 3)
    {
        $render_html = '';
        $count_ads = count($last_ads);

        // echo '<pre>';
        // print_r($last_ads);
        // die;
        $render_html .= '<div class="row five item">';
        for($i = 0, $j = 1; $i < $count_ads; $i++, $j++){
            /*if($j == 1)
                $render_html .= '<div class="row five item">';
            */
                $render_html .= self::FullRenderProduct($last_ads[$i]);

            /*if($j == $row || ($i == $count_ads - 1)){
                $render_html .= '</div>';
                $j = 0;
            }*/
        }
        $render_html .= '</div>';
        return $render_html;
    }

    public function getFullHtmlProducts2($last_ads, $row = 3)
    {
        $render_html = '';
        $count_ads = count($last_ads);

        for($i = 0, $j = 1; $i < $count_ads; $i++, $j++){
            if($j == 1)
                $render_html .= '<div class="row five item">';

                $render_html .= self::FullRenderProduct($last_ads[$i]);

            if($j == $row || ($i == $count_ads - 1)){
                $render_html .= '</div>';
                $j = 0;
            }
        }
        return $render_html;
    }

    public function FullRenderProduct($last_ad, $money = true)
    {
        // echo '<pre>';
        // print_r($last_ad);
        // die;
        $title          = htmlspecialchars_decode($last_ad['name']);
        if(mb_strlen($last_ad['name']) > 23)
            $last_ad['name'] = mb_substr($last_ad['name'], 0, 21) . '...';
        
        if(mb_strlen($last_ad['location']) > 23)
            $last_ad['location'] = mb_substr($last_ad['location'], 0, 21) . '...';
        

        $url            = Url::to(['ads/view', 'alias' => $last_ad['alias']]);
        $category_url   = Url::to(['category/view', 'cat' => $last_ad['parent_category_alias'], 'subcat' => $last_ad['category_alias']]);
        $name           = htmlspecialchars_decode($last_ad['name']);
        
        if(mb_strlen( $last_ad['category_name']) > 23)
            $category_name = mb_substr( $last_ad['category_name'], 0, 21) . '...';
        else
            $category_name  = $last_ad['category_name'];

        if(empty(intval($last_ad['price'])))
            $price      = 'Без цены';
        else{
        	if($money)
            	$price      = $last_ad['price'] . ' ' . self::getTypePayment()[$last_ad['type_payment']];
            else
            	$price      = $last_ad['price'];
        }
        $city           = htmlspecialchars_decode($last_ad['location']);

        $render_html = '<div class="item-select ';

        if(!empty($last_ad['type'])){
            foreach ($last_ad['type'] as $key) {
                if($key['type'] == 2)
                    $render_html .= ' vip ';
               
            }

        } 
        $render_html .= '">';

        if(!empty($last_ad['type'])){
            foreach ($last_ad['type'] as $key) {
                if($key['type'] == 5)
                    $render_html .= ' <p class="img-top-urgently"><span class="img-top-text-urgently">Срочно</span></p> '; //<img class="img-urgently" src="/images/urgently.png" alt="">
                if($key['type'] == 3)
                    $render_html .= ' <p class="img-top"></p><span class="img-top-text">ТОП</span> ';

            }   
        }
                    
    
        
        $render_html .='<a href="' . $url . '">';
        $render_html .= self::getMiniImage($last_ad, $title);
        $render_html .= '</a>';

        $render_html .= '<p><a href="' . $url . '" title="' . $title . '">' . $name . '</a></p><p class="ladprice">Цена: <span>' . $price . '</span></p><div class="additional-info"><p class="city">'. $city .'</p><p><a href="' . $category_url . '" title="' . $category_name . '">' . $category_name . '</a></p></div></div>';
                                                                   
                        
        return $render_html;
    }

    public static function getLikeAds($ad_id, $ad_category_id, $ad_parent_id)
    {
        $like_ads = Yii::$app->db->createCommand('
            SELECT a.*, b.name as category_name, b.alias as category_alias, d.filePath, d.isMain, g.name as parent_category_name, g.alias as parent_category_alias
            FROM jandoo_ads as a 
            LEFT JOIN jandoo_categories as b ON a.category_id = b.id
            LEFT JOIN jandoo_categories as g ON (SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = g.id
            LEFT JOIN jandoo_image as d ON a.id = d.itemId
            WHERE a.id != :ads_id AND a.active = 1 AND a.category_id = :category_id AND d.modelName = "Ads" AND d.isMain = 1 LIMIT 15')->bindValue(':ads_id', $ad_id)->bindValue(':category_id', $ad_category_id)->queryall();
        
        $ads_ids = [];for($i = 0; $i < count($like_ads); $ads_ids[$i] = $like_ads[$i]['id'],$i++){;}
        $ads_has_img = \frontend\models\AdsHasImage::find()->where(['in', 'ads_id', $ads_ids])->where(['type' => 2])->indexBy('ads_id')->asArray()->all();

        if(empty($like_ads)){

            $like_ads = Yii::$app->db->createCommand('
                SELECT a.*, b.name as category_name, b.alias as category_alias, d.filePath, d.isMain, g.name as parent_category_name, g.alias as parent_category_alias
                FROM jandoo_ads as a 
                LEFT JOIN jandoo_categories as b ON a.category_id = b.id
                LEFT JOIN jandoo_categories as g ON (SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = g.id
                LEFT JOIN jandoo_image as d ON a.id = d.itemId
                WHERE a.id != :ads_id AND a.active = 1 AND a.category_id IN (SELECT id FROM jandoo_categories WHERE parent_id = :parent_id) AND d.modelName = "Ads" AND d.isMain = 1 LIMIT 15')->bindValue(':ads_id', $ad_id)->bindValue(':parent_id', $ad_parent_id)->queryall();
        
            $ads_ids = [];for($i = 0; $i < count($like_ads); $ads_ids[$i] = $like_ads[$i]['id'],$i++){;}
            $ads_has_img = \frontend\models\AdsHasImage::find()->where(['in', 'ads_id', $ads_ids])->where(['type' => 2])->indexBy('ads_id')->asArray()->all();
        }

         for ($i = 0; $i<count($like_ads); $i++) {
            if(!empty($ads_has_img[$like_ads[$i]['id']])){
                $like_ads[$i]['type'] = 2;
            }
        }

        return  Ads::getFullHtmlProducts2($like_ads, 1);
    }

    public static function getUserAds($user_id, $ad_id)
    {

        $user_ads = Yii::$app->db->createCommand('SELECT a.*, b.name as category_name, g.name as parent_category_name, g.alias as parent_category_alias, b.alias as category_alias, d.filePath, d.isMain
            FROM jandoo_ads as a 
            LEFT JOIN jandoo_categories as b ON a.category_id = b.id
            LEFT JOIN jandoo_categories as g ON (SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = g.id
            LEFT JOIN jandoo_image as d ON a.id = d.itemId
            WHERE a.user_id = :user_id AND a.id != :ad_id AND d.modelName = "Ads" AND a.active = 1 AND d.isMain = 1 LIMIT 15')->bindValue(':user_id', $user_id)->bindValue(':ad_id', $ad_id)->queryall();


        $ads_ids = [];for($i = 0; $i < count($user_ads); $ads_ids[$i] = $user_ads[$i]['id'],$i++){;}
        $ads_has_img = \frontend\models\AdsHasImage::find()->where(['in', 'ads_id', $ads_ids])->where(['type' => 2])->indexBy('ads_id')->asArray()->all();

        for ($i = 0; $i<count($user_ads); $i++) {
            if(!empty($ads_has_img[$user_ads[$i]['id']])){
                $user_ads[$i]['type'] = 2;
            }
        }
        return Ads::getFullHtmlProducts2($user_ads, 1);

    }

    public static function getAdsByCategoryId($category_id, $limit = 30, $type_ads = null, $img = null, $sprice = null, $eprice = null)
    {
        $sort = self::getCategorySort();

        $child_cat = Categories::find()->select('id')->where(['parent_id' => $category_id])->indexBy('id')->asArray()->all();
        $child_cat[$category_id] = $category_id;

        if($type_ads !== null){
            $ta[0] = 'AND a.type_ads = ' . $type_ads . '';
            $ta[1] = 'AND type_ads = ' . $type_ads . '';
        }

        if($sprice !== null){

            if(!empty($_GET['course'])){
                $course = \frontend\models\CourseMoney::getCurrentCourse();
                $cur_del = 0;
                if($_GET['course'] === 'usd'){
                    $cur_del = $course['usd_sale'];
                }else{
                    $cur_del = $course['eur_sale'];
                }
                $ta[2] = 'AND a.price >= ' . $sprice * $cur_del;
                $ta[3] = 'AND price >= ' . $sprice * $cur_del;
            }else{
                $ta[2] = 'AND a.price >= ' . $sprice;
                $ta[3] = 'AND price >= ' . $sprice;
            }

            $ta[2] = 'AND a.price >= ' . $sprice;
            $ta[3] = 'AND price >= ' . $sprice;
        }
        if($eprice !== null){
            if(!empty($_GET['course'])){
                $course = \frontend\models\CourseMoney::getCurrentCourse();
                $cur_del = 0;
                if($_GET['course'] === 'usd'){
                    $cur_del = $course['usd_sale'];
                }else{
                    $cur_del = $course['eur_sale'];
                }

                $ta[4] = 'AND a.price <= ' . $eprice * $cur_del;
                $ta[5] = 'AND price <= ' . $eprice * $cur_del;
            }else{
               $ta[4] = 'AND a.price <= ' . $eprice;
                $ta[5] = 'AND price <= ' . $eprice; 
            }
        }

		$success_get = ['img', 'course', 'ads', 'sort', 'cat', 'subcat', 'q', 'reg', 'sprice', 'eprice', '_pjax', 'page', 'per-page'];
		$new_sql_param = [];
		$new_sql = [];
        foreach (Yii::$app->request->get() as $key => $value) {
            if(!in_array($key, $success_get)){
                $new_sql_param[$key] = htmlspecialchars($value);
                $new_sql[0] .= ' AND aa.value_sub_field LIKE \'%"' . $key . '":"' . $value . '"%\'';
                $new_sql[1] .= ' AND value_sub_field LIKE \'%"' . $key . '":"' . $value . '"%\'';
            }
        }

        $top = (new \yii\db\Query())
                    ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, dd.type as top, gg.filePath')
                    ->from('jandoo_ads as a')
                    ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
                    ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                    ->leftJoin('jandoo_ads_has_images as dd', 'a.id = dd.ads_id')
                    ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                    ->where('gg.isMain = 1 AND dd.validity_at > ' . time() . ' AND a.active = 1 AND dd.type = 5 AND a.category_id IN ('.implode(', ', array_keys($child_cat)).') ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4] . ' ' .  $new_sql[0])->groupBy('a.id')->all();

        $top_ids = array_column($top, 'id');
self::getCategoryAdsImgInf($top);

        if($img != null){

        	if(!empty($new_sql_param)){
        		$ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, gg.filePath')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->where('gg.isMain = 1 AND a.category_id IN ('.implode(', ', array_keys($child_cat)).') AND a.active = 1 ' . $ta[0] . ' ' . $ta[2] . ' ' . $ta[4] . ' '  . $new_sql[0])->andWhere(['not in', 'a.id', $top_ids]);


            	$count = (new \yii\db\Query())->select('id')
            	    ->from('jandoo_ads as a')
            	    ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
            	    ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
            	    ->where('gg.isMain = 1  AND a.category_id IN ('.implode(', ', array_keys($child_cat)).') AND a.active = 1 ' . $ta[0] . ' ' . $ta[2] . ' ' . $ta[4] . ' '  . $new_sql[0])->andWhere(['not in', 'a.id', $top_ids]);
        	}else{
            	$ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, gg.filePath')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->where('gg.isMain = 1 AND a.category_id IN ('.implode(', ', array_keys($child_cat)).') AND a.active = 1 ' . $ta[0] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in', 'a.id', $top_ids]);


            	$count = (new \yii\db\Query())->select('a.id')->from('jandoo_ads as a')->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')->where('gg.isMain = 1 AND category_id IN ('.implode(', ', array_keys($child_cat)).') AND active = 1 ' . $ta[0] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in', 'a.id', $top_ids]);
        	}
        }else{
            if(!empty($new_sql_param)){
            	$ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->where('a.category_id IN ('.implode(', ', array_keys($child_cat)).') AND a.active = 1 ' . $ta[0] . ' ' . $ta[2] . ' ' . $ta[4] . ' '  . $new_sql[0])->andWhere(['not in', 'a.id', $top_ids]);


            	$count = (new \yii\db\Query())->select('id')
            	    ->from('jandoo_ads as a')
            	    ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
            	    ->where('a.category_id IN ('.implode(', ', array_keys($child_cat)).') AND a.active = 1 ' . $ta[0] . ' ' . $ta[2] . ' ' . $ta[4] . ' '  . $new_sql[0])->andWhere(['not in', 'a.id', $top_ids]);
            }else{


            	$ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.validity_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->where('a.category_id IN ('.implode(', ', array_keys($child_cat)).') AND a.active = 1 ' . $ta[0] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in', 'a.id', $top_ids]);

                 
            	$count = (new \yii\db\Query())->select('id')->from('jandoo_ads')->where('category_id IN ('.implode(', ', array_keys($child_cat)).') AND active = 1 ' . $ta[1] . ' ' . $ta[3] . ' ' . $ta[5])->andWhere(['not in', 'id', $top_ids]);
            }
        }
        $pag = self::getCategoryPagination($ads, $limit, $count, $sort);

        // echo '<pre>';
        //     print_r($pag);
        //     die;

        self::getCategoryAdsImgInf($pag['ads']);

        

        return [
            'ads' => $pag['ads'],
            'sort' => $sort,
            'top' => $top,
            'pagination' => $pag['pages'],
        ];
    }

    public static function getAdsCatIdSearch($q, $reg = null, $category_id, $limit = 30, $type_ads = null, $img = null, $sprice = null, $eprice = null)
    {
        if(empty($reg) || strcmp($reg, 'c-') ==0){
            $reg = null;
        }
        if(empty($q) || strcmp($q, 'q-') ==0){
            $q = null;
        }
        
        $sort = self::getCategorySort();

        $child_cat = Categories::find()->select('id')->where(['parent_id' => $category_id])->indexBy('id')->asArray()->all();
        $child_cat[$category_id] = $category_id;

        $searchField = self::getSearchField($q, $reg);

        if($type_ads !== null){
            $ta[0] = 'AND a.type_ads = ' . $type_ads . '';
            $ta[1] = 'AND type_ads = ' . $type_ads . '';
        }

        if($sprice !== null){

            if(!empty($_GET['course'])){
                $course = \frontend\models\CourseMoney::getCurrentCourse();
                $cur_del = 0;
                if($_GET['course'] === 'usd'){
                    $cur_del = $course['usd_sale'];
                }else{
                    $cur_del = $course['eur_sale'];
                }
                $ta[2] = 'AND a.price >= ' . $sprice * $cur_del;
                $ta[3] = 'AND price >= ' . $sprice * $cur_del;
            }else{
                $ta[2] = 'AND a.price >= ' . $sprice;
                $ta[3] = 'AND price >= ' . $sprice;
            }

            $ta[2] = 'AND a.price >= ' . $sprice;
            $ta[3] = 'AND price >= ' . $sprice;
        }
        if($eprice !== null){
            if(!empty($_GET['course'])){
                $course = \frontend\models\CourseMoney::getCurrentCourse();
                $cur_del = 0;
                if($_GET['course'] === 'usd'){
                    $cur_del = $course['usd_sale'];
                }else{
                    $cur_del = $course['eur_sale'];
                }

                $ta[4] = 'AND a.price <= ' . $eprice * $cur_del;
                $ta[5] = 'AND price <= ' . $eprice * $cur_del;
            }else{
               $ta[4] = 'AND a.price <= ' . $eprice;
                $ta[5] = 'AND price <= ' . $eprice; 
            }
        }

        if(!empty($searchField['a.location']) && !empty($searchField['location'])){
            $searchField['a.location'] = 'AND ('.$searchField['a.location'].')';
            $searchField['location'] = 'AND ('.$searchField['location'].')';
        }
        if(!empty($searchField['a.name']) && !empty($searchField['a.text'])){
            $searchField['a.val'] = 'AND ('.$searchField['a.name'].')';
            $searchField['val'] = 'AND ('.$searchField['name'].')';
        }

        $success_get = ['img', 'course', 'ads', 'sort', 'cat', 'subcat', 'q', 'reg', 'sprice', 'eprice', '_pjax', 'page', 'per-page'];
        $new_sql_param = [];
        $new_sql = [];
        foreach (Yii::$app->request->get() as $key => $value) {
            if(!in_array($key, $success_get)){
                $new_sql_param[$key] = htmlspecialchars($value);
                $new_sql[0] .= ' AND aa.value_sub_field LIKE \'%"' . $key . '":"' . $value . '"%\'';
                $new_sql[1] .= ' AND value_sub_field LIKE \'%"' . $key . '":"' . $value . '"%\'';
            }
        }


        $top = (new \yii\db\Query())
                    ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, a.validity_at, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, dd.type as top, gg.filePath')
                    ->from('jandoo_ads as a')
                    ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
                    ->leftJoin('jandoo_ads_has_images as dd', 'a.id = dd.ads_id')
                    ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                    ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                    ->where('gg.isMain = 1 AND dd.validity_at > ' . time() . ' AND dd.validity_at > ' . time() . ' AND a.active = 1 AND dd.type = 5 AND a.category_id IN ('.implode(', ', array_keys($child_cat)).') ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4] . ' ' .  $new_sql[0])->groupBy('a.id')->all();

        $top_ids = array_column($top, 'id');

self::getCategoryAdsImgInf($top);
        if($img !== null){
            if(!empty($new_sql_param)){
                     $ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.validity_at, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, gg.filePath')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
                ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->where('gg.isMain = 1 AND a.category_id IN ('.implode(', ', array_keys($child_cat)).') AND a.active = 1 ' . $ta[0] . ' ' . $searchField['a.location'] . ' ' . $searchField['a.val'] . ' ' . $ta[2] . ' ' . $ta[4] . ' '  . $new_sql[0])->andWhere(['not in', 'a.id', $top_ids]);


                $count = (new \yii\db\Query())->select('a.id')
                        ->from('jandoo_ads as a')
                        ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                        ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
                        ->where('gg.isMain = 1 AND category_id IN ('.implode(', ', array_keys($child_cat)).') AND a.active = 1 '. $ta[0] . ' ' . $searchField['a.location'] . ' ' . $searchField['a.val'] . ' ' . $ta[2] . ' ' . $ta[4] . ' '  . $new_sql[0])->andWhere(['not in', 'a.id', $top_ids]);
            }else{
                 $ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, a.validity_at, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, gg.filePath')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->where('gg.isMain = 1 AND a.category_id IN ('.implode(', ', array_keys($child_cat)).') AND a.active = 1 ' . $ta[0] . ' ' . $searchField['a.location'] . ' ' . $searchField['a.val'] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in', 'a.id', $top_ids]);


                $count = (new \yii\db\Query())->select('a.id')->from('jandoo_ads as a')->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')->where('gg.isMain = 1 AND category_id IN ('.implode(', ', array_keys($child_cat)).') AND a.active = 1 '. $ta[0] . ' ' . $searchField['a.location'] . ' ' . $searchField['a.val'] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in', 'a.id', $top_ids]);
            }
        }else{
            if(!empty($new_sql_param)){
                $ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, a.validity_at, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->where('a.category_id IN ('.implode(', ', array_keys($child_cat)).') AND a.active = 1 ' . $ta[0] . ' ' . $searchField['a.location'] . ' ' . $searchField['a.val'] . ' ' . $ta[2] . ' ' . $ta[4] . ' '  . $new_sql[0])->andWhere(['not in', 'a.id', $top_ids]);


                $count = (new \yii\db\Query())->select('id')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
                ->where('category_id IN ('.implode(', ', array_keys($child_cat)).') AND active = 1 '. $ta[0] . ' ' . $searchField['location'] . ' ' . $searchField['val'] . ' ' . $ta[2] . ' ' . $ta[4] . ' '  . $new_sql[0])->andWhere(['not in', 'a.id', $top_ids]);
            }else{
                $ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, a.validity_at, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->where('a.category_id IN ('.implode(', ', array_keys($child_cat)).') AND a.active = 1 ' . $ta[0] . ' ' . $searchField['a.location'] . ' ' . $searchField['a.val'] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in', 'a.id', $top_ids]);


            $count = (new \yii\db\Query())->select('id')->from('jandoo_ads')->where('category_id IN ('.implode(', ', array_keys($child_cat)).') AND active = 1 '. $ta[1] . ' ' . $searchField['location'] . ' ' . $searchField['val'] . ' ' . $ta[3] . ' ' . $ta[5])->andWhere(['not in', 'id', $top_ids]);
            }
        }

        $pag = self::getCategoryPagination($ads, $limit, $count, $sort);


        self::getCategoryAdsImgInf($pag['ads']);

        return [
            'ads' => $pag['ads'],
            'sort' => $sort,
            'top' => $top,
            'pagination' => $pag['pages'],
        ];
    }

    public static function getAdsByCityId($city, $limit = 30)
    {
        $sort = self::getCategorySort();

        if(empty($city['parent_category_id'])){
            $ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->where(['a.reg_id' => $city['category_id']])
                ->andWhere('a.active = 1');
            $count = (new \yii\db\Query())->select('id')->from('jandoo_ads')->where(['reg_id' => $city['category_id']])->andWhere('active = 1');
        }
        else{
            $ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->where(['a.city_id' => $city['category_id']])
                ->andWhere(['a.reg_id' => $city['parent_category_id']])
                ->andWhere('a.active = 1');
            $count = (new \yii\db\Query())->select('id')->from('jandoo_ads')->where(['reg_id' => $city['parent_category_id']])->andWhere(['city_id' => $city['category_id']])->andWhere('active = 1');
        }


        $pag = self::getCategoryPagination($ads, $limit, $count, $sort);


        self::getCategoryAdsImgInf($pag['ads']);

        return [
            'ads' => $pag['ads'],
            'sort' => $sort,
            'pagination' => $pag['pages'],
        ];
    }

    public static function getAllAds($limit = 30, $type_ads = null, $img = null, $sprice = null, $eprice = null)
    {
        $sort = self::getCategorySort();


        if($type_ads !== null){
            $ta[0] = 'AND a.type_ads = ' . $type_ads . '';
            $ta[1] = 'AND type_ads = ' . $type_ads . '';
        }

        if($sprice !== null){

            if(!empty($_GET['course'])){
                $course = \frontend\models\CourseMoney::getCurrentCourse();
                $cur_del = 0;
                if($_GET['course'] === 'usd'){
                    $cur_del = $course['usd_sale'];
                }else{
                    $cur_del = $course['eur_sale'];
                }
                $ta[2] = 'AND a.price >= ' . $sprice * $cur_del;
                $ta[3] = 'AND price >= ' . $sprice * $cur_del;
            }else{
                $ta[2] = 'AND a.price >= ' . $sprice;
                $ta[3] = 'AND price >= ' . $sprice;
            }
        }
        if($eprice !== null){
            if(!empty($_GET['course'])){
                $course = \frontend\models\CourseMoney::getCurrentCourse();
                $cur_del = 0;
                if($_GET['course'] === 'usd'){
                    $cur_del = $course['usd_sale'];
                }else{
                    $cur_del = $course['eur_sale'];
                }

                $ta[4] = 'AND a.price <= ' . $eprice * $cur_del;
                $ta[5] = 'AND price <= ' . $eprice * $cur_del;
            }else{
               $ta[4] = 'AND a.price <= ' . $eprice;
                $ta[5] = 'AND price <= ' . $eprice; 
            }
        }

        // echo '<pre>';
        // print_r($sprice);
        // die;


        $top = (new \yii\db\Query())
                    ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, dd.type as top, gg.filePath')
                    ->from('jandoo_ads as a')
                    ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
                    ->leftJoin('jandoo_ads_has_images as dd', 'a.id = dd.ads_id')
                    ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                    ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                    ->where('gg.isMain = 1 AND a.active = 1 AND dd.type = 5 ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4] . ' ' .  $new_sql[0])->groupBy('a.id')->all();

        $top_ids = array_column($top, 'id');


        if($img !== null){
             $ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, gg.filePath')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->where('gg.isMain = 1 AND a.active = 1 ' . $ta[0] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in', 'a.id', $top_ids]);;


                $count = (new \yii\db\Query())->select('a.id')->from('jandoo_ads as a')->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')->where('gg.isMain = 1 AND a.active = 1 '. $ta[0] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in', 'a.id', $top_ids]);;

        }else{
            $ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->where(' a.active = 1 ' . $ta[0] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in', 'a.id', $top_ids]);;


            $count = (new \yii\db\Query())->select('id')->from('jandoo_ads')->where('active = 1 '. $ta[1] . ' ' . $ta[3] . ' ' . $ta[5])->andWhere(['not in', 'id', $top_ids]);;
        }


        // $ads = (new \yii\db\Query())
        //     ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id')
        //     ->from('jandoo_ads as a')
        //     ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
        //     ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
        //     ->where('a.active = 1');


        // $count = (new \yii\db\Query())->select('id')->from('jandoo_ads')->where('active = 1');
        $pag = self::getCategoryPagination($ads, $limit, $count, $sort);


        self::getCategoryAdsImgInf($pag['ads']);

        return [
            'ads' => $pag['ads'],
            'sort' => $sort,
            'top' => $top,
            'pagination' => $pag['pages'],
        ];
    }

    public static function getSearchAds($q = null, $reg = null, $limit = 30, $type_ads = null, $with_img = null, $sprice = null, $eprice = null)
    {
        $sort = self::getCategorySort();

        $searchField = self::getSearchField($q, $reg);   

        if($type_ads !== null){
            $ta[0] = 'AND a.type_ads = ' . $type_ads . '';
            $ta[1] = 'AND type_ads = ' . $type_ads . '';
        }

        if($sprice !== null){

            if(!empty($_GET['course'])){
                $course = \frontend\models\CourseMoney::getCurrentCourse();
                $cur_del = 0;
                if($_GET['course'] === 'usd'){
                    $cur_del = $course['usd_sale'];
                }else{
                    $cur_del = $course['eur_sale'];
                }
                $ta[2] = ' AND a.price >= ' . $sprice * $cur_del;
                $ta[3] = ' AND price >= ' . $sprice * $cur_del;
            }else{
                $ta[2] = ' AND a.price >= ' . $sprice;
                $ta[3] = ' AND price >= ' . $sprice;
            }

        }
        if($eprice !== null){
            if(!empty($_GET['course'])){
                $course = \frontend\models\CourseMoney::getCurrentCourse();
                $cur_del = 0;
                if($_GET['course'] === 'usd'){
                    $cur_del = $course['usd_sale'];
                }else{
                    $cur_del = $course['eur_sale'];
                }

                $ta[4] = ' AND a.price <= ' . $eprice * $cur_del;
                $ta[5] = ' AND price <= ' . $eprice * $cur_del;
            }else{
               $ta[4] = ' AND a.price <= ' . $eprice;
                $ta[5] = ' AND price <= ' . $eprice; 
            }
        }

        // echo '<pre>';
        // print_r($sprice);
        // echo '<pre>';die;

        if(!empty($searchField['a.location']) && !empty($searchField['location'])){
            $searchField['a.location'] = 'AND ('.$searchField['a.location'].')';
            $searchField['location'] = 'AND ('.$searchField['location'].')';
        }
        if(!empty($searchField['a.name']) && !empty($searchField['a.text'])){
            $searchField['a.val'] = 'AND ('.$searchField['a.name'].')';
            // $searchField['a.val'] = 'AND ('.$searchField['a.name'].' OR '.$searchField['a.text'].')';
            $searchField['val'] = 'AND ('.$searchField['name'].')';
        }

        
        $success_get = ['img', 'course', 'ads', 'sort', 'cat', 'subcat', 'q', 'reg', 'sprice', 'eprice', '_pjax', 'page', 'per-page'];
        $new_sql_param = [];
        $new_sql = [];
        foreach (Yii::$app->request->get() as $key => $value) {
            if(!in_array($key, $success_get)){
                $new_sql_param[$key] = htmlspecialchars($value);
                $new_sql[0] .= ' AND aa.value_sub_field LIKE \'%"' . $key . '":"' . $value . '"%\'';
                $new_sql[1] .= ' AND value_sub_field LIKE \'%"' . $key . '":"' . $value . '"%\'';
            }
        }


        $top = (new \yii\db\Query())
                    ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, dd.type as top, gg.filePath')
                    ->from('jandoo_ads as a')
                    ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
                    ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                    ->leftJoin('jandoo_ads_has_images as dd', 'a.id = dd.ads_id')
                    ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                    ->where('gg.isMain = 1 AND a.active = 1 AND dd.type = 5 ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4] . ' ' .  $new_sql[0])->groupBy('a.id')->all();

        $top_ids = array_column($top, 'id');
self::getCategoryAdsImgInf($top);
        // echo '<pre>';
        // print_r($top);die;

        if(empty($with_img)){
                   if(!empty($new_sql_param)){
                     $ads = (new \yii\db\Query())
                    ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id')
                    ->from('jandoo_ads as a')
                    ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
                    ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                    ->where('a.active = 1 ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4] . ' ' .  $new_sql[0])->andWhere(['not in','a.id', $top_ids]);


                    $count = (new \yii\db\Query())->select('a.id')->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')->from('jandoo_ads as a')->where('a.active = 1 ' . $ta[0] . ' ' . $searchField['val'] . ' ' . $searchField['location'] . ' ' . $ta[2] . ' ' . $ta[4] . ' ' .  $new_sql[0])->andWhere(['not in','a.id', $top_ids]);
                }else{
                     $ads = (new \yii\db\Query())
                    ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id')
                    ->from('jandoo_ads as a')
                    ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                    ->where('a.active = 1 ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in','a.id', $top_ids]);


                    $count = (new \yii\db\Query())->select('id')->from('jandoo_ads')->where('active = 1 ' . $ta[1] . ' ' . $searchField['val'] . ' ' . $searchField['location'] . ' ' . $ta[3] . ' ' . $ta[5])->andWhere(['not in','id', $top_ids]);
                }
        }else{

            if(!empty($new_sql_param)){
                $ads = (new \yii\db\Query())
                    ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, gg.filePath')
                    ->from('jandoo_ads as a')
                    ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                    ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
                    ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                    ->where('gg.isMain = 1 AND a.active = 1 ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4] . ' ' .  $new_sql[0])->andWhere(['not in','a.id', $top_ids]);


                    $count = (new \yii\db\Query())->select('a.id')->from('jandoo_ads as a')->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')->where('gg.isMain = 1 AND a.active = 1 ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4] . ' ' .  $new_sql[0])->andWhere(['not in','a.id', $top_ids]);
                }else{
                    $ads = (new \yii\db\Query())
                    ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, gg.filePath')
                    ->from('jandoo_ads as a')
                    ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')

                    ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                    ->where('gg.isMain = 1 AND a.active = 1 ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in','a.id', $top_ids]);


                    $count = (new \yii\db\Query())->select('a.id')->from('jandoo_ads as a')->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')->where('gg.isMain = 1 AND a.active = 1 ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in','a.id', $top_ids]);
                }

        }

        
        $pag = self::getCategoryPagination($ads, $limit, $count, $sort);
        self::getCategoryAdsImgInf($pag['ads']);


        return [
            'top' => $top,
            'ads' => $pag['ads'],
            'sort' => $sort,
            'pagination' => $pag['pages'],
        ];
    }


    public static function getSearchField($q, $city = null)
    {
        if(!empty($q)){
            $q = explode('-', htmlspecialchars($q));

            $a_name = 'a.name LIKE "%'.$q[0].'%"';
            $name = 'name LIKE "%'.$q[0].'%"';
            $a_text = 'a.text LIKE "%'.$q[0].'%"';
            $text = 'text LIKE "%'.$q[0].'%"';

            for($i = 1; $i < count($q); $i++){
                $a_name .= 'OR a.name LIKE "%'.$q[$i].'%" ';
                $name .= 'OR name LIKE "%'.$q[$i].'%" ';
                $a_text .= 'OR a.text LIKE "%'.$q[$i].'%" ';
                $text .= 'OR text LIKE "%'.$q[$i].'%" ';
            }
        }

        if(!empty($city)){
            $reg_city = (new \yii\db\Query())
                ->select('b.db_defnamelang')
                ->from('jandoo_vd_region as a')
                ->leftJoin('jandoo_vd_city as b', 'b.db_rootid = a.id')
                ->where(['like', 'a.db_defnamelang', mb_substr(explode('-',$city)[0], 0, 4)])->all();

        
            $reg_city = array_column($reg_city, 'db_defnamelang');

            $a_location = 'a.location LIKE "%'.mb_substr(explode(' ',$city)[0], 0, 4).'%" ';
            $location = 'location LIKE "%'.mb_substr(explode(' ',$city)[0], 0, 4).'%" ';

            for($i = 0; $i < count($reg_city); $i++){
                $a_location .= 'OR a.location LIKE "%'.$reg_city[$i].'%" ';
                $location .= 'OR location LIKE "%'.$reg_city[$i].'%" ';
            }

            return ['a.name' => $a_name, 'name' => $name, 'a.text' => $a_text, 'text' => $text, 'a.location' => $a_location, 'location' => $location];
        }
        return ['a.name' => $a_name, 'name' => $name, 'a.text' => $a_text, 'text' => $text];
    }

    public static function getCurrentCity($cat, $subcat = null)
    {
        if($subcat !== null)
            return $category = Yii::$app->db->createCommand('SELECT a.db_defnamelang as category_name, a.id as category_id, a.id as category_alias, a.db_rootid as parent_category_id, a.db_rootid as parent_category_alias, d.db_defnamelang as parent_category_name
            FROM jandoo_vd_city as a 
            LEFT JOIN jandoo_vd_region as d ON a.db_rootid = d.id
            WHERE a.id = :subcat AND a.db_rootid = :cat ')->bindValue(':cat', $cat)->bindValue(':subcat', $subcat)->queryOne();

        return Yii::$app->db->createCommand('SELECT a.db_defnamelang as category_name, a.id as category_id, a.id as category_alias
            FROM jandoo_vd_region as a WHERE a.id = :cat')->bindValue(':cat', $cat)->queryOne();
    }


    public static function getAdsByUserId($q, $reg = null, $user_id, $limit = 30, $type_ads = null, $img = null, $sprice = null, $eprice = null)
    {
        if(empty($reg) || strcmp($reg, 'c-') ==0){
            $reg = null;
        }
        if(empty($q) || strcmp($q, 'q-') ==0){
            $q = null;
        }
        
        $sort = self::getCategorySort();
        $searchField = self::getSearchField($q, $reg);

        if($type_ads !== null){
            $ta[0] = 'AND a.type_ads = ' . $type_ads . '';
            $ta[1] = 'AND type_ads = ' . $type_ads . '';
        }

        if($sprice !== null){

            if(!empty($_GET['course'])){
                $course = \frontend\models\CourseMoney::getCurrentCourse();
                $cur_del = 0;
                if($_GET['course'] === 'usd'){
                    $cur_del = $course['usd_sale'];
                }else{
                    $cur_del = $course['eur_sale'];
                }
                $ta[2] = 'AND a.price >= ' . $sprice * $cur_del;
                $ta[3] = 'AND price >= ' . $sprice * $cur_del;
            }else{
                $ta[2] = 'AND a.price >= ' . $sprice;
                $ta[3] = 'AND price >= ' . $sprice;
            }

        }
        if($eprice !== null){
            if(!empty($_GET['course'])){
                $course = \frontend\models\CourseMoney::getCurrentCourse();
                $cur_del = 0;
                if($_GET['course'] === 'usd'){
                    $cur_del = $course['usd_sale'];
                }else{
                    $cur_del = $course['eur_sale'];
                }

                $ta[4] = 'AND a.price <= ' . $eprice * $cur_del;
                $ta[5] = 'AND price <= ' . $eprice * $cur_del;
            }else{
               $ta[4] = 'AND a.price <= ' . $eprice;
                $ta[5] = 'AND price <= ' . $eprice; 
            }
        }


        if(!empty($searchField['a.location']) && !empty($searchField['location'])){
            $searchField['a.location'] = 'AND ('.$searchField['a.location'].')';
            $searchField['location'] = 'AND ('.$searchField['location'].')';
        }
        if(!empty($searchField['a.name']) && !empty($searchField['a.text'])){
            $searchField['a.val'] = 'AND ('.$searchField['a.name'].')';
            $searchField['val'] = 'AND ('.$searchField['name'].')';
        }

        $top = (new \yii\db\Query())
                    ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, dd.type as top, gg.filePath')
                    ->from('jandoo_ads as a')
                    ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
                    ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                    ->leftJoin('jandoo_ads_has_images as dd', 'a.id = dd.ads_id')
                    ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                    ->where('gg.isMain = 1 AND dd.validity_at > ' . time() . ' AND a.active = 1 AND dd.type = 5 AND a.user_id = "'.$user_id.'" ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4] . ' ' .  $new_sql[0])->groupBy('a.id')->all();

        $top_ids = array_column($top, 'id');

self::getCategoryAdsImgInf($top);
        // echo $user_id; die;
        if($img != null){
            $ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, gg.filePath')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->where('gg.isMain = 1 AND a.user_id = "'.$user_id.'" AND a.active = 1 ' . $ta[0] . ' ' . $searchField['a.location'] . ' ' . $searchField['a.val'] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in', 'a.id', $top_ids]);


        $count = (new \yii\db\Query())->select('a.id')->from('jandoo_ads as a')->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')->where('gg.isMain = 1 AND a.user_id = "'.$user_id.'" AND a.active = 1 '. $ta[0] . ' ' . $searchField['a.location'] . ' ' . $searchField['a.val'] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in', 'a.id', $top_ids]);
        }else{
            $ads = (new \yii\db\Query())
            ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id')
            ->from('jandoo_ads as a')
            ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
            ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
            ->where('a.user_id = "'.$user_id.'" AND a.active = 1 ' . $ta[0] . ' ' . $searchField['a.location'] . ' ' . $searchField['a.val'] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in', 'a.id', $top_ids]);


        $count = (new \yii\db\Query())->select('id')->from('jandoo_ads')->where('user_id = "'.$user_id.'" AND active = 1 '. $ta[1] . ' ' . $searchField['location'] . ' ' . $searchField['val'] . ' ' . $ta[3] . ' ' . $ta[5])->andWhere(['not in', 'id', $top_ids]);
        }

        $pag = self::getCategoryPagination($ads, $limit, $count, $sort);


        self::getCategoryAdsImgInf($pag['ads']);

        return [
            'ads' => $pag['ads'],
            'sort' => $sort,
            'top' => $top,
            'pagination' => $pag['pages'],
        ];
    }

    public static function getVipAds($q, $reg, $limit = 30, $type_ads = null, $img = null, $sprice = null, $eprice = null)
    {
        $sort = self::getCategorySorta();

        $searchField = self::getSearchField($q, $reg);   

        if($type_ads !== null){
            $ta[0] = 'AND a.type_ads = ' . $type_ads . '';
            $ta[1] = 'AND type_ads = ' . $type_ads . '';
        }

        if($sprice !== null){

            if(!empty($_GET['course'])){
                $course = \frontend\models\CourseMoney::getCurrentCourse();
                $cur_del = 0;
                if($_GET['course'] === 'usd'){
                    $cur_del = $course['usd_sale'];
                }else{
                    $cur_del = $course['eur_sale'];
                }
                $ta[2] = 'AND a.price >= ' . $sprice * $cur_del;
                $ta[3] = 'AND price >= ' . $sprice * $cur_del;
            }else{
                $ta[2] = 'AND a.price >= ' . $sprice;
                $ta[3] = 'AND price >= ' . $sprice;
            }
        }
        if($eprice !== null){
            if(!empty($_GET['course'])){
                $course = \frontend\models\CourseMoney::getCurrentCourse();
                $cur_del = 0;
                if($_GET['course'] === 'usd'){
                    $cur_del = $course['usd_sale'];
                }else{
                    $cur_del = $course['eur_sale'];
                }

                $ta[4] = 'AND a.price <= ' . $eprice * $cur_del;
                $ta[5] = 'AND price <= ' . $eprice * $cur_del;
            }else{
               $ta[4] = 'AND a.price <= ' . $eprice;
                $ta[5] = 'AND price <= ' . $eprice; 
            }
        }

        if(!empty($searchField['a.location']) && !empty($searchField['location'])){
            $searchField['a.location'] = 'AND ('.$searchField['a.location'].')';
            $searchField['location'] = 'AND ('.$searchField['location'].')';
        }
        if(!empty($searchField['a.name']) && !empty($searchField['a.text'])){
            $searchField['a.val'] = 'AND ('.$searchField['a.name'].')';
            $searchField['val'] = 'AND ('.$searchField['name'].')';
        }
        // echo '<pre>'; print_r($q );die;

        $top = (new \yii\db\Query())
                    ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, dd.type as top, gg.filePath')
                    ->from('jandoo_ads as a')
                    ->leftJoin('jandoo_field_value as aa', 'aa.ads_id = a.id')
                    ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                    ->leftJoin('jandoo_ads_has_images as dd', 'a.id = dd.ads_id')
                    ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                    ->where('gg.isMain = 1 AND a.active = 1 AND dd.validity_at > ' . time() . ' AND dd.type = 3 ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4] . ' ' .  $new_sql[0])->groupBy('a.id')->all();

        $top_ids = array_column($top, 'id');

        self::getCategoryAdsImgInf($top);


        if($img != null){
            $ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, gg.filePath')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->leftJoin('jandoo_ads_has_images as g', 'g.ads_id= a.id')
                ->where('gg.isMain = 1 AND g.type = 2 AND a.active = 1 ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in', 'a.id', $top_ids]);

        $count = (new \yii\db\Query())->select('a.id')->from('jandoo_ads as a')->leftJoin('jandoo_ads_has_images as g', 'g.ads_id= a.id')->leftJoin('jandoo_image as gg', 'gg.itemId = a.id')->where('gg.isMain = 1 AND g.type = 2 AND a.active = 1 ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in', 'a.id', $top_ids]);
        }else{

            $ads = (new \yii\db\Query())
                    ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id')
                    ->from('jandoo_ads as a')
                    ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                    ->leftJoin('jandoo_ads_has_images as g', 'g.ads_id= a.id')
                    ->where('g.type = 2 AND a.active = 1 ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4] . ' AND g.validity_at > ' . time())->andWhere(['not in', 'a.id', $top_ids]);

            $count = (new \yii\db\Query())->select('a.id')->from('jandoo_ads as a')->leftJoin('jandoo_ads_has_images as g', 'g.ads_id= a.id')->where('g.type = 2 AND g.validity_at > ' . time() . ' AND a.active = 1 ' . $ta[0] . ' ' . $searchField['a.val'] . ' ' . $searchField['a.location'] . ' ' . $ta[2] . ' ' . $ta[4])->andWhere(['not in', 'a.id', $top_ids]);
        }


        $pag = self::getCategoryPagination($ads, $limit, $count, $sort);

        // echo '<pre>';
        // print_r($pag['ads']);
        // die;
        self::getCategoryAdsImgInf($pag['ads']);

        // echo '<pre>';print_r($top);
        // die;
        return [
            'ads' => $pag['ads'],
            'top' => $top,
            'sort' => $sort,
            'pagination' => $pag['pages'],
        ];
    }


    public static function getCategoryPagination($ads, $limit, $count, $sort)
    {
        $pages = new Pagination(['totalCount' => $count->count(), 'pageSize' => $limit]);
        $ads = $ads->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy($sort->orders)
            ->groupBy('id')
            ->all();
        return ['pages' => $pages, 'ads' => $ads];
    }


    public static function getCategorySort()
    {
        return new Sort([
            'defaultOrder' => ['validity_at' => SORT_DESC, 'id' => SORT_DESC],
            'attributes' => [
                'price' => [
                    'asc' => ['price' => SORT_ASC],
                    'default' => SORT_ASC,
                    'label' => 'Самые дешевые',
                ],
                'big-price' => [
                    'desc' => ['price' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Самые дорогие',
                ],
                'id' => [
                    'desc' => ['id' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Самые новые',
                ],
                'number' => [
                    'desc' => ['id' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Самые новые',
                ],
                'validity_at' => [
                    'desc' => ['validity_at' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Самые новые',
                ],
            ],
        ]);
    }

    public static function getCategorySorta()
    {
        return new Sort([
            'defaultOrder' => ['validity_at' => SORT_DESC],
            'attributes' => [
                'price' => [
                    'asc' => ['price' => SORT_ASC],
                    'default' => SORT_ASC,
                    'label' => 'Самые дешевые',
                ],
                'big-price' => [
                    'desc' => ['price' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Самые дорогие',
                ],
                'id' => [
                    'desc' => ['id' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Самые новые',
                ],
                'number' => [
                    'desc' => ['id' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Самые новые',
                ],
                'validity_at' => [
                    'desc' => ['a.validity_at' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Самые новые',
                ],
            ],
        ]);
    }


    public static function getCategoryAdsImgInf(&$ads)
    {
        // echo '<pre>';
        // print_r($ads);
        // die;
        $ads_ids = array_column($ads, 'id');


        $promo = (new \yii\db\Query())->select('*')->from('jandoo_ads_has_images')->where('validity_at > ' . time() . '')->andWhere(['in', 'ads_id', $ads_ids])->orderBy('validity_at DESC')->groupBy('ads_id, type')->all();
        $images = \rico\yii2images\models\Image::find()->select('itemId,filePath')->where(['in', 'itemId', $ads_ids])->andWhere('modelName = "Ads" AND isMain = 1')->indexBy('itemId')->asArray()->all();

        for ($i = 0; $i<count($ads); $i++) {
            $ad_id = $ads[$i]['id'];
            if(!empty($images[$ad_id])){
                $ads[$i]['filePath'] = $images[$ad_id]['filePath'];
            }
        }

        // echo '<pre>';
        // print_r($ads);
        // die;

        for($i = 0; $i < count($promo); $i++)
            for($y = 0; $y < count($ads); $y++)
                if($ads[$y]['id'] == $promo[$i]['ads_id']){
                    $ads[$y]['type'][] = $promo[$i];
                    if($promo[$i]['type'] == 5)
                        $ads[$y]['top'] = 5;
                }

        // echo '<pre>';
        // print_r($promo);
        // die;


        // $array_top = [];

        // for($i = 0; $i < count($ads); $i++){
        //     if($ads[$i]['top'] == 5)
        //         array_unshift($array_top, $i);
        //     else
        //         $array_top[] = $i;
        // }

        // echo '<pre>';
        // print_r($array_top);die;
        // array_multisort($ads,SORT_DESC, $array_top);

        // echo '<pre>';
        // print_r($ads);die;

        return true;
    }


    public static function getAds($limit = 30, $vip = false)
    {
        
        if(!$vip){
            $promo = (new \yii\db\Query())->select('ads_id')->from('jandoo_ads_has_images')->where('type = 2 AND validity_at > "' . time() . '"')->indexBy('ads_id')->orderBy('id DESC')->all();

            // echo '<pre>';
            // print_r(implode(',', array_keys($promo)));die;
            $ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.validity_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->where('a.id NOT IN('.implode(',',array_keys($promo)).') AND a.active = 1')->orderBy('a.validity_at DESC')->limit($limit)->all();

        }else{
            $ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                ->leftJoin('jandoo_ads_has_images as i', 'a.id = i.ads_id')
                ->where('i.type = 2 AND a.active = 1 AND i.validity_at > ' . time() . '')
                // ->orderBy('i.id DESC')
                // ->groupBy('a.id,  i.ads_id')
                ->limit($limit)
                ->all();

                // echo '<pre>';
                // print_r($ads);
                // die;
        }


        $ads_ids = array_column($ads, 'id');

        // if(!$vip){
        //     $ads_has_img = \frontend\models\AdsHasImage::find()->where(['in', 'ads_id', $ads_ids])->where(['type' => 2])->indexBy('ads_id')->asArray()->all();
        // }
        $images = \rico\yii2images\models\Image::find()->select('itemId,filePath')->where(['in', 'itemId', $ads_ids])->andWhere('modelName = "Ads" AND isMain = 1')->indexBy('itemId')->asArray()->all();


        $promo = (new \yii\db\Query())
                ->select('*')
                ->from('jandoo_ads_has_images')
                ->where('validity_at > ' . time() . '')->andWhere(['in', 'ads_id', $ads_ids])->orderBy('validity_at DESC')->groupBy('ads_id, type')->all();



        for ($i = 0; $i<count($ads); $i++) {
            $ad_id = $ads[$i]['id'];
            if(!empty($ads_has_img[$ad_id])){
               $ads[$i]['type'] = 2;
            }
            if(!empty($images[$ad_id])){
                $ads[$i]['filePath'] = $images[$ad_id]['filePath'];
            }
        }

        for($i = 0; $i < count($promo); $i++)
            for($y = 0; $y < count($ads); $y++)
                if($ads[$y]['id'] == $promo[$i]['ads_id'])
                    $ads[$y]['type'][] = $promo[$i];
                
            
        

        // echo '<pre>';
        // print_r($ads);die;
        // if( $vip ){
        //     shuffle($ads);
        // }


        return $ads;
    }

    public static function getRegs()
    {
        $reg = Yii::$app->cache->get('list_popular_reg');

        if($reg === false){

            $reg_ads = (new \yii\db\Query())
                ->select('COUNT(a.reg_id) as count, u.db_defnamelang as reg_name, a.reg_id')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_vd_region as u', 'a.reg_id = u.id')
                ->where('a.active = 1 AND a.reg_id != ""')->groupBy('a.reg_id')->all();

            $reg = '';

            for($i = 0, $j = 1; $i < count($reg_ads); $i++, $j++){
                if($j == 1)
                    $reg .= '<div class="ads_from_city">'; 

                $reg .= '<p><a rel="nofollow" href="'.Url::to(['category/view', 'cat' => $reg_ads[$i]['reg_id']]).'">Объявления '. $reg_ads[$i]['reg_name'] .'</a><span class="number_ads"> ('. $reg_ads[$i]['count'] .')</span></p>';

                if($j == 9 || $i == count($reg_ads) - 1){ $reg .= '</div>';$j = 0; }
            }

            Yii::$app->cache->set('list_popular_reg', $reg, 600);
        }

        return $reg;
    }


    public static function getPopularCity()
    {   
        $city = Yii::$app->cache->get('list_popular_city');

        if($city === false){
            $city_ads = (new \yii\db\Query())
                ->select('COUNT(a.city_id) as count_city, u.db_defnamelang as city_name, u.id as city_id, u.db_rootid as reg_id')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_vd_city as u', 'a.city_id = u.id')
                ->where('a.active = 1 AND a.city_id != ""')->groupBy('a.city_id')->orderBy('count_city DESC')->limit(9)->all();

            $city = '<p class="title_in_footer">Популярные города</p>';

            for($i = 0; $i < count($city_ads); $i++){

                $city .= '<a rel="nofollow" href="'.Url::to(['category/view', 'cat' => $city_ads[$i]['reg_id'], 'subcat' => $city_ads[$i]['city_id']]).'" class="link-white">'.$city_ads[$i]['city_name'].'</a>';

            }

            Yii::$app->cache->set('list_popular_city', $city, 600);

        }

        return $city;
    }


    public static function getPreview($ad)
    {
        $course = \frontend\models\CourseMoney::getCurrentCourse();

        if(!empty($ad['images'])){

        	$first_img = array_shift($ad['images']);
        	$main_img = '<a data-fancybox="gallery" class="big_img" data-mini-href="' . $first_img . '" href="' . $first_img . '"><img src="' . $first_img . '" alt="#" /></a>';
        	$all_img = '<div id="owl-carousel-ad" class="owl-carousel owl-theme" style="display:block">';
        	foreach ($ad['images'] as $key) {
        		$all_img .= '<div style="display:inline-block" class="item item-img"><a class="mini_img" href="' . $key . '" alt="#"><img width="180px" height="100px" src="' . $key . '" alt="#" /></a></div>';
        	}
        	$all_img .= '</div>';
        	$ad['images'] = $main_img . $all_img;
        }

        if(!empty($ad['sub_field'])){
        	foreach ($ad['sub_field'] as $key => $value) {
        		$sub_field_ .= '<li><b>' . $key . '</b>' . $value . '</li>';
        	}
        	$ad['sub_field'] = $sub_field_;
        }

        $html = '<div class="container product-page">

    <div class="header-title">
        <h1 class="no-margin-top">'. htmlspecialchars_decode($ad['name']) .'</h1>

    <p class="like"><span class="favorite-text">В избранное</span> <a href="#" class="favorite"><i class="fa fa-star-o" aria-hidden="true"></i></a></p>

  </div>
  <span><i class="fa fa-clock-o" aria-hidden="true"></i> ' . Yii::$app->formatter->asDate(time(), 'php:d.m.Y') .'</span><span><i class="fa fa-eye" aria-hidden="true"></i> Просмотров: 0</span><span><i class="fa fa-bookmark" aria-hidden="true"></i> ID: 0</span>

  <div class="product">

    <div class="row">
      <div class="col-md-8">
        <div class="image">
          ' . $ad['images'] . '

        </div>
          <div><a class="btn j-primary m-y-1">Поднять вверх списка</a>
          </div>

        <div class="description-block">
          <ul>
            <li><b>Цена договорная: </b>' . \frontend\models\Ads::getNegotiable()[$ad['negotiable']] . '</li>
            <li><b>Тип доставки: </b>' . \frontend\models\Ads::getTypeDelivery()[$ad['type_delivery']] . '</li>
            <li><b>Торг: </b>' . \frontend\models\Ads::getBargain()[$ad['bargain']] . '</li>
            ' . $ad['sub_field'] . '
          </ul>

        </div>
        <p class=\'description-text\'>' . htmlspecialchars_decode($ad['text']) . '</p>
      </div>
      <div class="col-md-4">
        <div class="p-content">
          <div class="span">

            <div class="wrap_prices">
              <span class="price_name">Цена:</span> 
              <span class="price_value">' . $ad['price'] . 'грн<br>

                <span class="currency_in_ads">' . number_format($ad['price']/$course['usd_sale'], 2, ',', ' ') . '$ <span class="eur_sale">' . number_format($ad['price']/$course['eur_sale'], 2, ',', ' ') . '€</span></span>
              </span>
            </div>

            <div class="fad_city">
              <i class="fa fa-map-marker" aria-hidden="true"></i>
              <div class="text">' . $ad['location'] . '</div>
            </div>


            <div class="fad_map"><iframe style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA4ySfZlbdXq832ilqx-GcIk3tpmfnREHU
              &q=' . $ad['location'] . '" allowfullscreen></iframe></div>

              <p>
                <button class="btn j-primary" data-view="1" data-toggle="collapse" data-target="#contacts">Показать контакты</button>
              </p>
              <p><a class="btn j-primary" href="#">Отзывы об авторе</a></p>
            </div>

            <button type="button" class="btn j-primary sms-call" data-toggle="modal" data-target="#myModal">Написать автору</button>

          </div>
          <div class="p-content">
            <div class="user-inf">
              <p class="user-img"><i class="fa fa-user-o" aria-hidden="true"></i></p>
              <p class=""><noindex><a rel="nofollow" href="' . Url::to(['category/view' , 'cat' => 'user-ads', 'subcat' => Yii::$app->user->identity->id]) .'">
               ' . Yii::$app->user->identity->username . '
              </a></noindex></p>
              <p class="">На <b>JANDOOO</b> c ' . Yii::$app->formatter->asDate(Yii::$app->user->identity->created_at, 'php:M Y') . '</p>
            </div>

            <div class="span">
              <p><noindex><a class="btn j-primary" rel="nofollow" href="' . Url::to(['category/view' , 'cat' => 'user-ads', 'subcat' => Yii::$app->user->identity->id]) .'">Другие обьявления автора</a></noindex></p>
            </div>

          </div>

        </div>
      </div>

    </div>



</div>';

    return $html;
    }
}