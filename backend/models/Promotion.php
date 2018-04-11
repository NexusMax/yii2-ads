<?php

namespace backend\models;

use Yii;
use yii\base\Model;


class Promotion extends Model
{	
    public $ads_id;
   	public $vip;
    public $top_;
    public $up;
    public $fire;
    public $once_up;

    public $promotion;


    private $select_list = [
        'up' => [
            3 => 15,
            7 => 20,
        ],
        'vip' => [
            7 => 28,
            14 => 56,
        ],
        'top_' => [
            3 => 15,
            7 => 25,
            14 => 40,
            30 => 75,
        ],
        'fire' => [
            7 => 10,
        ],
        'once_up' => [
            1 => 5,
        ],
    ];

    private $package_list = ['start' => 20, 'medium' => 39, 'full' => 79,];
    private $package_promo = [
        'start' => [
            'top_' => 3,
            'fire' => 30,
        ],
        'medium' => [
            'top_' => 7,
            'up' => 3,
            'fire' => 30,
        ],
        'full' => [
            'top_' => 30,
            'up' => 7,
            'vip' => 7,
            'fire' => 30,
        ],
    ];

    public function getPackageList()
    {
        return $this->package_list;
    }

    public function getSelectList()
    {
        return $this->select_list;
    }

    public function setPackagePrice($start, $medium, $full)
    {
        $this->package_list['start'] = $start;
        $this->package_list['medium'] = $medium;
        $this->package_list['full'] = $full;
    }

    public function rules()
    {
        return [
            [['vip', 'top_', 'up', 'ads_id'], 'integer'],
            ['promotion', 'string'],
            [['fire', 'once_up'], 'boolean'],

            // ['created_at', 'validity_at', 'integer', 'required'],
        ];
    }

    public static function getNamePromo()
    {
        return [
            2 => 'Вип',
            3 => 'Топ',
            4 => 'Вверх',
            5 => 'Срочно',
            6 => 'Единоразовое поднятие',
        ];
    }

    public static function getNamePackage()
    {
        return [
            1 => 'Легкое начало',
            2 => 'Золотая середина',
            3 => 'Супер старт',
        ];
    }

    public static function getPricePackage()
    {
        
    }


    public static function promotionImage($id)
    {
        switch ($id) {
            case 1: return 'dobavit_ramky.png'; break;
            case 2: return 'tyrbo_prodvizhenie.png'; break;
            case 3: return 'zafiksirovat_obyavlenie.png'; break;
            case 4: return 'up.png'; break;
            case 5: return 'vudelit_fon.png'; break;
            case 6: return 'vudelit_zagolovok.png'; break;
            
            default:
                break;
        }
    }

   /*
    *   VIP         = 2         [7,14]
    *   TOP         = 3         [3,7,14,30]
    *   UP          = 4         [3,7]
    *   FIRE        = 5         [7]
    *   ONCE UP     = 6         [+30day]
    */
    public function getIdSelect($name)
    {
        if(strcmp($name, 'vip') == 0)
            return 2;
        if(strcmp($name, 'top_') == 0)
            return 3;
        if(strcmp($name, 'up') == 0)
            return 4;
        if(strcmp($name, 'fire') == 0)
            return 5;
        if(strcmp($name, 'once_up') == 0)
            return 6;
    }


    public function save()
    {
        $promo_arr = [];
        if(!empty($this->top_) || !empty($this->vip) || !empty($this->up) || !empty($this->fire) || !empty($this->once_up) || !empty($this->promotion)){
            $time = time();

            if(!empty($this->top_) || !empty($this->vip) || !empty($this->up) || !empty($this->fire) || !empty($this->once_up)){
                if(!empty($this->top_)) /// TOP 3
                    $promo_arr[] = [$this->ads_id, 3, $time, $this->getTime($this->top_), $this->top_, $this->select_list['top_'][$this->top_]];
                if(!empty($this->vip)) /// VIP 2
                    $promo_arr[] = [$this->ads_id, 2, $time, $this->getTime($this->vip), $this->vip, $this->select_list['vip'][$this->vip]];
                if(!empty($this->up))    /// UP 4
                    $promo_arr[] = [$this->ads_id, 4, $time, $this->getTime($this->up), $this->up, $this->select_list['up'][$this->up]];
                if(!empty($this->fire))  /// FIRE 5
                    $promo_arr[] = [$this->ads_id, 5, $time, $this->getTime(7), $this->fire, $this->select_list['fire'][$this->fire]];
                if(!empty($this->once_up)){  /// ONCE_UP 6
                    $ad = Ads::find()->where(['id' => $this->ads_id])->limit(1)->one();
                    $ad->validity_at = $this->getTime(30);
                    $ad->save(false);
                    $promo_arr[] = [$this->ads_id, 6, $time, $this->getTime(30), $this->once_up, $this->select_list['once_up'][$this->once_up]];
                }
                Yii::$app->db->createCommand()->batchInsert('jandoo_ads_has_images', ['ads_id', 'type', 'created_at', 'validity_at', 'type_time', 'price'], $promo_arr)->execute();
            }

            if(!empty($this->promotion)){
                foreach ($this->promotion as $key) {
                    if(!empty($key)){

                        Yii::$app->db->createCommand()->batchInsert('jandoo_ads_promotion', ['ads_id', 'created_at', 'validity_at', 'promotion', 'price'], 
                        [[$this->ads_id, $time, $this->getTime(30), $this->getNumberPackege($key), $this->getTotalPrice()]])->execute();

                        $id_package = (new \yii\db\Query())->select('id')->from('jandoo_ads_promotion')->where(['created_at' => $time])->column()[0];

                        $this->setPromotion($key, $id_package, $time);

                    }
                }
            }
            
            return true;
        }
        return false;
    }


