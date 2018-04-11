<?php

namespace backend\controllers;

use Yii;
use frontend\models\MagazineHasPayment;
use frontend\models\MagazinePayment;
use frontend\models\Magazine;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MagazinePaymentController implements the CRUD actions for MagazineHasPayment model.
 */
class MagazinePaymentController extends Controller
{
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
     * Lists all MagazineHasPayment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MagazineHasPayment::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MagazineHasPayment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single MagazineHasPayment model.
     * @param integer $id
     * @return mixed
     */
    public function actionMagazineView($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MagazineHasPayment::find()->where(['magazine_id' => $id])->with('magazin')->with('payment'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new MagazineHasPayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MagazineHasPayment();
        $magazines = Magazine::find()->asArray()->all();
        $payments = MagazinePayment::find()->asArray()->all();


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'magazines' => $magazines,
                'payments' => $payments,
            ]);
        }
    }

    /**
     * Updates an existing MagazineHasPayment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $magazines = Magazine::find()->where(['id' => $model->magazine_id])->asArray()->one();
        $payments = MagazinePayment::find()->where(['id' => $model->payment_id])->asArray()->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'magazines' => $magazines,
                'payments' => $payments,
            ]);
        }
    }

    /**
     * Deletes an existing MagazineHasPayment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MagazineHasPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MagazineHasPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MagazineHasPayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
