<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class MyaccountDeleteAccount extends Model
{
    public $hidden;

    private $_user;

    public function rules()
    {
        return [
            ['hidden', 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [

        ];
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Yii::$app->user;

        }

        return $this->_user;
    }

    public function deleteAccount()
    {
    	if($this->validate(false)){

    		$user = \common\models\User::find()->where(['id'=>Yii::$app->user->id])->one(); 
    		$user->delete();

    		return true;
    	}
    	return false;
    }

}
