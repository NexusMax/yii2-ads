<?php

namespace backend\controllers;

use Yii;
use backend\models\Categories;
use backend\models\AdsHasImage;
use backend\controllers\MainController;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\data\Sort;



class MegaController extends MainController
{

    public function actionIndex()
    {
        $this->view->title = 'Бухгалтерия';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        // $searchModel = new CategoriesSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $sort = new Sort([
            'defaultOrder' => ['sort'=>SORT_ASC],
            'attributes' => [
                'name' => [
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Название',
                ],
                'sort' => [
                    'asc' => ['sort' => SORT_ASC],
                    'desc' => ['sort' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => '№',
                ],
                'active' =>[
                    'asc' => ['active' => SORT_ASC],
                    'desc' => ['active' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Активность',
                ],
            ],
        ]);

        if (Yii::$app->request->isAjax) {
            $caterory = AdsHasImage::find()->orderBy('id DESC')->with('promotion')->one();
            $caterory->active = Yii::$app->request->post('checkbox_active');
            $caterory->save();
        }

        $promotion = AdsHasImage::find()->orderBy('id DESC')->with('promotion');

        return $this->render('index', compact('promotion', 'sort'));
    }


    /**
     * Displays a single Categories model.
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
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->view->title = 'Создание категории';
        $this->view->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        $model = new Categories();

        if ($model->load(Yii::$app->request->post())) {

            $status_sort = Yii::$app->request->post()['Categories']['sort'];

            if(empty($status_sort)){
                $model->setSort();
            }else{
                $model->setSortMiddle($status_sort);
            }

            if(empty($model->alias))
                $model->alias = $model->str2url($model->name);

            if($model->save()){

                $model->image = UploadedFile::getInstance($model, 'image');
            
                if($model->image){
                    $model->upload();
                }
               
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'sort' => $sort,
            ]);
        }
    }


    /**
     * Updates an existing Categories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $current_image = $model->image;

        if ($model->load(Yii::$app->request->post())) {

            $status_sort = Yii::$app->request->post()['Categories']['sort'];

            if(empty($status_sort)){
                $model->setSort();
            }else{
                $model->setSortMiddle($status_sort);
            }
            
            if($model->save(false)){
                $model->image = UploadedFile::getInstance($model, 'image');
                if($model->image){
                
                    if($model->image){
                        $model->upload();
                    }
                }else{
                    $model->image = $current_image;
                    $model->save(false);
                }
                
                return $this->redirect(['view', 'id' => $model->id]);
            }

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if(!empty($model->image)){
            @unlink('/home/sago3/sago.in.ua/temp9/web/uploads/categories/'. $model->image);
        }

        $child_model = Categories::find()->where(['parent_id' => $id])->all();
        if(!empty($child_model)){
           foreach ($child_model as $key) {
                $childer_model = $this->findModel($key->id);
                if(!empty($childer_model->image)){
                    @unlink('/home/sago3/sago.in.ua/temp9/web/uploads/categories/'. $childer_model->image);
                }
                $childer_model->delete();
           }
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
