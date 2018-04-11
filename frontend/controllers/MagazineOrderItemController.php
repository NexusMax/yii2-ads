<?php

namespace frontend\controllers;

use Yii;
use frontend\models\MagazineOrderItem;
use frontend\models\MagazineOrder;
use frontend\models\MagazineAds;
use frontend\models\Magazine;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MagazineOrderItemController implements the CRUD actions for MagazineOrderItem model.
 */
class MagazineOrderItemController extends Controller
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
     * Lists all MagazineOrderItem models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MagazineOrderItem::find()->where(['order_id' => $id])->with('order'),
        ]);

        $order = MagazineOrder::find()->where(['id' => $id])->asArray()->one();
        $magazine = Magazine::find()->where(['id' => $order['magazine_id']])->asArray()->one();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'order' => $order,
            'magazine' => $magazine,
        ]);
    }

    /**
     * Displays a single MagazineOrderItem model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $order = MagazineOrder::find()->where(['id' => $model->order_id])->asArray()->one();
        $magazine = Magazine::find()->where(['id' => $order['magazine_id']])->asArray()->one();

        return $this->render('view', [
            'model' => $model,
            'magazine' => $magazine,
            'order' => $order,
        ]);
    }

    /**
     * Creates a new MagazineOrderItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new MagazineOrderItem();
        $model->order_id = $id;
        $model->quantity = 1;

        $order = MagazineOrder::find()->where(['id' => $id])->asArray()->one();

        $ads = MagazineAds::find()->where(['magazine_id' => $order['magazine_id']])->asArray()->all();

        if ($model->load(Yii::$app->request->post())) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'ads' => $ads,
            ]);
        }
    }

    /**
     * Updates an existing MagazineOrderItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $order = MagazineOrder::find()->where(['id' => $model->order_id])->asArray()->one();
        $ads = MagazineAds::find()->where(['magazine_id' => $order['magazine_id']])->asArray()->all();
        $magazine = Magazine::find()->where(['id' => $order['magazine_id']])->asArray()->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'ads' => $ads,
                'magazine' => $magazine,
                'order' => $order,
            ]);
        }
    }

    /**
     * Deletes an existing MagazineOrderItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $order_id = $model->order_id;
        $model->delete();

        return $this->redirect(['/myaccount/magazine/order-item', 'id' => $order_id]);
    }

    /**
     * Finds the MagazineOrderItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MagazineOrderItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MagazineOrderItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
