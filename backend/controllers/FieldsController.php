<?php

namespace backend\controllers;

use Yii;
use backend\models\Fields;
use backend\models\Categories;
use common\models\User;
use backend\controllers\MainController;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\data\Sort;
use yii\data\Pagination;


class FieldsController extends MainController
{

    public function actionIndex()
    {
        $this->view->title = 'Дополнительные поля';
        $this->view->params['breadcrumbs'][] = $this->view->title; 

        if (Yii::$app->request->isAjax) {
            $field = Fields::find()->where(['id' => Yii::$app->request->post('id')])->one();
            $field->active = Yii::$app->request->post('checkbox_active');
            $field->save();
            return;
        }

        
        $sort = new Sort([
            'defaultOrder' => ['id' => SORT_ASC],
            'attributes' => [
                'name' => [
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Название',
                ],
                'id' => [
                    'asc' => ['id' => SORT_ASC],
                    'desc' => ['id' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => '№',
                ],
                'category' => [
                    'asc' => ['category_id' => SORT_ASC],
                    'desc' => ['category_id' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Категория',
                ],
                'active' =>[
                    'asc' => ['active' => SORT_ASC],
                    'desc' => ['active' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Активность',
                ],
            ],
        ]);

        $query = Fields::find()->orderBy($sort->orders)->asArray();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 500]);
        $fields = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $array_categories = null;
        foreach ($fields as $key) {
            foreach (json_decode($key['category_id'], true) as $value) {
                if(!in_array($value, $array_categories))
                    $array_categories[] = $value; 
            }         
        }

        $categories = Categories::find()->asArray()->where(['in', 'id', $array_categories])->indexBy('id')->all();

        return $this->render('index', [
        	'fields' => $fields,
        	'categories' => $categories,
            'sort' => $sort,
            'pages' => $pages,
        ]);
    }



    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $this->view->title = 'Создание поля';
        $this->view->params['breadcrumbs'][] = ['label' => 'Дополнительные поля', 'url' => ['index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        $model = new Fields();

        if ($model->load(Yii::$app->request->post())) {

            if(isset(Yii::$app->request->post()['Fields']['category_id']))
                $model->category_id = json_encode(Yii::$app->request->post()['Fields']['category_id'], JSON_FORCE_OBJECT);

            if(isset(Yii::$app->request->post()['Fields']['sub_field'])){

                $array_name = null;
                
                foreach (Yii::$app->request->post()['Fields']['sub_field'] as $key) {

                    if(Yii::$app->request->post()['Fields']['type'] == 'radio')
                        $array_name[] = $model->str2url($model->name);
                    else
                        $array_name[] = $model->str2url($key);
                }
                $model->value_sub_field = json_encode(array_combine(Yii::$app->request->post()['Fields']['sub_field'], $array_name));

              
            }


            $model->name_field = $model->str2url($model->name);

            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionNew()
    {
// echo '<pre>';
//         $model = $field = Fields::find()->all();
//         foreach ($model as $key) {
//             $val = json_decode($key->value_sub_field, true);
//             echo '<br>';
//              print_r($val);
//         }
       
        
//         echo '</pre>';die;
//         return $this->render('update', [
//                 'model' => $model
//             ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->category_id = json_decode($model->category_id, true);

        if ($model->load(Yii::$app->request->post())) {


            if(isset(Yii::$app->request->post()['Fields']['category_id']))
                $model->category_id = json_encode(Yii::$app->request->post()['Fields']['category_id'], JSON_FORCE_OBJECT);

            if(isset(Yii::$app->request->post()['Fields']['sub_field'])){
                
                $array_name = null;
                
                foreach (Yii::$app->request->post()['Fields']['sub_field'] as $key) {
                    if(Yii::$app->request->post()['Fields']['type'] == 'radio')
                        $array_name[] = $model->str2url($model->name);
                    else
                        $array_name[] = $model->str2url($key);
                }
                
               $model->value_sub_field = json_encode(array_combine(Yii::$app->request->post()['Fields']['sub_field'], $array_name));
            }


            $model->name_field = $model->str2url($model->name);

            if($model->save(false)){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
		else {
            return $this->render('update', [
                'model' => $model
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
        if (($model = Fields::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
