<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\OrderP;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for OrderP model.
 */
class OrderController extends Controller
{
    public function beforeAction($action)
    {      
        if ($this->action->id == 'login-social') {
            Yii::$app->controller->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update', 'view', 'delete', 'profile', 'messages', 'settings'],
                'rules' => [
                    [
                        'actions' => ['index', 'profile', 'messages', 'settings'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                       'actions' => ['update', 'view', 'delete'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           return \common\models\User::isUserAdmin(Yii::$app->user->identity->username);
                       }
                   ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {   
        $this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';

        


        return $this->render('index', [
            
        ]);
    }
}
