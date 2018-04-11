<?php

namespace backend\controllers;

use backend\models\User;
use backend\models\Stock;
use backend\models\UserSearch;
use backend\controllers\MainController;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\data\Sort;
use yii\data\Pagination;


class UsersController extends MainController
{


    public function actionIndex()
    {
        $this->view->title = 'Пользователи';
        $this->view->params['breadcrumbs'][] = $this->view->title; 

         if (Yii::$app->request->isAjax) {
            $pages = User::find()->where(['id' => Yii::$app->request->post('id')])->one();
            $pages->active = Yii::$app->request->post('checkbox_active');
            $pages->save();
            return;
        }

        $sort = new Sort([
            'defaultOrder' => ['id' => SORT_DESC],
            'attributes' => [
                'name' => [
                    'asc' => ['username' => SORT_ASC],
                    'desc' => ['username' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Название',
                ],
                'id' => [
                    'asc' => ['id' => SORT_ASC],
                    'desc' => ['id' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => '№',
                ],
                'email' => [
                    'asc' => ['email' => SORT_ASC],
                    'desc' => ['email' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Email',
                ],
                'created_at' =>[
                    'asc' => ['created_at' => SORT_ASC],
                    'desc' => ['created_at' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Дата регистрации',
                ],
                'lastvisit' =>[
                    'asc' => ['lastvisit' => SORT_ASC],
                    'desc' => ['lastvisit' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Последнее посещение',
                ],
                'status' =>[
                    'asc' => ['ban' => SORT_ASC],
                    'desc' => ['ban' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Статус',
                ],
                'active' =>[
                    'asc' => ['active' => SORT_ASC],
                    'desc' => ['active' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Активность',
                ],
            ],
        ]);


        // if(!empty(Yii::$app->request->get('q'))){
        //     $q = Yii::$app->request->get('q');


        //     $query = User::find()->orderBy($sort->orders)->where(['id' => $q])->with('ads')->asArray();
        //     $countQuery = clone $query;
        //     $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //     $users = $query->offset($pages->offset)
        //     ->where(['id' => $q])
        //     ->limit($pages->limit)
        //     ->with('ads')
        //     ->all();
        // }else{

        //     $query = User::find()->orderBy($sort->orders)->with('ads')->asArray();
        //     $countQuery = clone $query;
        //     $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //     $users = $query->offset($pages->offset)
        //         ->limit($pages->limit)
        //         ->with('ads')
        //         ->all();
        // }

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $stock = Stock::find()->where(['>' ,'validity_at', time()])->orderBy('id DESC')->one();

        return $this->render('index', [
        	'pages' => $dataProvider->pagination,
        	'users' => $dataProvider->getModels(),
            'sort' => $sort,
            'stock' => $stock,
            'searchModel' => $searchModel,
        	]);
    }

    public function actionStockdel()
    {
        if (Yii::$app->request->isAjax) {
            $price = Yii::$app->request->post('id');
       
            Yii::$app->db->createCommand('DELETE FROM `jandoo_stock` WHERE `id` = :id', [
                ':id' => $price,
            ])->execute();
        }
    }



    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionPrice()
    {
        if (Yii::$app->request->isAjax) {
            parse_str(Yii::$app->request->post('data'), $data);
            $price = $data['price'];
       
            Yii::$app->db->createCommand('UPDATE `jandoo_user` SET `balance` = `balance` + :price', [
                ':price' => $price,
            ])->execute();
        }
    }

    public function actionStock()
    {
        if (Yii::$app->request->isAjax) {
            parse_str(Yii::$app->request->post('data'), $data);
            $price = $data['price'];
            $date = strtotime($data['validity']);
            
            $stock = new Stock();
            $stock->sum = $price;
            $stock->validity_at = $date;
            $stock->save();
        }
    }

    public function actionCreate()
    {
        $this->view->title = 'Добавить пользователя';
        $this->view->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        $model = new User();

        if ($model->load(Yii::$app->request->post())) {

            $model->lastvisit = time();
            $model->setPassword(Yii::$app->request->post('password'));
            $model->generateAuthKey();

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


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

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

    public function actionSetStatus()
    {
        if(Yii::$app->request->isAjax){
            User::setStatus();
        }
    }

    public function actionSetBalance()
    {
        if(Yii::$app->request->isAjax){

            parse_str(Yii::$app->request->post('data'), $data);

            // print_r($data);return;
            User::setBalance($data['user_id'], $data['price']);


            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo( $data['email'])
                ->setSubject( $data['tema'])
                ->setTextBody($data['message'])
                ->send();

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
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMoveUsers()
    {

        // vx57v_acymailing_listsub
        // vx57v_acymailing_subscriber
        $users = Yii::$app->db->createCommand('SELECT id, username, lastname, email, created_at, updated_at FROM {{%user}} WHERE mail_list = 0 AND email != "" ORDER BY id DESC')->queryAll();

        $usersIds = array_column($users, 'id');
        $tim = time();
        $rows = [];
        $rows2 = [];
        $data = [];
        $data2 = [];
        foreach ($users as $key) {
            $data['name'] = $key['username'] . ' ' . $key['lastname'];
            // $data['username'] = $key['lastname'];
            $data['email'] = $key['email'];
            $data['created'] = $tim;
            // $data['registerDate'] = date('Y-m-d H:i:s', $key['created_at']);
            // $data['lastvisitDate'] = date('Y-m-d H:i:s', $key['updated_at']);
            $rows[] = $data;
        }

        // if(!empty($usersIds)){
        //     Yii::$app->db2->createCommand()->batchInsert('vx57v_users', ['name', 'username', 'email', 'registerDate', 'lastvisitDate'], $rows)->execute();
        //     Yii::$app->db->createCommand('UPDATE {{%user}} SET `mail_list` = "1" WHERE `id` IN (' . implode(', ', $usersIds) . ')')->execute();


            // $users_joomla = Yii::$app->db2->createCommand('SELECT `id` FROM `vx57v_users` ORDER BY `id` DESC LIMIT ' . count($usersIds))->queryAll();
            // $users_ids = array_column($users_joomla, 'id');

            // foreach ($users_joomla as $key) {
            //     $data2['user_id'] = $key['id'];
            //     $data2['group_id'] = 2;
            //     $rows2[] = $data2;
            // }

            // Yii::$app->db2->createCommand()->batchInsert('vx57v_user_usergroup_map', ['user_id', 'group_id'], $rows2)->execute();
        // }

        if(!empty($usersIds)){
            Yii::$app->db2->createCommand()->batchInsert('vx57v_acymailing_subscriber', ['name', 'email', 'created'], $rows)->execute();
            Yii::$app->db->createCommand('UPDATE {{%user}} SET `mail_list` = "1" WHERE `id` IN (' . implode(', ', $usersIds) . ')')->execute();

            $users_joomla = Yii::$app->db2->createCommand('SELECT `subid` as `id` FROM `vx57v_acymailing_subscriber` WHERE `created` = "' . $tim . '" ORDER BY `id` DESC LIMIT ' . count($usersIds))->queryAll();
            $users_ids = array_column($users_joomla, 'id');
            
            $listsub = Yii::$app->db2->createCommand('SELECT COUNT(*) as count, listid FROM `vx57v_acymailing_listsub` GROUP BY `listid`')->queryAll();

            $listsubNumber = 0;

            foreach ($listsub as $key) {
               if((intval($key['count']) + intval(count($users_ids))) < 4500){
                    $listsubNumber = $key['listid']; break;
               }
            }

            if(intval($listsubNumber) === 0){
                $listsubNumber = $listsub[count($listsub) - 1]['listid'] + 1;
            }

            foreach ($users_joomla as $key) {

                $data2['listid'] = $listsubNumber;
                $data2['subid'] = $key['id'];
                $data2['subdate'] = $tim;
                $data2['status'] = 1;
                $rows2[] = $data2;
            }

            Yii::$app->db2->createCommand()->batchInsert('vx57v_acymailing_listsub', ['listid', 'subid', 'subdate', 'status'], $rows2)->execute();
         
            // }else{

            //     $listsubNumber = [];

            //     $count_ = ceil($users_ids / 4500);

            //     foreach ($listsub as $key) {
            //        if((intval($key['count']) + intval(count($users_ids))) < 4500){
            //             $listsubNumber = $key['listid']; break;
            //        }
            //     }

            //     $max = 4499;
            //     for($i = 0; $i < count($users_joomla); $i++){
            //         $data2['listid'] = $listsubNumber;
            //         $data2['subid'] = $users_joomla[$i]['id'];
            //         $data2['subdate'] = $tim;
            //         $data2['status'] = 1;
            //         $rows2[] = $data2;

                    
            //         if($i > $max){

            //             Yii::$app->db2->createCommand()->batchInsert('vx57v_acymailing_listsub', ['listid', 'subid', 'subdate', 'status'], $rows)->execute();
                        
            //             $max += 4500;


            //             $listsub = Yii::$app->db2->createCommand('SELECT COUNT(*) as count, listid FROM `vx57v_acymailing_listsub` GROUP BY `listid`')->queryAll();

            //             foreach ($listsub as $key) {
            //                if((intval($key['count']) + intval(count($rows))) < 4500){
            //                     $listsubNumber = $key['listid']; break;
            //                }
            //             }

            //             if(intval($listsubNumber) === 0){
            //                 $listsubNumber = $listsub[count($listsub) - 1]['listid'] + 1;
            //             }

            //             $rows = [];
            //         }
            //     }
            //     if(!empty($rows2)){
            //         Yii::$app->db2->createCommand()->batchInsert('vx57v_acymailing_listsub', ['listid', 'subid', 'subdate', 'status'], $rows)->execute();
            //     }
                

            // }

        }
        
       


        return $this->redirect(['index']);
    }
}