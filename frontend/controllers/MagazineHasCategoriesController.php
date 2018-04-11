<?php

namespace frontend\controllers;

use Yii;
use frontend\models\MagazineHasCategories;
use frontend\models\Magazine;
use frontend\models\MagazineAds;
use frontend\models\Message;
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


    public function beforeAction($action)
    {
        if(!Yii::$app->user->isGuest){
            $my_id = Yii::$app->user->identity->id;


            $this->view->registerCssFile('/css/nprogress.css');
            
            $messages = Message::find()->select('count(unread) as unread, author_ads_id, ads_id')->where(['for' => $my_id])->andWhere('unread = 0')->indexBy('ads_id')->groupBy('ads_id')->asArray()->all();
            $all_count = 0;$count_my = 0;$count_send = 0;
            
            foreach ($messages as $key) {
                $all_count += $key['unread'];
                if ($key['author_ads_id'] == $my_id)
                    $count_my += $key['unread'];
                else
                    $count_send += $key['unread'];
            }

            Yii::$app->params['messages'] = $messages;
            Yii::$app->params['messages']['count'] = $all_count;
            Yii::$app->params['messages']['count_my'] = $count_my;
            Yii::$app->params['messages']['count_send'] = $count_send;

            if(
                $this->action->id == 'index' || 
                $this->action->id == 'view' ||
                $this->action->id == 'payment-update' ||
                $this->action->id == 'payment-delete' ||
                $this->action->id == 'payment-view' ||
                $this->action->id == 'payment'  ||
                $this->action->id == 'update' ||
                $this->action->id == 'create'
            ){
                Yii::$app->params['myaccount']['title'] = 'Магазины';
                Yii::$app->params['myaccount']['sub_title'] = 'Здесь вы найдёте ваши активные магазины';
            }

            if($this->action->id == 'index' || $this->action->id == 'archive'){
                Yii::$app->params['myaccount']['active_ads'] = Yii::$app->db->createCommand('SELECT COUNT(id) as active_count FROM jandoo_ads WHERE active = 1 AND user_id = :user_id')->bindValue(':user_id', Yii::$app->user->identity->id)->queryOne()['active_count'];
                Yii::$app->params['myaccount']['disactive_ads'] = Yii::$app->db->createCommand('SELECT COUNT(id) as disactive_count FROM jandoo_ads WHERE active = 0 AND user_id = :user_id')->bindValue(':user_id', Yii::$app->user->identity->id)->queryOne()['disactive_count'];
            }

            $ads = (new \yii\db\Query())
                        ->select('a.id, a.name, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, dd.id as parent_parent_category_id, dd.alias as parent_parent_category_alias, dd.name as parent_parent_category_name, count(d.id) as count')
                        ->from('jandoo_ads as a')
                        ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                        ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                        ->leftJoin('jandoo_categories as dd', 'dd.id = d.parent_id')
                        ->where('a.user_id =  ' . Yii::$app->user->identity->id )->groupBy('d.name')->all();

            
            foreach ($ads as $key) {
                if(empty($key['parent_parent_category_alias']))
                    Yii::$app->params['myaccount']['cat_limit'][$key['parent_category_alias']]['count'] = $key['count'];
                else
                    Yii::$app->params['myaccount']['cat_limit'][$key['parent_parent_category_alias']]['count'] += $key['count'];
            }

        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all MagazineHasCategories models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        // $dataProvider = new ActiveDataProvider([
        //     'query' => MagazineHasCategories::find()->where(['magazine_id' => $id])->orderBy('parent_id, sort ASC'),
        // ]);
        $model = \backend\models\Categories::getTree(MagazineHasCategories::find()->where(['magazine_id' => $id])->with('mainImage')->indexBy('id')->asArray()->orderBy('parent_id, sort ASC')->all());
        // echo '<pre>';
        // print_r($model);
        // die;
  
        $magazine = Magazine::find()->where(['id' => $id])->asArray()->one();


        return $this->render('index', [
            'model' => $model,
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
        $model = $this->findModel($id);
        $magazine = Magazine::find()->where(['id' => $model->magazine_id])->asArray()->one();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'magazine' => $magazine,
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
        $magazine = Magazine::find()->where(['id' => $model->magazine_id])->asArray()->one();


        if ($model->load(Yii::$app->request->post())) {

            $model->setSort();

            if($model->save()){
                return $this->redirect(['/myaccount/magazine/magazine-has-categories/view', 'id' => $model->id]);
            }

        }
        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
            'sort' => $sort,
            'magazine' => $magazine,
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
    
        } 

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MagazineHasCategories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $magazine_id = $model->magazine_id;
        $model->delete();
        MagazineAds::deleteAll(['magazine_id' => $magazine_id]);

        return $this->redirect(['/myaccount/magazine/magazine-has-categories', 'id' => $magazine_id]);
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
