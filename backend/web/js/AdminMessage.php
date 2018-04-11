<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%categories}}".
 *

 */
class AdminMessage extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */


    public static function tableName()
    {
        return '{{%admin_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'unread', 'created_at'], 'integer'],
            ['email', 'email'],
            ['text'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'text' => 'Сообщение'
        ];
    }


    public static function getHash($str){

        $str = $str . strtotime(date('H:i:s'));
        $file = md5($str);
        return $file;

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
	    $old_str = Blog::find()->where(['alias' => $str])->one();
	    if(!empty($old_str))
	    	$str = $str . '_' . mb_substr(Yii::$app->security->generateRandomString(), 0, 6);;
	    return $str;
	}

	public static function getDate($unix)
	{
		// return date('N F Y H:i', $unix);

		return strftime('%e %B %C %H:%M', $unix);
	}

	public function upload()
    {
        $img = '/home/sago3/sago.in.ua/temp9/web/uploads/blog/' . $this->getHash($this->image->baseName) . '.' . $this->image->extension;
        $img_tmp = $this->image->tempName;
        
        $this->image = $this->getHash($this->image->baseName) . '.' . $this->image->extension;

        if(!file_exists('/home/sago3/sago.in.ua/temp9/web/uploads/blog/'))
        	mkdir('/home/sago3/sago.in.ua/temp9/web/uploads/blog/');

        move_uploaded_file($img_tmp, $img);

        $this->save(false);
        return true;
      
    }


}