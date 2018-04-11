<?php

namespace backend\controllers;

use Yii;
use backend\models\Blog;
use common\models\User;
use backend\controllers\MainController;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\data\Sort;
use yii\data\Pagination;


class BlogController extends MainController
{

    public function actionIndex()
    {
        $this->view->title = 'Блог';
        $this->view->params['breadcrumbs'][] = $this->view->title; 

        if (Yii::$app->request->isAjax) {
            $blog = Blog::find()->where(['id' => Yii::$app->request->post('id')])->one();
            $blog->active = Yii::$app->request->post('checkbox_active');
            $blog->save();
            return;
        }

        $sort = new Sort([
            'defaultOrder' => ['id'=>SORT_DESC],
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
                    'default' => SORT_ASC,
                    'label' => '№',
                ],
                'user' => [
                    'asc' => ['user_id' => SORT_ASC],
                    'desc' => ['user_id' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Пользователь',
                ],
                'active' =>[
                    'asc' => ['active' => SORT_ASC],
                    'desc' => ['active' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Активность',
                ],
            ],
        ]);


        $query = Blog::find()->orderBy($sort->orders)->asArray();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        $blog = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();


        $array_users = null;
        foreach ($blog as $key) {
            $array_users[] = $key->user_id;
        }

        $users = User::find()->indexBy('id')->where(['in', 'id', $array_users])->asArray()->all();


        return $this->render('index', [
        	'blog' => $blog,
            'pages' => $pages,
        	'users' => $users,
            'sort'=> $sort,
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
        $this->view->title = 'Создание поста';
        $this->view->params['breadcrumbs'][] = ['label' => 'Посты', 'url' => ['index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        $model = new Blog();

        if ($model->load(Yii::$app->request->post())) {

            if(empty($model->alias)){
                $model->alias = $model->str2url($model->name);
            }

             
            $model->user_id = Yii::$app->user->identity->id;

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


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $current_image = $model->image;

        if ($model->load(Yii::$app->request->post())) {

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

        if(!empty($model->image)){
            @unlink(Yii::getAlias('@appWeb') . '/uploads/blog/' . $model->image);
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
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
