<?php

namespace frontend\controllers;

use Yii;

use frontend\models\Magazine;
use frontend\models\MagazineAds;
use frontend\models\MagazinePlan;
use frontend\models\MagazinePrice;
use frontend\models\MagazinePeriod;
use frontend\models\MagazineCategory;
use frontend\models\MagazineHasCategory;
use frontend\models\MagazinePayment;
use frontend\models\MagazineDelivery;
use frontend\models\MagazineHasDelivery;
use frontend\models\MagazineHasCategories;
use frontend\models\MagazineHasPayment;

use frontend\models\Message;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MagazineController implements the CRUD actions for Magazine model.
 */
class MyaccMagazineController extends Controller
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
        if(!Yii::$app->user->isGuest){
            $my_id = Yii::$app->user->identity->id;


            $this->view->registerCssFile('/css/nprogress.css');
            
            $messages = Message::find()->select('count(unread) as unread, author_ads_id, ads_id')->where(['for' => $my_id])->andWhere('unread = 0')->indexBy('ads_id')->groupBy('ads_id')->asArray()->all();
            $all_count = 0;$count_my = 0;$count_send = 0;
            
            foreach ($messages as $key) {
                $all_count += $key['unread'];
                if ($key['author_ads_id'] == $my_id)
                    $count_my += $key['unread'];
                else
                    $count_send += $key['unread'];
            }

            Yii::$app->params['messages'] = $messages;
            Yii::$app->params['messages']['count'] = $all_count;
            Yii::$app->params['messages']['count_my'] = $count_my;
            Yii::$app->params['messages']['count_send'] = $count_send;

            if(
                $this->action->id == 'index' || 
                $this->action->id == 'view' ||
                $this->action->id == 'payment-update' ||
                $this->action->id == 'payment-delete' ||
                $this->action->id == 'payment-view' ||
                $this->action->id == 'payment'	||
                $this->action->id == 'update'
            ){
                Yii::$app->params['myaccount']['title'] = 'Магазины';
                Yii::$app->params['myaccount']['sub_title'] = 'Здесь вы найдёте ваши активные магазины';
            }

            if($this->action->id == 'index' || $this->action->id == 'archive'){
                Yii::$app->params['myaccount']['active_ads'] = Yii::$app->db->createCommand('SELECT COUNT(id) as active_count FROM jandoo_ads WHERE active = 1 AND user_id = :user_id')->bindValue(':user_id', Yii::$app->user->identity->id)->queryOne()['active_count'];
                Yii::$app->params['myaccount']['disactive_ads'] = Yii::$app->db->createCommand('SELECT COUNT(id) as disactive_count FROM jandoo_ads WHERE active = 0 AND user_id = :user_id')->bindValue(':user_id', Yii::$app->user->identity->id)->queryOne()['disactive_count'];
            }

            $ads = (new \yii\db\Query())
                        ->select('a.id, a.name, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, dd.id as parent_parent_category_id, dd.alias as parent_parent_category_alias, dd.name as parent_parent_category_name, count(d.id) as count')
                        ->from('jandoo_ads as a')
                        ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                        ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                        ->leftJoin('jandoo_categories as dd', 'dd.id = d.parent_id')
                        ->where('a.user_id =  ' . Yii::$app->user->identity->id )->groupBy('d.name')->all();

            
            foreach ($ads as $key) {
                if(empty($key['parent_parent_category_alias']))
                    Yii::$app->params['myaccount']['cat_limit'][$key['parent_category_alias']]['count'] = $key['count'];
                else
                    Yii::$app->params['myaccount']['cat_limit'][$key['parent_parent_category_alias']]['count'] += $key['count'];
            }

        }
        return parent::beforeAction($action);
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'payment-delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Magazine models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Magazine::find()->where(['user_id' => Yii::$app->user->identity->id])->with('tarif')->with('periodd')->with('user')->with('category')->orderBy('id DESC')
        ]);


        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Magazine model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = Magazine::find()->where(['id' => $id])->with('tarif')->with('periodd')->with('user')->with('category')->with('deliveries')->with('orderss')->one();

        if(empty($model))
        	throw new \yii\web\HttpException(404, "Такого магазина нету!");

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionPay($id)
    {
        $model = Magazine::find()->where(['id' => $id])->with('tarif')->with('periodd')->with('user')->with('category')->with('deliveries')->one();
        $model->scenario = Magazine::SCENARIO_UPDATE;
        if(empty($model))
            throw new \yii\web\HttpException(404, "Такого магазина нету!");

        $plans = MagazinePlan::find()->where('active = 1')->with('priceIndex')->asArray()->all();
        // echo '<pre>';
        // print_r($plans);
        // die;
        $periods = MagazinePeriod::find()->select('id, name')->asArray()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $model->validity_at = strtotime('+' . $model->periodd->days . ' days');
            $model->save(false);

            $ads = MagazineAds::find()->where(['magazine_id' => $model->id])->all();
            foreach ($ads as $key) {
                $key->validity_at = strtotime('+' . $model->periodd->days . ' days');
                $key->save(false);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('pay', [
            'model' => $model,
            'plans' => $plans,
            'periods' => $periods,
        ]);
    }

    /**
     * Creates a new Magazine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Magazine();
        $model->scenario = Magazine::SCENARIO_BACKEND;
        $plans = MagazinePlan::find()->select('id, name')->where('active = 1')->asArray()->all();
        $periods = MagazinePeriod::find()->select('id, name')->asArray()->all();
        $categories = MagazineCategory::find()->select('id, name')->asArray()->all();
        $payments = MagazinePayment::find()->select('name')->indexBy('id')->asArray()->column();
        $deliveries = MagazineDelivery::find()->select('name')->indexBy('id')->asArray()->column();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            if (!empty($model->deliveries)){
                MagazineHasDelivery::saveDeliveries($model->deliveries, $model->id);
            }

            if (!empty($model->payments)){
                MagazineHasPayment::savePayments($model->payments, $model->id);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'plans' => $plans,
                'periods' => $periods,
                'categories' => $categories,
                'deliveries' => $deliveries,
                'payments' => $payments,
            ]);
        }
    }

    /**
     * Updates an existing Magazine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Magazine::SCENARIO_BACKEND;
        $plans = MagazinePlan::find()->select('id, name')->where('active = 1')->asArray()->all();
        $periods = MagazinePeriod::find()->select('id, name')->asArray()->all();
        $categories = MagazineCategory::find()->select('id, name')->asArray()->all();
        $payments = MagazinePayment::find()->select('name')->indexBy('id')->asArray()->column();
        $deliveries = MagazineDelivery::find()->select('name')->indexBy('id')->asArray()->column();
        $model->payments = $model->getPaymentss()->select('payment_id')->asArray()->column();
        $model->deliveries = $model->getDeliveriess()->select('delivery_id')->asArray()->column();

        $reg = Yii::$app->db->createCommand('Select id, db_rootid, db_defnamelang as name from jandoo_vd_region')->queryAll();
        $city = Yii::$app->db->createCommand('Select id, db_rootid, db_defnamelang as name from jandoo_vd_city')->queryAll();

        // $model->worked_start_at = $model->worked_start_at;
        // $model->worked_end_at = $model->worked_end_at;

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {


            MagazineHasDelivery::deleteAll(['magazine_id' => $model->id]);
            if (!empty($model->deliveries)){
                MagazineHasDelivery::saveDeliveries($model->deliveries, $model->id);
            }

            MagazineHasPayment::deleteAll(['magazine_id' => $model->id]);
            if (!empty($model->payments)){
                MagazineHasPayment::savePayments($model->payments, $model->id);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {

        //     echo '<pre>';
        // print_r($model);
        // die;
            return $this->render('update', [
                'model' => $model,
                'plans' => $plans,
                'periods' => $periods,
                'categories' => $categories,
                'deliveries' => $deliveries,
                'payments' => $payments,
                'reg' => $reg,
                'city' => $city,
            ]);
        }
    }


    public function actionMagazinePayment($id)
    {
    	// $model = $this->findModel($id);
    	$model = MagazineHasPayment::find()->whre(['magazine_id' => $id])->all();

    	return $this->render('payment_index', [
    		'model' => $model,
    	]);
    }

    /**
     * Deletes an existing Magazine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        MagazineHasPayment::deleteAll(['magazine_id' => $id]);
        MagazineHasDelivery::deleteAll(['magazine_id' => $id]);
        MagazineHasCategories::deleteAll(['magazine_id' => $id]);
        MagazineAds::deleteAll(['magazine_id' => $id]);


        return $this->redirect(['index']);
    }

    /**
     * Finds the Magazine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Magazine the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Magazine::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionPayment($id)
    {

        $dataProvider = new ActiveDataProvider([
            'query' => MagazineHasPayment::find()->where(['magazine_id' => $id])->with('magazin')->with('payment'),
        ]);

        $magazine = Magazine::find()->where(['id' => $id])->asArray()->one();

        return $this->render('payment_index', [
            'dataProvider' => $dataProvider,
            'magazine' => $magazine,
        ]);
    }

    public function actionPaymentUpdate($id)
    {

        $model = MagazineHasPayment::findOne($id);
        $magazines = Magazine::find()->where(['id' => $model->magazine_id])->asArray()->one();
        $payments = MagazinePayment::find()->where(['id' => $model->payment_id])->asArray()->one();
        $magazine = Magazine::find()->where(['id' => $model->magazine_id])->asArray()->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/myaccount/magazine/payment/view', 'id' => $model->id]);
        } else {
            return $this->render('payment_update', [
                'model' => $model,
                'magazines' => $magazines,
                'payments' => $payments,
                'magazine' => $magazine,
            ]);
        }
    }

    public function actionPaymentDelete($id)
    {
        MagazineHasPayment::delete($id);

        return $this->redirect(['index']);
    }

    public function actionPaymentView($id)
    {
        $model = MagazineHasPayment::find()->where(['id' => $id])->with('magazin')->with('payment')->one();

        if(empty($model))
        	throw new \yii\web\HttpException(404, "Ничего не найдено!");

        return $this->render('payment_view', [
            'model' => $model,
        ]);
    }
}
