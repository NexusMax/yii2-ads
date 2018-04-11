<?php
namespace backend\controllers;

use Yii;
use backend\controllers\MainController;
use backend\models\AdsHasImage;
use backend\models\Promotion;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use common\models\User;
use yii\data\Sort;
/**
 * Main controller
 */
class PromotionController extends MainController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'index', 'create', 'update', 'view', 'delete'],
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                       'actions' => ['index', 'update', 'view', 'create', 'delete'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           return User::isUserAdmin(Yii::$app->user->identity->username);
                       }
                   ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->title = 'Бухгалтерия';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        // $searchModel = new CategoriesSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $sort = new Sort([
            'defaultOrder' => ['sort'=>SORT_ASC],
            'attributes' => [
                'name' => [
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Название',
                ],
                'sort' => [
                    'asc' => ['sort' => SORT_ASC],
                    'desc' => ['sort' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => '№',
                ],
                'active' =>[
                    'asc' => ['active' => SORT_ASC],
                    'desc' => ['active' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Активность',
                ],
                'create' =>[
                    'asc' => ['created_at' => SORT_ASC],
                    'desc' => ['created_at' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Дата начала',
                ],
                'validity' =>[
                    'asc' => ['validity_at' => SORT_ASC],
                    'desc' => ['validity_at' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Дата конца',
                ],
            ],
        ]);

        if (Yii::$app->request->isAjax) {
            $caterory = AdsHasImage::find()->orderBy('id DESC')->with('promotion')->one();
            $caterory->active = Yii::$app->request->post('checkbox_active');
            $caterory->save();
        }

        $promotion = AdsHasImage::find()->orderBy('id DESC')->with('ads')->with('package');

        $id = Yii::$app->request->get('id');
        
        if($id){
            $promotion->where(['id' => $id]);
        }
        $promotion = $promotion->asArray()->all();
        $promotionName = Promotion::getNamePromo();
        $packageName = Promotion::getNamePackage();

        // echo '<pre>';
        // print_r($promotion);die;

        return $this->render('index', compact('promotion', 'sort', 'promotionName', 'packageName'));
    }


    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = AdsHasImage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

 }
