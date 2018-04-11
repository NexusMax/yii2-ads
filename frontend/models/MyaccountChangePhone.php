<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class MyaccountChangePhone extends Model
{
    public $phone;


    private $_user;

    public function rules()
    {
        return [
            [['phone'], 'required'],
            ['phone', 'trim'],
            ['phone', 'match', 'pattern' => '/((\+[0-9]{6})|0)[-]?[0-9]{9}/'],
            ['phone', 'string', 'min' => 10, 'max' => 13]
        ];
    }

    public function attributeLabels()
    {
        return [
            'phone' => 'Телефон',
        ];
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Yii::$app->user;

        }

        return $this->_user;
    }

    public function savePhone()
    {
    	if($this->validate()){
    		$user = \common\models\User::find()->where(['id'=>Yii::$app->user->id])->one(); 

            if(preg_match('/((\+[0-9]{6})|0)[-]?[0-9]{9}/', $this->phone)){
                if(preg_match('/(\+[0-9]{6})/', $this->phone))
                    $user->phone = $this->phone;
                else
                    $user->phone = '+38' . $this->phone;
            }
    		$user->save();

    		return true;
    	}
    	return false;
    }

}
