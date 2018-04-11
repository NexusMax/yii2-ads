<?php
namespace backend\models;

use yii\base\Model;
use backend\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $lastname;
    public $phone;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['lastname', 'trim'],
            ['lastname', 'required'],
            ['lastname', 'string', 'min' => 2, 'max' => 255],

            ['phone', 'required'],
            ['phone', 'unique', 'targetClass' => '\backend\models\User', 'message' => 'This phone address has already been taken.'],
            // ['phone', 'match', 'pattern' => '/((\+[0-9]{6})|0)[-]?[0-9]{9}/'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate(false)) {
            return null;
        }
        
        $user = new User();
        $user->phone = $this->phone;
        $user->username = $this->username;
        $user->lastname = $this->lastname;
        
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }
}
