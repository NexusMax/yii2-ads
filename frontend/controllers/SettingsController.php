<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use \frontend\models\Categories;
use \frontend\models\Ads;
use frontend\models\LoginForm;
use frontend\models\SignupForm;
use backend\models\Blog;
use backend\models\Pages;
use backend\models\Stock;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;

use frontend\models\Search;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;
use yii\helpers\Html;


class SettingsController extends Controller
{
    public $layout = 'settings';  

    public function actionIndex()
    {
        return $this->render('index');
    }
}
