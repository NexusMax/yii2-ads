<?php

namespace backend\console;

use Yii;
use \yii\base\BaseObject;
use backend\models\User;

class SendEmail extends BaseObject implements \yii\queue\JobInterface
{

	public $users;
	public $email;

    
    public function execute($queue)
    {
        
    	for($i = 0; $i < 100; $i++){
    		
    		Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($users[$i])
                ->setSubject($this->email->subject)
                ->setTextBody($this->email->message)
                ->send();
    	}

    }
}

