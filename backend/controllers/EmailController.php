<?php

namespace backend\controllers;

use Yii;
use backend\models\Email;
use backend\models\SendMessage;
use backend\models\Categories;
use backend\models\FieldValue;
use backend\models\AdsHasImage;
use common\models\User;
use backend\controllers\MainController;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\data\Sort;
use yii\data\Pagination;
use backend\console\SendEmail;


class EmailController extends MainController
{

    public function actionIndex()
    {
        $this->view->title = 'Рассылка';
        $this->view->params['breadcrumbs'][] = $this->view->title; 

         $sort = new Sort([
            'defaultOrder' => ['id' => SORT_DESC],
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
                'created_at' => [
                    'asc' => ['created_at' => SORT_ASC],
                    'desc' => ['created_at' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Дата',
                ]
            ],
        ]);

        $query = Email::find()->asArray()->orderBy($sort->orders);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
        $mail = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();


        return $this->render('index',[
            'mail' => $mail,
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
        $this->view->title = 'Создание сообщения';
        $this->view->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => ['index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        $model = new Email();


        if ($model->load(Yii::$app->request->post())) {

        	$model->created_at = time();
            if($model->save()){

            	Yii::$app->queue->delay(2 * 60)->push(new SendEmail([
				    'users' => User::findAll(),
				    'email' => $model,
				]));
                
               
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post())) {

            if($model->save()){
                
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
        if (($model = Email::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
