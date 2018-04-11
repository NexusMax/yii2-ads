<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class MyaccountChangePassword extends Model
{
    public $password;
    public $password_2;

    private $_user;

    public function rules()
    {
        return [
            [['password'], 'required'],
            ['password', 'string', 'min' => 6],

            ['password_2', 'compare', 'compareAttribute' => 'password', 'message' => 'Значение паролей должны быть равны'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Новый пароль',
            'password_2' => 'Повторите новый пароль'
        ];
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Yii::$app->user;

        }

        return $this->_user;
    }

    public function savePassword()
    {
    	if($this->validate()){
    		$user = \common\models\User::find()->where(['id'=>Yii::$app->user->id])->one(); 
    		$user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
    		$user->save();

    		return true;
    	}
    	return false;
    }

}
