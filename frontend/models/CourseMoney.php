<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\base\Model;


class CourseMoney extends Model
{

    static $url = "https://api.privatbank.ua/p24api/pubinfo?exchange&coursid=5";

    public static function getCurrentCourse()
    {

        $course = Yii::$app->cache->get('course_cache');


        // Yii::$app->cache->delete('course_cache');

        if($course === false){
            $course = self::getCourse();
            Yii::$app->cache->set('course_cache', $course, 600*60*6);
        }


        return $course;
    }


    public static function getCourse()
    {

        if (self::curlInst()){
            
            $curl = curl_init(self::$url);

            if ( $curl ){

                curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
                $page = curl_exec($curl);
                curl_close($curl);
                unset($curl);



                $xml = new \SimpleXMLElement($page);

                // print $xml->row;
                $eur_sale = floatval($xml->row[1]->exchangerate['sale'][0]); if (strlen($m1) == 2){$m1=$m1.'.00';} else if (strlen($m1)==4){$m1=$m1.'0';}
                $eur_buy = floatval($xml->row[1]->exchangerate['buy'][0]); if (strlen($m2) == 2){$m2=$m2.'.00';} else if (strlen($m2)==4){$m2=$m2.'0';}
                $usd_sale = floatval($xml->row[0]->exchangerate['sale'][0]); if (strlen($m3) == 2){$m3=$m3.'.00';} else if (strlen($m3)==4){$m3=$m3.'0';}
                $usd_buy = floatval($xml->row[0]->exchangerate['buy'][0]); if (strlen($m4) == 2){$m4=$m4.'.00';} else if (strlen($m4)==4){$m4=$m4.'0';}
            }
        }
        return ['usd_sale' => $usd_sale, 'usd_buy' => $usd_buy, 'eur_sale' => $eur_sale, 'eur_buy' => $eur_buy];

    }


    public static function curlInst()
    {
        return in_array ('curl', get_loaded_extensions()) ? true : false;
    }
}
