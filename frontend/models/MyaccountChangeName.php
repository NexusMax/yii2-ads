<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class MyaccountChangeName extends Model
{
    public $name;
    public $lastname;

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'lastname'], 'string', 'max' => 25],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'lastname' => 'Ваша фамилия'
        ];
    }

    public function save()
    {
    	if($this->validate()){
    		$user = \common\models\User::find()->where(['id' => Yii::$app->user->id])->one();

            // echo '<pre>'; 
            // print_r(Yii::$app->user->id);die;
            $user->username = $this->name;
            $user->lastname = $this->lastname;
    		$user->save();

    		return true;
    	}
    	return false;
    }

}
