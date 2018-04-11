<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use \frontend\models\Categories;
use frontend\models\LoginForm;
use frontend\models\SignupForm;
use frontend\models\Magazine;

use frontend\models\MagazineAds;
use frontend\models\MagazinePlan;
use frontend\models\MagazinePeriod;
use frontend\models\MagazinePrice;
use frontend\models\MagazineCategory;
use frontend\models\MagazineEavValue;

use frontend\models\MagazineSearch;
use frontend\models\MagazineAdsSearch;

use frontend\models\Message;
use \common\models\User;

use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use yii\helpers\Url;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;
use yii\data\Sort;

use yii\helpers\ArrayHelper;



class MagazineController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

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
                'only' => ['index', 'create', 'finish', 'delete', 'profile', 'messages', 'settings'],
                'rules' => [
                    [
                        'actions' => ['index', 'profile', 'messages', 'settings', 'create', 'finish'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                       'actions' => ['update', 'delete'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           return Magazine::isUserAuthorMagazine();
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
        	'model' => $model,
        ]);
    }


    public function actionCreate()
    {   
        $this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';

        $model = new Magazine();
        $model->scenario = Magazine::SCENARIO_CREATE;
        $model->load(Yii::$app->request->post());

        $plans = MagazinePlan::find()->where('active = 1');
        !empty(Yii::$app->request->post('Magazine')['tarif_plan']) ? $plans = $plans->with('firstPrice') : $plans = $plans->with('price');
        $plans = $plans->asArray()->all();

        $periods = MagazinePeriod::find()->select('id, name')->asArray()->all();
        $categories = MagazineCategory::find()->select('id, name')->where('active = 1')->asArray()->all();

        return $this->render('magazine', [
            'model' => $model,
            'plans' => $plans,
            'periods' => $periods,
            'categories' => $categories,
        ]);
    }

    public function actionFinish()
    {
        $this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';

        $model = new Magazine();
        $model->scenario = Magazine::SCENARIO_BACKEND;
        $model->load(Yii::$app->request->post());


        return $this->render('magazine2', [
            'model' => $model,
        ]);
    }

    public function actionSave()
    {
        $model = new Magazine();

        if($model->load(Yii::$app->request->post())){
            if(empty($model->category_id)){
                return $this->redirect(['magazine/create']);
            }

            $model->getTotalPrice();

            $transaction = Yii::$app->db->beginTransaction();
            try{
                if($model->save()){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Магазин создан');
                    return $this->redirect(['myaccount/index']);
                }
            }
            catch (Exception $e) {
                $transaction->rollback();
                // Yii::log("Error occurred while saving artist or its 'songs'. Rolling back... . Failure reason as reported in exception: " . $e->getMessage(), CLogger::LEVEL_ERROR, __METHOD__);
            }
        }

        return $this->render('magazine2', [
            'model' => $model,
        ]);

    }

    public function actionAjaxFinish()
    {
        $model = new Magazine();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    public function actionAjax()
    {
        $model = new Magazine();
        $model->scenario = Magazine::SCENARIO_CREATE;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    public function actionAjaxPlan()
    {
        $id = Yii::$app->request->post('id');
        $plans = MagazinePrice::find()->where(['period_id' => $id])->asArray()->all();
        echo json_encode($plans);
    }

    public function actionShops()
    {
        $this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';

        $searchModel = new MagazineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $reg = Yii::$app->db->createCommand('Select a.id, a.db_rootid, a.db_defnamelang as name from jandoo_vd_region as a LEFT JOIN jandoo_magazine as b ON a.id = b.reg_id WHERE b.active = 1')->queryAll();

        $city = Yii::$app->db->createCommand('Select distinct a.id, a.db_rootid, a.db_defnamelang as name , b.id as magazine_id
            from jandoo_vd_city as a 
            LEFT JOIN jandoo_magazine as b ON a.id = b.city_id
            WHERE b.active = 1
            group by a.id')->queryAll();


        $shops = $dataProvider->getModels();
        $magazineCategories = Yii::$app->db->createCommand('Select a.id, a.name, a.alias, count(`b`.id) as count 
            FROM `jandoo_magazine_categories` as `a`
            LEFT JOIN `jandoo_magazine` as `b` ON `a`.id = `b`.category_id
            GROUP BY a.id')->queryAll();

        if(!empty($shops)){
            $countAds = Yii::$app->db->createCommand('Select COUNT(*) as count, magazine_id from jandoo_magazine_ads WHERE magazine_id IN('. implode(' ,', array_keys($shops)) .') GROUP BY magazine_id')->queryAll();
            foreach ($countAds as $key) {
                $shops[$key['magazine_id']]['count_ads'] = $key['count'];
            }
        }

        return $this->render('shops', [
            'shops' => $shops,
            'magazineCategories' => $magazineCategories,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'city' => $city,
            'reg' => $reg,
        ]);
    }

    public function actionView($alias)
    {
        $sort = new Sort([
            'defaultOrder' => ['updated_by'=>SORT_DESC],
            'attributes' => [
                'created_at' => [
                    'asc' => ['created_at' => SORT_ASC],
                    'desc' => ['created_at' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Дата',
                ],
                'price' =>[
                    'asc' => ['price' => SORT_ASC],
                    'desc' => ['price' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Цена',
                ],
                'id' =>[
                    'asc' => ['id' => SORT_ASC],
                    'desc' => ['id' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Номер',
                ],
                'updated_by' => [
                    'asc' => ['updated_by' => SORT_ASC],
                    'desc' => ['updated_by' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Дата изменения',
                ],
            ],
        ]);

        $model = Magazine::find()->where('active = 1')->andWhere(['>' , 'validity_at', time()])->andWhere(['alias' => $alias])->one();


        if (empty($model))
            throw new \yii\web\HttpException(404, "Такого магазина нет!");


        if(!empty(Yii::$app->request->get('category_id'))){
            $category_ = Yii::$app->request->get('category_id');
            $id = $category_;
        }else{
            $category_ = 0;
            $id = null;
        }


        $categories = \frontend\models\MagazineHasCategories::find()->indexBy('id')->orderBy('id')->where(['magazine_id' => $model['id']])->asArray()->all();


        $searchModel = new MagazineAdsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model['id'], $sort, $categories, $id);

        $ads = $dataProvider->getModels();


        $main_categories = Yii::$app->db->createCommand('Select id, name FROM jandoo_magazine_has_categories where magazine_id = ' . $model['id'] . ' AND active = 1 AND parent_id = 0')->queryAll();

        // $searchModel->getCategoryIds($categories, $id);

        // echo '<pre>';
        // var_dump($category_);
        // die;
        if(!empty($categories)){
	        $magazineCategories = Yii::$app->db->createCommand('Select a.id, a.name, count(`b`.id) as count
	            FROM jandoo_magazine_has_categories as a 
	            LEFT JOIN jandoo_magazine_ads as b ON a.id = b.category_id
	            where a.magazine_id = ' . $model['id'] . ' AND a.active = 1 AND parent_id = ' . $category_ . '
	            GROUP BY a.id')->queryAll();
	        if(empty($magazineCategories)){
	            $magazineCategories = Yii::$app->db->createCommand('Select a.id, a.name, count(`b`.id) as count
	            FROM jandoo_magazine_has_categories as a 
	            LEFT JOIN jandoo_magazine_ads as b ON a.id = b.category_id
	            where a.magazine_id = ' . $model['id'] . ' AND a.active = 1 AND a.parent_id = ' . $categories[$category_]['parent_id']. '
	            GROUP BY a.id')->queryAll();
	        }

	        if( $category_ === 0){
	        	$ids = implode(', ', array_column($magazineCategories, 'id'));

	        	$res = $searchModel->getCategoryIds($categories, $id);

	        	$magazineCategories_ = Yii::$app->db->createCommand('SELECT b.name, b.parent_id, COUNT(a.id)  as count
	        		FROM jandoo_magazine_ads as a 
	        		LEFT JOIN jandoo_magazine_has_categories as b ON a.category_id = b.id
					where b.parent_id IN (' . $ids . ') group by b.parent_id')->queryAll();

	        	$magazineCategories_ = ArrayHelper::index($magazineCategories_, 'parent_id');

	        	foreach ($magazineCategories as $key => $value) {
	        		$magazineCategories[$key]['count'] = $magazineCategories_[$value['id']]['count'] + $magazineCategories[$key]['count'];
	        	}
	        }
        }

        
        $this->view->title = 'Jandooo - Магазин: ' . $shops['name'];
        
        $city = Yii::$app->db->createCommand('Select location from jandoo_magazine_ads WHERE active = 1 AND location != "" AND magazine_id = ' . $model['id'])->queryAll();

        return $this->render('view', [
            'model' => $model,
            'ads' => $ads,
            'magazineCategories' => $magazineCategories,
            'main_categories' => $main_categories,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'city' => $city,
            'sort' => $sort,
        ]);
    }

    public function actionAbout($alias)
    {
        $model = Magazine::find()->where('active = 1')->andWhere(['>' , 'validity_at', time()])->andWhere(['alias' => $alias])->one();

        $searchModel = new MagazineAdsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model['id']);

        $ads = $dataProvider->getModels();
        $magazineCategories = Yii::$app->db->createCommand('Select a.id, a.name, count(`b`.id) as count
            FROM jandoo_magazine_has_categories as a 
            LEFT JOIN jandoo_magazine_ads as b ON a.id = b.category_id
            where a.magazine_id = ' . $model['id'] . ' AND a.active = 1 
            GROUP BY a.id')->queryAll();

        $this->view->title = 'Jandooo - Магазин: ' . $shops['name'];


        $city = Yii::$app->db->createCommand('Select location from jandoo_magazine_ads WHERE active = 1 AND location != "" AND magazine_id = ' . $model['id'])->queryAll();

        return $this->render('about', [
            'model' => $model,
            'ads' => $ads,
            'magazineCategories' => $magazineCategories,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'city' => $city,
        ]);
    }

    public function actionContact($alias)
    {
        $model = Magazine::find()->where('active = 1')->andWhere(['>' , 'validity_at', time()])->andWhere(['alias' => $alias])->one();

        $searchModel = new MagazineAdsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model['id']);

        $ads = $dataProvider->getModels();
        $magazineCategories = Yii::$app->db->createCommand('Select a.id, a.name, count(`b`.id) as count
            FROM jandoo_magazine_has_categories as a 
            LEFT JOIN jandoo_magazine_ads as b ON a.id = b.category_id
            where a.magazine_id = ' . $model['id'] . ' AND a.active = 1 
            GROUP BY a.id')->queryAll();

        $this->view->title = 'Jandooo - Магазин: ' . $shops['name'];



        $reg_name = Yii::$app->db->createCommand('Select * from jandoo_vd_region WHERE id = ' . $model['reg_id'])->queryOne();

        $city_name = Yii::$app->db->createCommand('Select * from jandoo_vd_city WHERE id = ' . $model['city_id'])->queryOne();


        return $this->render('contact', [
            'model' => $model,
            'ads' => $ads,
            'magazineCategories' => $magazineCategories,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'city' => $city,
            'reg_name' => $reg_name[db_defnamelang],
            'city_name' => $city_name[db_defnamelang],
        ]);
    }

    public function actionProduct($alias)
    {
        $ad = MagazineAds::find()->where('active = 1')->andWhere(['alias' => $alias])->with('vals')->one();
        $ad->views = $ad->views + 1;
        $ad->save(false);
        $magazine = Magazine::find()->where('active = 1')->andWhere(['id' => $ad['magazine_id']])->with('paymentsss')->with('deliveriesss')->one();


        $vals = MagazineEavValue::find()->where(['product_id' => $ad->id])->with('field')->asArray()->all();

        $params = [];

        foreach($vals as $key){

            $params[$key['field']['name']][] = $key['value'];
        }

        // echo '<pre>';
        // print_r( $ad);
        // die;

        // $user = User::find()->where(['id' => $ad['user_id']]);
        $comments = new Message;

        if ($comments->load(Yii::$app->request->post())) {
            $comments->from = Yii::$app->user->identity->id;

            $comments->ads_id = $ad['id'];
            $comments->first_message_id = Yii::$app->user->identity->id;
            $comments->author_ads_id = $ad['user_id'];
            $comments->for = $comments->author_ads_id;
            $comments->id_messages = $comments->author_ads_id . Yii::$app->user->identity->id . $comments->ads_id;
            $comments->magazine_id = $ad['magazine_id'];

            // echo '<pre>';
            // print_r($comments);
            // die;
            
            if($comments->save()){
                Yii::$app->session->setFlash('success', 'Сообщение отправлено.');
                return $this->refresh();
            }
        }


        $main_img = $ad->getImage();
        $images = $ad->getImages();

        if($main_img['urlAlias'] == 'placeHolder'){
            $images = '';
        }

        $user_ads = MagazineAds::find()->where('active = 1')->andWhere(['magazine_id' => $ad['magazine_id']])->with('category')->with('mainImage')->andWhere(['not in', 'id', $ad['id']])->limit(3)->all();
    

        return $this->render('product', [
            'model' => $model,
            'magazine' => $magazine,
            'user' => $user,
            'ad' => $ad,
            'magazineCategories' => $magazineCategories,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'city' => $city,
            'comments' => $comments,
            'main_img' => $main_img,
            'images' => $images,
            'user_ads' => $user_ads,
            'params' => $params,
        ]);

    }

}
