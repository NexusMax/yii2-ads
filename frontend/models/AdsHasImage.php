<?php

namespace frontend\models;

use Yii;

class AdsHasImage extends \yii\db\ActiveRecord
{	
   	public $vip;
    public $top;
    public $up;
    public $fire;
    public $once_up;

    public function rules()
    {
        return [
            [['vip', 'top', 'up'], 'integer'],
            [['fire', 'once_up'], 'boolean'],
            ['created_at', 'validity_at', 'integer', 'required'],
        ];
    }
    public static function tableName()
    {
        return '{{%ads_has_images}}';
    }

    public function getAds()
    {
        return $this->hasOne(\backend\models\Ads::className(), ['id' => 'ads_id']);
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

    public static function getTimePromo()
    {
        // switch ($number) {
        //     case 3: return strtotime('+3 day'); break;
        //     case 7: return strtotime('+7 day'); break;
        //     case 14: return strtotime('+14 day'); break;
        //     case 30: return strtotime('+30 day'); break;
            
        //     default: return time(); break;
        // }
        return [
            3 => strtotime('+3 day'),
            7 => strtotime('+7 day'),
            14 => strtotime('+14 day'),
            30 => strtotime('+30 day'),
        ];
    }
}