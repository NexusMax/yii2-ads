<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;
use backend\models\Stock;
/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $phone;
    public $balance;

    public $text;

    public function rules()
    {
        return [

            ['username','required'],

            // ['text', 'required'],
            // ['text', 'trim'],
            // ['text', 'match', 'pattern' => '/(((\+[0-9]{6})|0)[-]?[0-9]{9})|(.+@.+\..+)/'],
            [['balance'], 'integer'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Такой Email адрес уже используется.'],
            
            ['phone', 'trim'],
            ['phone', 'match', 'pattern' => '/((\+[0-9]{6})|0)[-]?[0-9]{9}/'],
            ['phone', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Такой телефон уже используется.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function beforeValidate()
    {
        if(preg_match('/((\+[0-9]{6})|0)[-]?[0-9]{9}/', $this->text)){
            $this->phone = $this->text;
        }elseif(preg_match('/.+@.+\..+/', $this->text)){
            $this->email = $this->text;
        }
        if (parent::beforeValidate()) {
           return true;
        }
        return false;
    }


    public function afterValidate()
    {
        if(!empty($this->getErrors()['email']))
            $this->addError('text', $this->getErrors()['email'][0]);
        if(!empty($this->getErrors()['phone']))
            $this->addError('text', $this->getErrors()['phone'][0]);

        return true;
    } 


    public function attributeLabels()
    {
        return [
            'text' => 'Email или Телефон',
            'password' => 'Пароль'
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();

        // if(preg_match('/((\+[0-9]{6})|0)[-]?[0-9]{9}/', $this->text)){

        //     if(preg_match('/(\+[0-9]{6})/', $this->text))
        //         $this->phone = $this->text;
        //     else
        //         $this->phone = '+38' . $this->text;

        //     $user->phone = $this->phone;
        // }elseif(preg_match('/.+@.+\..+/', $this->text)){
        //     $user->email = $this->text;
        //     $this->email = $this->text;
        // }
        $stock = Stock::find()->where(['>' ,'validity_at', time()])->orderBy('id DESC')->one();
        $balance = 0;
        if(!empty($stock)){
            $stock->count = $stock->count +1;
            $stock->save();
            $balance = $stock['sum']; 
        }
        
        $user->username = $this->username;
        $user->balance = $balance;
        $user->phone = $this->phone;
        $user->email = $this->email;
        $user->lastvisit = time();
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
