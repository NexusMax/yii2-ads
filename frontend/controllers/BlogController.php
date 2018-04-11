<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use \frontend\models\Categories;
use \frontend\models\Ads;
use \backend\models\Blog;
use \frontend\models\Promotion;
use \frontend\models\UserStatus;
use \frontend\models\Message;
use yii\filters\AccessControl;
use frontend\models\AdsFavorites;
use yii\helpers\Url;

class BlogController extends Controller
{ 


    public function beforeAction($action)
    {

        $this->view->registerCssFile('/css/style.css', ['position' => \yii\web\View::POS_HEAD]);
        // $this->view->registerJsFile('/js/common.js', ['position' => \yii\web\View::POS_END]);
        return parent::beforeAction($action);

    }


	public function actionIndex()
    {
        $blogs = Blog::find()->where('active = 1')->orderBy('id DESC')->asArray()->limit(6)->all();

        
        return $this->render('index', [
            'blogs' => $blogs,
        ]);
    }

    public function actionView($alias)
    {
        $this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';

        $blog = Blog::find()->where('active = 1')->andWhere(['alias' => $alias])->asArray()->one();
        
        return $this->render('view', [
            'blog' => $blog,
        ]);
    }

}
