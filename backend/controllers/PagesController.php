<?php

namespace backend\controllers;

use Yii;
use backend\models\Ads;
use backend\models\Pages;
use backend\models\SendMessage;
use backend\models\Categories;
use backend\models\AdsHasImage;
use common\models\User;
use backend\controllers\MainController;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\data\Pagination;
use yii\data\Sort;

class PagesController extends MainController
{

    public function actionIndex()
    {
        $this->view->title = 'Страницы';
        $this->view->params['breadcrumbs'][] = $this->view->title; 

         if (Yii::$app->request->isAjax) {
            $pages = Pages::find()->where(['id' => Yii::$app->request->post('id')])->one();
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
                'user' => [
                    'asc' => ['user_id' => SORT_ASC],
                    'desc' => ['user_id' => SORT_DESC],
                    'default' => SORT_DESC,
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


        $query = Pages::find()->orderBy($sort->orders)->asArray();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        $pagess = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();


        $array_users = null;
        foreach ($pagess as $key) {
            $array_users[] = $key['user_id'];
        }

        $users = User::find()->indexBy('id')->where(['in', 'id', $array_users])->asArray()->all();
        
        return $this->render('index', [
            'pages' => $pages,
        	'pagess' => $pagess,
        	'users' => $users,
            'sort' => $sort,
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
        $this->view->title = 'Создание страницы';
        $this->view->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        $model = new Pages();

        if ($model->load(Yii::$app->request->post())) {

            if(empty($model->alias))
                $model->alias = $model->str2url($model->name);

            //  if(preg_match('/-/', $model->alias)){
            // 	$name = explode('-',$model->alias);
            // 	$name_ar = [];
            // 	$name_new = null;
            // 	foreach ($name as $key) {
            // 		$name_ar[] = ucfirst($key);
            // 	}
            // 	$name_new = implode('',$name_ar);
            // }else{
            // 	$name_new = ucfirst($model->alias);
            // }

      //       mkdir(Yii::getAlias('@frontend') . "/views/" . $model->alias . "", 0700);

      //       $fp = fopen(Yii::getAlias('@frontend') . "/views/" . $model->alias . "/index.php", "w");
      //       $text = '<h1>' . $model->name . '</h1><p>' . $model->text . '</p>';
      //       fwrite($fp, $text);
      //       fclose($fp);

      //       $fp = fopen(Yii::getAlias('@frontend') . "/controllers/" . $name_new . "Controller.php", "w");
      //       $text = '<?php
						// namespace frontend\controllers;

						// use Yii;
						// use yii\web\Controller;

						// class ' . $name_new . 'Controller extends Controller
						// {
						    
						//     public function actionIndex()
						//     {
						//         return $this->render("index");
						//     }
						// }';
      //       fwrite($fp, $text);
      //       fclose($fp);

            $model->user_id = Yii::$app->user->identity->id;

            if($model->save()){
                Yii::$app->cache->flush('header_menu');
                Yii::$app->cache->flush('footer_menu');
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

    /**
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // if(preg_match('/-/', $model->alias)){
        //     	$name = explode('-',$model->alias);
        //     	$name_ar = [];
        //     	$name_new = null;
        //     	foreach ($name as $key) {
        //     		$name_ar[] = ucfirst($key);
        //     	}
        //     	$name_new = implode('',$name_ar);
        //     }else{
        //     	$name_new = ucfirst($model->alias);
        //     }

        // if(file_exists(Yii::getAlias('@frontend') . "/controllers/" . $name_new . "Controller.php")){
        //     unlink(Yii::getAlias('@frontend') . "/views/" . $model->alias . "/index.php");
        // 	rmdir(Yii::getAlias('@frontend') . "/views/" . $model->alias . "");
        // 	unlink(Yii::getAlias('@frontend') . "/controllers/" . $name_new . "Controller.php");
        // }

         Yii::$app->cache->flush('header_menu');
                Yii::$app->cache->flush('footer_menu');
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
        if (($model = Pages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
