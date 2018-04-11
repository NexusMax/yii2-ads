<?php

namespace backend\controllers;

use Yii;
use frontend\models\MagazineSuccessPayed;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MagazineSuccessController implements the CRUD actions for MagazineSuccessPayed model.
 */
class MagazineSuccessController extends Controller
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
     * Lists all MagazineSuccessPayed models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MagazineSuccessPayed::find()->with('user')->with('tarif')->orderBy('id DESC'),
        ]);
      
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MagazineSuccessPayed model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = MagazineSuccessPayed::find()->where(['id' => $id])->one();



        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new MagazineSuccessPayed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MagazineSuccessPayed();
        $tarif_ = \frontend\models\MagazinePrice::find()->with('plan')->with('period')->asArray()->all();
        
        $tarif = [];
        foreach ($tarif_ as $key => $value) {
            $tarif[$value['id']] = $value['plan']['name'] . ' (период: ' . $value['period']['name'] . ')';
        }



        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'tarif' => $tarif,
            ]);
        }
    }

    /**
     * Updates an existing MagazineSuccessPayed model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $tarif_ = \frontend\models\MagazinePrice::find()->with('plan')->with('period')->asArray()->all();
        
        $tarif = [];
        foreach ($tarif_ as $key => $value) {
            $tarif[$value['id']] = $value['plan']['name'] . ' (период: ' . $value['period']['name'] . ')';
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'tarif' => $tarif,
            ]);
        }
    }

    /**
     * Deletes an existing MagazineSuccessPayed model.
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
     * Finds the MagazineSuccessPayed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MagazineSuccessPayed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MagazineSuccessPayed::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
