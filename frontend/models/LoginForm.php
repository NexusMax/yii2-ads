<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends \common\models\LoginForm
{
    public $email;
    public $text;
    public $phone;

    private $_user;

    public function rules()
    {
        return [
            [['password', 'text'], 'required'],
            ['email', 'email'],
            ['text', 'match', 'pattern' => '/(((\+[0-9]{6})|0)[-]?[0-9]{9})|(.+@.+\..+)/'],
            ['phone', 'match', 'pattern' => '/((\+[0-9]{6})|0)[-]?[0-9]{9}/'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
            'text' => 'Email или Телефон'
        ];
    }

    public function login()
    {
        if(preg_match('/((\+[0-9]{6})|0)[-]?[0-9]{9}/', $this->text)){
            $this->phone = $this->text;
        }elseif(preg_match('/.+@.+\..+/', $this->text)){
            $this->email = $this->text;
        }
 
        if ($this->validate()) {
            
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {

            return false;
        }
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный Email/Телефон или пароль.');
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {

            if(preg_match('/((\+[0-9]{6})|0)[-]?[0-9]{9}/', $this->text)){
                if(preg_match('/(\+[0-9]{6})/', $this->text)){
                    $this->_user = \common\models\User::findByPhone($this->text);
                }
                else
                    $this->_user = \common\models\User::findByPhone('+38' . $this->text);
            }elseif(preg_match('/.+@.+\..+/', $this->text)){
                $this->_user = \common\models\User::findByEmail($this->text);
            }

        }

        return $this->_user;
    }


}
