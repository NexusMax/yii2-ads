<?php

namespace backend\controllers;

use Yii;
use frontend\models\MagazineHasCategories;
use frontend\models\Magazine;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MagazineHasCategoriesController implements the CRUD actions for MagazineHasCategories model.
 */
class MagazineHasCategoriesController extends Controller
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
     * Lists all MagazineHasCategories models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MagazineHasCategories::find()->where(['magazine_id' => $id]),
        ]);

        $magazine = Magazine::find()->where(['id' => $id])->asArray()->one();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'magazine' => $magazine,
        ]);
    }

    /**
     * Displays a single MagazineHasCategories model.
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
     * Creates a new MagazineHasCategories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new MagazineHasCategories();
        $model->magazine_id = $id;
        $model->active = 1;

        $categories = MagazineHasCategories::find()->where(['magazine_id' => $id])->andWhere('active = 1')->orderBy('sort ASC')->asArray()->all();

        if ($model->load(Yii::$app->request->post())) {

            $model->setSort();

            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }

        }
        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
            'sort' => $sort,
        ]);
    }

    /**
     * Updates an existing MagazineHasCategories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $categories = MagazineHasCategories::find()->where(['magazine_id' => $id])->andWhere('active = 1')->orderBy('sort ASC')->asArray()->all();

        if ($model->load(Yii::$app->request->post())) {

            $model->setSort();

            if( $model->save() ){
                return $this->redirect(['view', 'id' => $model->id]);
            }
    
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MagazineHasCategories model.
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
     * Finds the MagazineHasCategories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MagazineHasCategories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MagazineHasCategories::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
