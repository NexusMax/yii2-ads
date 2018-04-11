<?php

namespace backend\controllers;

use Yii;
use backend\models\AdminMessage;
use common\models\User;
use backend\controllers\MainController;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\data\Sort;
use yii\data\Pagination;


class MessagesController extends MainController
{

    public function actionIndex()
    {
        $this->view->title = 'Сообщения';
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
                'status' =>[
                    'asc' => ['unread' => SORT_ASC],
                    'desc' => ['undead' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Статус',
                ],
            ],
        ]);


        $query = AdminMessage::find()->orderBy($sort->orders)->with('user')->with('parent')->where(['parent_id' => NULL])->asArray();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        $messages = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();


        // $array_users = null;
        // foreach ($blog as $key) {
        //     $array_users[] = $key->user_id;
        // }

      //  $users = User::find()->indexBy('id')->where(['in', 'id', $array_users])->asArray()->all();


        return $this->render('index', [
        	'messages' => $messages,
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
        $this->view->title = 'Создание сообщения';
        $this->view->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => ['index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        $model = new AdminMessage();

        if ($model->load(Yii::$app->request->post())) {

            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($model->email)
                ->setSubject('Jandoo.com')
                ->setTextBody($model->text)
                ->send();

            if($model->save()){

                
               
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'sort' => $sort,
            ]);
        }
    }

    public function actionSend()
    {
        if(Yii::$app->request->post('data')){
            parse_str(Yii::$app->request->post('data'), $data);


            $message = AdminMessage::find()->where(['id' => $data['message_id']])->one();
            $message->unread = 1;
            $message->save(false);

            $new_m = new AdminMessage();
            $new_m->text = $data['message'];
            $new_m->email = Yii::$app->params['adminEmail'];
            $new_m->unread = 1;
            $new_m->parent_id = $data['message_id'];
            $new_m->user_id = Yii::$app->user->identity->id;
            $new_m->created_at = time();
            $new_m->save(false);

            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($data['email'])
                ->setSubject($data['tema'])
                ->setTextBody($data['message'])
                ->send();

            return true;  
        }
         
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post())) {

            if($model->save()){
                
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
        if (($model = AdminMessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
