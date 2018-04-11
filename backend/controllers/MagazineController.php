<?php

namespace backend\controllers;

use Yii;

use frontend\models\Magazine;
use frontend\models\MagazinePlan;
use frontend\models\MagazinePrice;
use frontend\models\MagazinePeriod;
use frontend\models\MagazineCategory;
use frontend\models\MagazinePayment;
use frontend\models\MagazineDelivery;
use frontend\models\MagazineHasDelivery;
use frontend\models\MagazineHasPayment;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\UploadedFile;
/**
 * MagazineController implements the CRUD actions for Magazine model.
 */
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
            'query' => Magazine::find()->with('tarif')->with('periodd')->with('user')->with('category')->orderBy('id DESC')
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
        $model = Magazine::find()->where(['id' => $id])->with('tarif')->with('periodd')->with('user')->with('category')->with('deliveries')->one();

        if(empty($model))
        	throw new \yii\web\HttpException(404, "Такого магазина нету!");

        return $this->render('view', [
            'model' => $model,
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
        $model->scenario = Magazine::SCENARIO_BACKEND_FULL;

        // echo '<pre>';
        // print_r($model);
        // die;
        $plans = MagazinePlan::find()->select('id, name')->where('active = 1')->asArray()->all();
        $periods = MagazinePeriod::find()->select('id, name')->asArray()->all();
        $categories = MagazineCategory::find()->select('id, name')->asArray()->all();
        $payments = MagazinePayment::find()->select('name')->indexBy('id')->asArray()->column();
        $deliveries = MagazineDelivery::find()->select('name')->indexBy('id')->asArray()->column();

        $reg = Yii::$app->db->createCommand('Select id, db_rootid, db_defnamelang as name from jandoo_vd_region')->queryAll();
        $city = Yii::$app->db->createCommand('Select id, db_rootid, db_defnamelang as name from jandoo_vd_city')->queryAll();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            $model->backgroundFile = UploadedFile::getInstance($model, 'backgroundFile');

            if(!empty($model->backgroundFile)){

                $name_ = mb_substr(md5($model->backgroundFile->baseName . time()), 0, 6) . '.' . $model->backgroundFile->extension;
                $model->backgroundFile->saveAs(Yii::getAlias('@appWeb') . '/uploads/magazinebackground/' . $name_);
                $model->background_url = $name_;
                $model->save(false);


            }

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
                'city' => $city,
                'reg' => $reg,
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
        $model->scenario = Magazine::SCENARIO_BACKEND_FULL;

        // echo '<pre>';
        // print_r($model);
        // die;
        $plans = MagazinePlan::find()->select('id, name')->where('active = 1')->asArray()->all();
        $periods = MagazinePeriod::find()->select('id, name')->asArray()->all();
        $categories = MagazineCategory::find()->select('id, name')->asArray()->all();
        $payments = MagazinePayment::find()->select('name')->indexBy('id')->asArray()->column();
        $deliveries = MagazineDelivery::find()->select('name')->indexBy('id')->asArray()->column();
        $model->payments = $model->getPaymentss()->select('payment_id')->asArray()->column();
        $model->deliveries = $model->getDeliveriess()->select('delivery_id')->asArray()->column();

        $reg = Yii::$app->db->createCommand('Select id, db_rootid, db_defnamelang as name from jandoo_vd_region')->queryAll();
        $city = Yii::$app->db->createCommand('Select id, db_rootid, db_defnamelang as name from jandoo_vd_city')->queryAll();



        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $model->backgroundFile = UploadedFile::getInstance($model, 'backgroundFile');

            if(!empty($model->backgroundFile)){

                @unlink(Yii::getAlias('@appWeb') . '/uploads/magazinebackground/' . $model->background_url);
                $name_ = mb_substr(md5($model->backgroundFile->baseName . time()), 0, 6) . '.' . $model->backgroundFile->extension;
                $model->backgroundFile->saveAs(Yii::getAlias('@appWeb') . '/uploads/magazinebackground/' . $name_);
                $model->background_url = $name_;
                $model->save(false);


            }

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
            return $this->render('update', [
                'model' => $model,
                'plans' => $plans,
                'periods' => $periods,
                'categories' => $categories,
                'deliveries' => $deliveries,
                'payments' => $payments,
                'city' => $city,
                'reg' => $reg,
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

    public function actionDeleteimggAg()
    {
        $model_id = Yii::$app->request->post('model_id');
        $model = $this->findModel($model_id);
        $model->scenario = Magazine::SCENARIO_BACKEND_FULL;

        @unlink(Yii::getAlias('@appWeb') . '/uploads/magazinebackground/' . $model->background_url);

        $model->background_url = '';
        $model->save();
    }
}
