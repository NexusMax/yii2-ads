<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%categories}}".
 *

 */
class User extends \common\models\User
{

	public $password;

    public function rules()
    {
        return [
            ['balance', 'integer'],
            [['username', 'email', 'password'], 'required'],
            [['email'], 'email'],
            [['phone'], 'match', 'pattern' => '/((\+[0-9]{6})|0)[-]?[0-9]{9}/']
        ];
    }

    public function getAds()
    {
        return $this->hasMany(\backend\models\Ads::className(), ['user_id' => 'id'])->with('category');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя',
            'lastname' => 'Фамилия',
            'phone' => 'Номер телефона',
            'password' => 'Пароль'
        ];
    }

    public function getStatus($ban)
    {
    	return $ban == 0 ? 'Активно' : 'Заблокировано';
    }

    public static function setStatus()
    {
    	$user_id = Yii::$app->request->post('user_id');
        $ban = Yii::$app->request->post('status');

        $user = User::find()->where(['id' => $user_id])->one();
        $user->ban = $ban;
        $user->save(false);

        if(intval($ban) === 1){
            $message = 'Ваш аккаунт был заблокирован. Свяжитесь с тех. поддержкой сайта.';
        }else{
            $message = 'Ваш аккаунт успешно разблокирован. Вы можете зайти в личный кабинет.';
        }

        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo( $data['email'])
            ->setSubject('Jandooo.com изменения в аккаунте')
            ->setTextBody($message)
            ->send();
    }

    public static function setBalance($id, $balance)
    {


        $user = User::find()->where(['id' => $id])->one();
        $user->balance = $balance;
        $user->save(false);
    }


    public static function getDayUserCount()
    {
        return User::find()->select('count(*) as k')->where(['<=', 'created_at', time()])->andWhere(['>=', 'created_at', strtotime('-1 day')])->asArray()->orderBy('id DESC')->one()['k'];
    }

    public static function getLastUser($count = 5)
    {
        return User::find()->orderBy('id DESC')->asArray()->orderBy('id DESC')->limit($count)->all();
    }

}