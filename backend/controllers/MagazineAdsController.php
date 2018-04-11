<?php

namespace backend\controllers;

use Yii;
use frontend\models\MagazineAds;
use frontend\models\Magazine;
use frontend\models\MagazineEavFields;
use frontend\models\MagazineEavValue;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MagazineAdsController implements the CRUD actions for MagazineAds model.
 */
class MagazineAdsController extends Controller
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
     * Lists all MagazineAds models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MagazineAds::find()->where(['magazine_id' => $id]),
        ]);

        $magazine = Magazine::find()->where(['id' => $id])->asArray()->one();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'magazine' => $magazine,
        ]);
    }

    /**
     * Displays a single MagazineAds model.
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
     * Creates a new MagazineAds model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new MagazineAds();
        $model->scenario = MagazineAds::SCENARIO_PRODUCT;
        $model->magazine_id = $id;

        $magazine = Magazine::find()->where(['id' => $id])->one();
        $fields = MagazineEavFields::find()->where(['category_id' => $magazine->category_id])->with('opts')->with('type')->all();

        // echo '<pre>';
        // print_r($fields);
        // die;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $attrField = Yii::$app->request->post('AttrField');

            if(!empty($attrField)){
                foreach ($attrField as $key => $value) {

                    foreach($value as $_vl){
                        $val = new MagazineEavValue();
                        $val->value = $_vl;
                        $val->product_id = $model->id;

                        foreach($fields as $key_){
                            if(strcmp($key_['name'], $key) === 0){
                                $val->field_id = $key_['id'];
                            }

                            if(!empty($key_['opts'])){
                            	foreach($key_['opts'] as $key__){

	                                if(strcmp($key__['id'], $val->value) === 0){
	                                    $val->option_id = $key__['id'];
	                                    $val->save(false);
	                                }
	                            }
                            }else{
                            	$val->save(false);
                            }
                        }

                    }
                    
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            // echo '<pre>';
            // print_r($model);
            // die;
            return $this->render('create', [
                'model' => $model,
                'fields' => $fields,
            ]);
        }
    }

    public function actionCopy($id)
    {
        $old_model = MagazineAds::find()->where(['id' => $id])->one();

        $model = new MagazineAds();
        $model->scenario = MagazineAds::SCENARIO_PRODUCT;
        $model->name = $old_model->name;
        $model->text = $old_model->text;
        $model->category_id = $old_model->category_id;
        $model->price = $old_model->price;
        $model->magazine_id = $old_model->magazine_id;

        $magazine = Magazine::find()->where(['id' => $old_model->magazine_id])->asArray()->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'magazine' => $magazine,
            ]);
        }
    }

    /**
     * Updates an existing MagazineAds model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = MagazineAds::find()->where(['id' => $id])->with('vals')->one();
        $model->scenario = MagazineAds::SCENARIO_PRODUCT;

        $vals = MagazineEavValue::find()->where(['product_id' => $model->id])->with('field')->asArray()->all();
        $params = [];

        foreach($vals as $key){

        	$params[$key['field']['name']][] = $key['value'];
        }
        // echo '<pre>';
        // print_r($params);
        // die;

        $magazine = Magazine::find()->where(['id' => $model->magazine_id])->one();
        $fields = MagazineEavFields::find()->where(['category_id' => $magazine->category_id])->with('opts')->with('type')->all();


        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            MagazineEavValue::deleteAll(['product_id' => $model->id]);
            $attrField = Yii::$app->request->post('AttrField');

            if(!empty($attrField)){
                foreach ($attrField as $key => $value) {

                    foreach($value as $_vl){
                        $val = new MagazineEavValue();
                        $val->value = $_vl;
                        $val->product_id = $model->id;

                        foreach($fields as $key_){
                            if(strcmp($key_['name'], $key) === 0){
                                $val->field_id = $key_['id'];
                            }

                            if(!empty($key_['opts'])){
                            	foreach($key_['opts'] as $key__){

	                                if(strcmp($key__['id'], $val->value) === 0){
	                                    $val->option_id = $key__['id'];
	                                    $val->save(false);
	                                }
	                            }
                            }else{
                            	$val->save(false);
                            }
                        }

                    }
                    
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'fields' => $fields,
                'params' => $params,
            ]);
        }
    }

    /**
     * Deletes an existing MagazineAds model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {	
    	$model = MagazineAds::find()->where(['id' => $id])->one();
    	$magazine_id = $model->magazine_id;
    	$model->delete();

        return $this->redirect(['/magazine-ads', 'id' => $magazine_id]);
    }

    /**
     * Finds the MagazineAds model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MagazineAds the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MagazineAds::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeleteImg()
    {
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('key');

            $image = \rico\yii2images\models\Image::find()->where(['id' => $id])->one();

            $str = explode('/', $image->filePath);
            $alias = Yii::getAlias('@appWeb') . '/images/store/' . $image->filePath;
            $alias_mini = Yii::getAlias('@appWeb') . '/images/store/' . $str[0] . '/' . $str[1] . '/mini_' . $str[2];
            @unlink($alias);
            @unlink($alias_mini);

            $image->delete();

            return true;
        }

        return false;
    }
}
