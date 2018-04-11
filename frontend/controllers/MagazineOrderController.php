<?php

namespace frontend\controllers;

use Yii;
use frontend\models\MagazineOrder;
use frontend\models\MagazineOrderItem;
use frontend\models\Magazine;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MagazineOrderController implements the CRUD actions for MagazineOrder model.
 */
class MagazineOrderController extends Controller
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
     * Lists all MagazineOrder models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MagazineOrder::find()->where(['magazine_id' => $id]),
        ]);

        $magazine = Magazine::find()->where(['id' => $id])->asArray()->one();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'magazine' => $magazine,
        ]);
    }

    /**
     * Displays a single MagazineOrder model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = MagazineOrder::find()->where(['id' => $id])->with('item')->with('magazine')->one();

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new MagazineOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new MagazineOrder();
        $model->magazine_id = $id;
        $magazines = Magazine::find()->where('active = 1')->asArray()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'magazines' => $magazines,
            ]);
        }
    }

    /**
     * Updates an existing MagazineOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $magazines = Magazine::find()->where('active = 1')->asArray()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'magazines' => $magazines,
            ]);
        }
    }

    /**
     * Deletes an existing MagazineOrder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $order_id = $model->id;
        $magazine_id = $model->magazine_id;

        MagazineOrderItem::deleteAll(['order_id' => $order_id]);
        $model->delete();

        return $this->redirect(['/myaccount/magazine/order', 'id' => $magazine_id]);
    }

    /**
     * Finds the MagazineOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MagazineOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MagazineOrder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
