<?php

namespace backend\controllers;

use Yii;
use frontend\models\MagazineEavFields;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\MagazineCategory;
use frontend\models\MagazineEavTypes;
use frontend\models\MagazineEavOptions;
/**
 * MagazineEavFieldsController implements the CRUD actions for MagazineEavFields model.
 */
class MagazineEavFieldsController extends Controller
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
     * Lists all MagazineEavFields models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MagazineEavFields::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MagazineEavFields model.
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
     * Creates a new MagazineEavFields model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MagazineEavFields();
        $model->active = 1;

        $categories = MagazineCategory::find()->where('active = 1')->asArray()->all();
        $types = MagazineEavTypes::find()->asArray()->all();


        if ($model->load(Yii::$app->request->post()) ) {

            $model->save();
            $fields = Yii::$app->request->post('Fields');

            if(!empty($fields)){
                $sub_field = $fields['sub_field'];
                foreach ($sub_field as $key) {
                    $subfld = new MagazineEavOptions();
                    $subfld->field_id = $model->id;
                    $subfld->name = $key;
                    $subfld->save();
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'categories' => $categories,
                'types' => $types,
            ]);
        }
    }

    /**
     * Updates an existing MagazineEavFields model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = MagazineEavFields::find()->where(['id' => $id])->with('opts')->one();

        $categories = MagazineCategory::find()->where('active = 1')->asArray()->all();
        $types = MagazineEavTypes::find()->asArray()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            MagazineEavOptions::deleteAll(['field_id' => $model->id]);
            
            $fields = Yii::$app->request->post('Fields');

            if(!empty($fields)){
                $sub_field = $fields['sub_field'];
                foreach ($sub_field as $key) {
                    $subfld = new MagazineEavOptions();
                    $subfld->field_id = $model->id;
                    $subfld->name = $key;
                    $subfld->save();
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                 'model' => $model,
                'categories' => $categories,
                'types' => $types,
            ]);
        }
    }

    /**
     * Deletes an existing MagazineEavFields model.
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
     * Finds the MagazineEavFields model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MagazineEavFields the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MagazineEavFields::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
