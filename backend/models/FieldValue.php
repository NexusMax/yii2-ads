<?php

namespace backend\models;

use Yii;

class FieldValue extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%field_value}}';
    }


    public static function getHash($str){

        $str = $str . strtotime(date('H:i:s'));
        $file = md5($str);
        return $file;

    }

    public function upload()
    {
        $img = '/home/sago3/sago.in.ua/temp9/web/uploads/ads/' . $this->getHash($this->image->baseName) . '.' . $this->image->extension;
        $img_tmp = $this->image->tempName;
        
        $this->image = $this->getHash($this->image->baseName) . '.' . $this->image->extension;
        move_uploaded_file($img_tmp, $img);

        // echo '<pre>';
        // print_r($this->image);
        //  echo '<br>';
        // print_r($img_tmp);
        // echo '<br>';
        // echo '</pre>';die;

        $this->save(false);
        return true;
      
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
	    $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
	    // удаляем начальные и конечные '-'
	    $str = trim($str, "-");
	    $old_str = Ads::find()->where(['alias' => $str])->one();
	    if(!empty($old_str))
	    	$str = $str . '-2';
	    return $str;
	}

	public static function getDate($unix)
	{
		// return date('N F Y H:i', $unix);

		return strftime('%e %B %C %H:%M', $unix);
	}


}