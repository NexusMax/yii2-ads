<?php

namespace frontend\controllers;

use Yii;
use frontend\models\MagazineAds;
use frontend\models\MagazinePrice;
use frontend\models\Magazine;
use frontend\models\Message;
use frontend\models\MagazineAdsLog;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use frontend\models\MagazineEavFields;
use frontend\models\MagazineEavValue;

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
     * Lists all MagazineAds models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MagazineAds::find()->where(['magazine_id' => $id])->with('favorite')->with('category')->with('mainImage')->with('fire'),
        ]);

        $magazine = Magazine::find()->where(['id' => $id])->asArray()->one();

        $tarif = MagazinePrice::find()->where(['plan_id' => $magazine['tarif_plan'] ])->andWhere(['period_id' => $magazine['period'] ])->with('period')->asArray()->one();
        $magazine_ads = MagazineAds::find()->where(['magazine_id' => $id])->andWhere(['>', 'created_at', strtotime('-' . $tarif['period']['days'] . ' days')])->asArray()->all();

        $count_ads = count($magazine_ads);
        $sum_next_ads = $tarif['dop_tov'];
        $count_free_ads = $tarif['count_ads'];

        $count_free_fire = $tarif['fire'];
        $count_free_update = $tarif['top_30_day'];


        $log = MagazineAdsLog::find()->where(['magazine_id' => $id])->andWhere(['>', 'created_at', strtotime('-' . $tarif['period']['days'] . ' days')])->asArray()->all();

        $count_fire = 0;
        $count_update = 0;
        foreach ($log as $key) {
            if($key['type'] == 2){
                $count_fire = $count_fire + 1;
            }

            if($key['type'] == 1){
                $count_update = $count_update + 1;
            }
        }


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'magazine' => $magazine,

            'count_ads' => $count_ads,
            'sum_next_ads' => $sum_next_ads,
            'count_free_ads' => $count_free_ads,

            'count_free_fire' => $count_free_fire,
            'count_fire' => $count_fire,
            'count_free_update' => $count_free_update,
            'count_update' => $count_update,
        ]);
    }

    /**
     * Displays a single MagazineAds model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        // $model = $this->findModel($id);
        // echo '<pre>';
        // print_r($model->getAllImages());
        // die;

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpd($id)
    {
        $model = MagazineAds::find()->where(['id' => $id])->one();
        $model->scenario = MagazineAds::SCENARIO_PRODUCT;

        $magazine = Magazine::find()->where(['id' => $model->magazine_id])->asArray()->one();

        $tarif = MagazinePrice::find()->where(['plan_id' => $magazine['tarif_plan'] ])->andWhere(['period_id' => $magazine['period'] ])->with('period')->asArray()->one();
        $magazine_ads = MagazineAds::find()->where(['magazine_id' => $id])->andWhere(['>', 'created_at', strtotime('-' . $tarif['period']['days'] . ' days')])->asArray()->all();


        $count_free_fire = $tarif['fire'];
        $count_free_update = $tarif['top_30_day'];

        $log = MagazineAdsLog::find()->where(['magazine_id' => $model->magazine_id])->andWhere(['>', 'created_at', strtotime('-' . $tarif['period']['days'] . ' days')])->asArray()->all();

        $count_fire = 0;
        $count_update = 0;
        foreach ($log as $key) {
            if($key['type'] == 2){
                $count_fire = $count_fire + 1;
            }

            if($key['type'] == 1){
                $count_update = $count_update + 1;
            }
        }

        
        if($count_update > $count_free_update){
            Yii::$app->session->setFlash('warning', 'Количество обновлений исчерпано.');
        }else{
            $model->updated_by = time();
            $model->save(false);

            $lo = new MagazineAdsLog();
            $lo->user_id = Yii::$app->user->id;
            $lo->magazine_id = $model->magazine_id;
            $lo->ads_id = $model->id;
            $lo->validity_at = time();
            $lo->type = 1;
            $lo->save();

            Yii::$app->session->setFlash('success', 'Товар "' . $model->name . '" успешно обновлено.');
        }


        return $this->redirect(['myaccount/magazine/ads', 'id' => $model->magazine_id]);

    }

    public function actionFire($id)
    {
                $model = MagazineAds::find()->where(['id' => $id])->one();
        $model->scenario = MagazineAds::SCENARIO_PRODUCT;

        $magazine = Magazine::find()->where(['id' => $model->magazine_id])->asArray()->one();

        $tarif = MagazinePrice::find()->where(['plan_id' => $magazine['tarif_plan'] ])->andWhere(['period_id' => $magazine['period'] ])->with('period')->asArray()->one();
        $magazine_ads = MagazineAds::find()->where(['magazine_id' => $id])->andWhere(['>', 'created_at', strtotime('-' . $tarif['period']['days'] . ' days')])->asArray()->all();


        $count_free_fire = $tarif['fire'];
        $count_free_update = $tarif['top_30_day'];

        $log = MagazineAdsLog::find()->where(['magazine_id' => $model->magazine_id])->andWhere(['>', 'created_at', strtotime('-' . $tarif['period']['days'] . ' days')])->asArray()->all();

        $count_fire = 0;
        $count_update = 0;
        foreach ($log as $key) {
            if($key['type'] == 2){
                $count_fire = $count_fire + 1;
            }

            if($key['type'] == 1){
                $count_update = $count_update + 1;
            }
        }

        
        if($count_fire > $count_free_fire){
            Yii::$app->session->setFlash('warning', 'Количество Срочно для товаров исчерпано.');
        }else{

            $lo = new MagazineAdsLog();
            $lo->user_id = Yii::$app->user->id;
            $lo->magazine_id = $model->magazine_id;
            $lo->ads_id = $model->id;
            $lo->validity_at = strtotime('+1 day');
            $lo->type = 2;
            $lo->save();

            Yii::$app->session->setFlash('success', 'Срочно на товаре "' . $model->name . '" успешно установлено сроком на 1 день.');
        }


        return $this->redirect(['myaccount/magazine/ads', 'id' => $model->magazine_id]);
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

        $magazine = Magazine::find()->where(['id' => $id])->asArray()->one();
        $tarif = MagazinePrice::find()->where(['plan_id' => $magazine['tarif_plan'] ])->andWhere(['period_id' => $magazine['period'] ])->with('period')->asArray()->one();
        $magazine_ads = MagazineAds::find()->where(['magazine_id' => $id])->andWhere(['>', 'created_at', strtotime('-' . $tarif['period']['days'] . ' days')])->asArray()->all();

        $fields = MagazineEavFields::find()->where(['category_id' => $magazine['category_id']])->with('opts')->with('type')->all();


        if ($model->load(Yii::$app->request->post())) {

            if( count($magazine_ads) > $tarif['cound_ads']){
                $tarif['dop_tov'];
            }
            if($model->save()){

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
            }
            
        }

        return $this->render('create', [
            'model' => $model,
            'magazine' => $magazine,
            'fields' => $fields,
            
        ]);

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

        $tarif = MagazinePrice::find()->where(['plan_id' => $magazine['tarif_plan'] ])->andWhere(['period_id' => $magazine['period'] ])->with('period')->asArray()->one();
        $magazine_ads = MagazineAds::find()->where(['magazine_id' => $id])->andWhere(['>', 'created_at', strtotime('-' . $tarif['period']['days'] . ' days')])->asArray()->all();

        $images = $old_model->getImages();
        foreach ($images as $key) {
            $_SESSION['Image_copy_' . $old_model->id][$key->id] = $key->getPathToOrigin();
        }


        if ($model->load(Yii::$app->request->post())) {

            if($model->save()){
                if(!empty($_SESSION['Image_copy_' . $old_model->id])){
                    foreach ($_SESSION['Image_copy_' . $old_model->id] as $key => $value) {
                        // echo '<pre>';
                        // print_r($value);
                        // die;
                        $model->attachImage($value);
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'magazine' => $magazine,
            'images' => $images,
            'old_model' => $old_model,
        ]);
    }

    public function actionCopySave($id)
    {
        $old_model = MagazineAds::find()->where(['id' => $id])->one();
        $model = new MagazineAds();
        $model->scenario = MagazineAds::SCENARIO_PRODUCT;
        $model->magazine_id = $old_model->magazine_id;

        $magazine = Magazine::find()->where(['id' => $old_model->magazine_id])->asArray()->one();

        $tarif = MagazinePrice::find()->where(['plan_id' => $magazine['tarif_plan'] ])->andWhere(['period_id' => $magazine['period'] ])->with('period')->asArray()->one();
        $magazine_ads = MagazineAds::find()->where(['magazine_id' => $id])->andWhere(['>', 'created_at', strtotime('-' . $tarif['period']['days'] . ' days')])->asArray()->all();

        if ($model->load(Yii::$app->request->post())) {

            if( count($magazine_ads) > $tarif['cound_ads']){
                $tarif['dop_tov'];
            }


            if($model->save()){
                if(!empty($_SESSION['Image_copy_' . $old_model->id])){
                    // echo '<pre>';
                    // print_r($_SESSION);
                    // die;
                    foreach ($_SESSION['Image_copy_' . $old_model->id] as $key => $value) {

                        $model->attachImage($value);
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        }

        return $this->redirect(Yii::$app->request->referrer);
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

        $magazine = Magazine::find()->where(['id' => $model->magazine_id])->one();
        $fields = MagazineEavFields::find()->where(['category_id' => $magazine->category_id])->with('opts')->with('type')->all();

        $vals = MagazineEavValue::find()->where(['product_id' => $model->id])->with('field')->asArray()->all();

        $params = [];

        foreach($vals as $key){

            $params[$key['field']['name']][] = $key['value'];
        }

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
                'params' => $params
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
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
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
}