    /*
    *   VIP         = 2         [7,14]
    *   TOP         = 3         [3,7,14,30]
    *   UP          = 4         [3,7]
    *   FIRE        = 5         [7]
    *   ONCE UP     = 6         [+30day]
    */
    public function getTime($number)
    {
        // switch ($number) {
        //     case 3: return strtotime('+3 day'); break;
        //     case 7: return strtotime('+7 day'); break;
        //     case 14: return strtotime('+14 day'); break;
        //     case 30: return strtotime('+30 day'); break;
            
        //     default: return time(); break;
        // }
        return strtotime('+'.$number.' day');
    }

    public function getNumberPackege($package)
    {
        if(strcmp($package, 'start') == 0)
            return 1;
        if(strcmp($package, 'medium') == 0)
            return 2;
        if(strcmp($package, 'full') == 0)
            return 3;
    }
    public function getTotalPrice()
    {
        return $this->select_list['top_'][$this->top_]
            + $this->select_list['up'][$this->up] 
            + $this->select_list['vip'][$this->vip] 
            + $this->select_list['fire'][$this->fire]
            + $this->select_list['once_up'][$this->once_up]
            + $this->package_list[$this->promotion[0]]
            + $this->package_list[$this->promotion[1]]
            + $this->package_list[$this->promotion[2]];
    }

    public function setPromotion($package, $id_package, $time)
    {
        $promo_arr = [];
        foreach ($this->package_promo[$package] as $key => $value) {

            // $this_price = $this->select_list[$key][$value];
            $this_price = 0;
            if(strcmp($key, 'top_') == 0){
                if(empty($this->top_))
                    $promo_arr[] = [$this->ads_id, $this->getIdSelect($key), $time, $this->getTime($value), $value, $this_price, $id_package];
                else{
                    $promo_arr[] = [$this->ads_id, $this->getIdSelect($key), $time, $this->getTime($this->top_ + $value), $this->top_ + $value, $this_price, $id_package];
                }
            }

            if(strcmp($key, 'vip') == 0){
                if(empty($this->vip))
                    $promo_arr[] = [$this->ads_id, $this->getIdSelect($key), $time, $this->getTime($value), $value, $this_price, $id_package];
                else{
                    $promo_arr[] = [$this->ads_id, $this->getIdSelect($key), $time, $this->getTime($this->vip + $value), $this->vip + $value, $this_price, $id_package];
                }
            }

            if(strcmp($key, 'up') == 0){
                if(empty($this->up) && empty($this->once_up))
                    $promo_arr[] = [$this->ads_id, $this->getIdSelect($key), $time, $this->getTime($value), $value, $this_price, $id_package];
                elseif(empty($this->up) && !empty($this->once_up)){
                    $promo_arr[] = [$this->ads_id, $this->getIdSelect($key), $time, $this->getTime($this->once_up + $value), $this->once_up + $value, $this_price, $id_package];
                }else{
                    $promo_arr[] = [$this->ads_id, $this->getIdSelect($key), $time, $this->getTime($this->up + $value), $this->up + $value, $this_price, $id_package];
                }
            }

            if(strcmp($key, 'fire') == 0){
                if(empty($this->fire))
                    $promo_arr[] = [$this->ads_id, $this->getIdSelect($key), $time, $this->getTime($value), $value, $this_price, $id_package];
                else{
                    $promo_arr[] = [$this->ads_id, $this->getIdSelect($key), $time, $this->getTime($this->fire + $value), $this->fire + $value, $this_price, $id_package];
                }
            }
        }

        Yii::$app->db->createCommand()->batchInsert('jandoo_ads_has_images', ['ads_id', 'type', 'created_at', 'validity_at', 'type_time', 'price', 'package_id'], $promo_arr)->execute();
    }

}