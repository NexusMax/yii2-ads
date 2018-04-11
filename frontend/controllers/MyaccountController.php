<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use \frontend\models\Categories;
use frontend\models\LoginForm;
use frontend\models\SignupForm;

use rico\yii2images\models\Image;

use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\MyaccountChangePhone;
use frontend\models\MyaccountChangeName;
use frontend\models\AdsHasUpdate;
use frontend\models\Ads;
use frontend\models\AdsFavorites;
use frontend\models\Message;
use backend\models\Promotion;
use frontend\models\AfterDel;
use backend\models\FieldValue;
use backend\models\AdsHasImage;
use frontend\models\SearchMyaccount;

use yii\data\Sort;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;


use yii\helpers\Url;

use yii\data\ActiveDataProvider;


class MyaccountController extends Controller
{
    public $messages;


	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update', 'view', 'deleteMessage', 'profile', 'messages', 'settings', 'favorite', 'disactive', 'delete', 'active', 'updatephonecount', 'updateadcount', 'refresh', 'yii2images', 'afterdel'],
                'rules' => [
                    [
                        'actions' => ['index', 'profile', 'messages', 'settings', 'view', 'favorite', 'updatephonecount', 'updateadcount', 'refresh', 'yii2images', 'afterdel'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                       'actions' => ['update', 'deleteMessage'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           return Ads::isUserAuthor($action);
                       }
                    ],
                    [
                       'actions' => ['disactive', 'delete', 'active'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           return Ads::isUserAuthorId($action);
                       }
                   ],
               ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'deleteMessage' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if(!Yii::$app->user->isGuest){
            $my_id = Yii::$app->user->identity->id;


        // if(Yii::$app->request->isAjax)
            if ($this->action->id == 'deleteajax' || $this->action->id == 'afterdel' || $this->action->id == 'deleteimg' || $this->action->id == 'deleteimgg' || $this->action->id == 'deleteimggg' || $this->action->id == 'deleteimgggg' || $this->action->id == 'deleteimggggold')
                Yii::$app->controller->enableCsrfValidation = false;


            

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

            if($this->action->id == 'favorite'){
                Yii::$app->params['myaccount']['title'] = 'Избранные обьявления';
                Yii::$app->params['myaccount']['sub_title'] = 'Здесь доступны избранные вами объявления';
                Yii::$app->params['myaccount']['none'] = 'Сейчас у вас нет избранных объявлений';
            }else{
                Yii::$app->params['myaccount']['title'] = 'Объявления';
                Yii::$app->params['myaccount']['sub_title'] = 'Здесь вы найдёте ваши активные и архивные объявления';
                if($this->action->id == 'archive'){
                    Yii::$app->params['myaccount']['none'] = 'Сейчас у вас нет неактивных объявлений';
                    Yii::$app->params['myaccount']['inform'] = 'Неактивные объявления:';
                }
                else{
                    Yii::$app->params['myaccount']['none'] = 'Сейчас у вас нет активных объявлений';
                    Yii::$app->params['myaccount']['inform'] = 'Активные объявления:';
                }
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
            // $ads = Ads::find()->where(['user_id' => Yii::$app->user->identity->id])->asArray()->all();
            // echo '<pre>';
            // print_r(Yii::$app->params['myaccount']['cat_limit']);
            // echo '</pre>';die;

        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {	
    	$this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';    
        $sort = self::getSort();
        $search = new SearchMyaccount();

        if ($search->load(Yii::$app->request->get())) {
            $ads = Ads::find()
            	->select('jandoo_ads.*, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, dd.id as parent_parent_category_id, dd.alias as parent_parent_category_alias, dd.name as parent_parent_category_name')
            		->leftJoin('jandoo_categories as b', 'jandoo_ads.category_id = b.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = jandoo_ads.category_id) = d.id')
                    ->leftJoin('jandoo_categories as dd', 'dd.id = d.parent_id')
            	->where(['user_id' => Yii::$app->user->identity->id])
            	->andWhere(['like', 'name', $search->q])
            	->andWhere('jandoo_ads.active = 1')
            	->with('mainImg')
            	->asArray()
            	->orderBy($sort->orders)
            	->indexBy('id')
            	->all();
        }else
            $ads = Ads::find()
            	->select('jandoo_ads.*, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, dd.id as parent_parent_category_id, dd.alias as parent_parent_category_alias, dd.name as parent_parent_category_name')
            		->leftJoin('jandoo_categories as b', 'jandoo_ads.category_id = b.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = jandoo_ads.category_id) = d.id')
                    ->leftJoin('jandoo_categories as dd', 'dd.id = d.parent_id')
            	->where(['user_id' => Yii::$app->user->identity->id])
            	->andWhere('jandoo_ads.active = 1')
            	->with('mainImg')
            	->asArray()
            	->orderBy($sort->orders)
            	->indexBy('id')
            	->all();

        $ads_id = array_column($ads, 'id');
        $favorite_ads = implode(',', $ads_id);
        empty($favorite_ads) ? $favorite_ads = 0 : 0;


        $adsHasUpdate = AdsHasUpdate::find()->where(['in', 'ads_id', $favorite_ads])->andWhere(['>', 'updated_at', strtotime('-1 Month')])->asArray()->orderBy('updated_at ASC')->all();

        

        $ads_phone_view = Yii::$app->db->createCommand('SELECT ads_id, COUNT(*) as phone_view FROM jandoo_ads_phone WHERE ads_id IN ('.$favorite_ads.') GROUP BY ads_id')->queryAll();
        $favorite_view = Yii::$app->db->createCommand('SELECT ads_id, COUNT(*) as favorite_view FROM jandoo_user_has_favorites WHERE ads_id IN ('.$favorite_ads.') GROUP BY ads_id')->queryAll();


        $allPromotionOrder = Yii::$app->db->createCommand('
            SELECT * FROM (SELECT * FROM `jandoo_ads_has_images` WHERE (validity_at > ' . time() . ') AND (`ads_id` IN ('.$favorite_ads.')) ORDER BY `validity_at` DESC) t 
            GROUP BY `type`
            ORDER BY `type` DESC
            ')->queryAll();


        foreach ($adsHasUpdate as $key) {
            $ads[$key['ads_id']]['adsHasUpdate'][] = $key;
        }

        foreach ($allPromotionOrder as $key) {
            $ads[$key['ads_id']]['allPromotionOrder'][] = $key;
        }

        foreach ($ads_phone_view as $key) {
            $ads[$key['ads_id']]['phone_view'] = $key['phone_view'];
        }

        foreach ($favorite_view as $key) {
            $ads[$key['ads_id']]['favorite_view'] = $key['favorite_view'];
        }
        // echo '<pre>';
        // print_r($ads);
        // die;
    
        $ads = Ads::getTableAllAds($ads, $sort);

        return $this->render('index', [
            'ads' => $ads,
            'ads_id' => $ads_id,
            'search' => $search,
            'afterDel' => $afterDel,
        ]);
    }

    public function actionArchive()
    {   
        $this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине'; 
        $search = new SearchMyaccount();   
        $sort = self::getSort();

        if ($search->load(Yii::$app->request->get())) {
            $ads = Ads::find()
            	->select('jandoo_ads.*, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, dd.id as parent_parent_category_id, dd.alias as parent_parent_category_alias, dd.name as parent_parent_category_name')
            		->leftJoin('jandoo_categories as b', 'jandoo_ads.category_id = b.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = jandoo_ads.category_id) = d.id')
                    ->leftJoin('jandoo_categories as dd', 'dd.id = d.parent_id')
            	->where(['user_id' => Yii::$app->user->identity->id])
            	->andWhere(['like', 'name', $search->q])
            	->andWhere('jandoo_ads.active = 0')
            	->with('mainImg')
            	->asArray()
            	->orderBy($sort->orders)
            	->indexBy('id')
            	->all();
        }else
            $ads = Ads::find()
            	->select('jandoo_ads.*, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, dd.id as parent_parent_category_id, dd.alias as parent_parent_category_alias, dd.name as parent_parent_category_name')
            		->leftJoin('jandoo_categories as b', 'jandoo_ads.category_id = b.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = jandoo_ads.category_id) = d.id')
                    ->leftJoin('jandoo_categories as dd', 'dd.id = d.parent_id')
            	->where(['user_id' => Yii::$app->user->identity->id])
            	->andWhere('jandoo_ads.active = 0')
            	->with('mainImg')
            	->asArray()
            	->orderBy($sort->orders)
            	->indexBy('id')
            	->all();
        $ads_id = array_column($ads, 'id');
        $favorite_ads = implode(',', $ads_id);
        empty($favorite_ads) ? $favorite_ads = 0 : 0;

        $ads_phone_view = Yii::$app->db->createCommand('SELECT ads_id, COUNT(*) as phone_view FROM jandoo_ads_phone WHERE ads_id IN ('.$favorite_ads.') GROUP BY ads_id')->queryAll();
        $favorite_view = Yii::$app->db->createCommand('SELECT ads_id, COUNT(*) as favorite_view FROM jandoo_user_has_favorites WHERE ads_id IN ('.$favorite_ads.') GROUP BY ads_id')->queryAll();


        foreach ($ads_phone_view as $key) {
            $ads[$key['ads_id']]['phone_view'] = $key['phone_view'];
        }

        foreach ($favorite_view as $key) {
            $ads[$key['ads_id']]['favorite_view'] = $key['favorite_view'];
        }

        // echo '<pre>';
        // print_r($ads);die;
    
        $ads = Ads::getTableAllAds($ads, $sort);

        return $this->render('index', [
            'ads' => $ads,
            'ads_id' => $ads_id,
            'search' => $search,
        ]);
    }

    public function actionDelete($id, $ajax = null)
    {
        $model = Ads::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere(['id'=> $id])->limit(1)->all();

        $image = \rico\yii2images\models\Image::find()->where(['itemId' => $id])->all();

        foreach ($image as $key) {
            $str = explode('/', $key->filePath);
            $alias = Yii::getAlias('@appWeb') . '/images/store/' . $key->filePath;
            $alias_mini = Yii::getAlias('@appWeb') . '/images/store/' . $str[0] . '/' . $str[1] . '/mini_' . $str[2];
            @unlink($alias);
            @unlink($alias_mini);
        }

        $messages = Message::find()->where(['ads_id' => $id])->all();
        foreach ($messages as $key) {
            $key->delete();
        }

        $ads_has_image = AdsHasImage::find()->where(['ads_id' => $id])->all();
        $sub_field = FieldValue::find()->where(['ads_id' => $id])->one();
        if(!empty($sub_field)){
            $sub_field->delete();
        }
        if(!empty($ads_has_image)){
            foreach ($ads_has_image as $key) {
                $key->delete();
            }
        }
        
        $model->removeImages();
        $model->delete();

        if($ajax)
            echo 'Обьявление удалено.';
        else{
            Yii::$app->session->setFlash('success', 'Обьявление удалено.');
            return $this->redirect(['myaccount/archive']);
        }

    }

    public function actionAfterdel()
    {
        if(Yii::$app->request->isAjax){
            $result = Yii::$app->request->post('result');
            $afterDel = new AfterDel();
            $afterDel->user_id = Yii::$app->user->identity->id;
            $afterDel->result = $result;
            $afterDel->created_at = time();
            if($afterDel->save())
                echo 'success';
            else
                echo 'error';
        }
    }

    public function actionDeleteajax()
    {
        if(Yii::$app->request->isAjax){
            $id = intval(Yii::$app->request->post('ad'));
            return $this->actionDelete($id, true);
        }
    }

    public function actionActive($id)
    {
        $ad = Ads::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere('active = 0')->andWhere(['id' => $id])->limit(1)->one();

        $ad->active = 1;
        //$ad->validity_at = strtotime('+1 month');

        if((time() - $ad->validity_at) > strtotime('+1 month') - time())
            $ad->validity_at = strtotime('+1 month');
        
        $ad->save(false);

        Yii::$app->session->setFlash('success', 'Обьявление активировано.');

        return $this->redirect(['myaccount/index']);
    }

    public function actionDisactive($id)
    {
        $ad = Ads::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere('active = 1')->andWhere(['id' => $id])->limit(1)->one();

        $ad->active = 0;
        $ad->save(false);

        Yii::$app->session->setFlash('warning', 'Обьявление деактивировано.');
        // Yii::$app->user->setFlash('success', "The user has been deleted successfully.");

        return $this->redirect(['myaccount/index']);
    }

    public function actionFavorite()
    {

        $this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';
        

        $sort = self::getSort();

        $ads = (new \yii\db\Query())
                ->select('a.id, a.alias, a.name, a.created_at, a.price, a.location, a.type_payment')
                ->from('jandoo_ads as a')
                ->leftJoin('jandoo_user_has_favorites as b', 'a.id = b.ads_id')
                ->where(['b.user_id' => Yii::$app->user->identity->id])->andWhere('a.active = 1')->indexBy('id')->orderBy($sort->orders)->all();

        $my_id = Yii::$app->user->identity->id;
        foreach (Yii::$app->request->cookies as $key)
            if(empty($ads[$key->value]))
                if(strcmp($key->name, 'ad_' . $key->value) == 0){
                    AdsFavorites::setFavorite($key->value, $my_id);
                    $refresh = true;
                }

        if($refresh)
            return $this->refresh();

        $ads_id = array_column($ads, 'id');

        $mainImg = Image::find()->select('itemId, filePath')->where(['in', 'itemId', $ads_id])->andWhere('modelName = "Ads"')->asArray()->all();

        for($i = 0; $i < count($mainImg); $i++){
            $ads[$mainImg[$i]['itemId']]['mainImg']['filePath'] = $mainImg[$i]['filePath'];
        }


        $favorite_ads = implode(',', $ads_id);

        empty($favorite_ads) ? $favorite_ads = 0 : 0;

        $ads_phone_view = Yii::$app->db->createCommand('SELECT ads_id, COUNT(*) as phone_view FROM jandoo_ads_phone WHERE ads_id IN ('.$favorite_ads.') GROUP BY ads_id')->queryAll();
        $favorite_view = Yii::$app->db->createCommand('SELECT ads_id, COUNT(*) as favorite_view FROM jandoo_user_has_favorites WHERE ads_id IN ('.$favorite_ads.') GROUP BY ads_id')->queryAll();


        foreach ($ads_phone_view as $key) {
            $ads[$key['ads_id']]['phone_view'] = $key['phone_view'];
        }

        foreach ($favorite_view as $key) {
            $ads[$key['ads_id']]['favorite_view'] = $key['favorite_view'];
        }
    
        $ads = Ads::getTableAllAds($ads, $sort);
        
        return $this->render('index', [
            'ads' => $ads,
            'ads_id' => $ads_id,
        ]);
    }


    public function actionView($cat, $subcat)
    {
        $this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';

    	$id_messages = Yii::$app->request->get('subcat');
    	$my_id = Yii::$app->user->identity->id;


        $messages = (new \yii\db\Query())
                ->select('a.created_at, a.ads_id, a.message, a.from, a.for, a.author_ads_id, a.id_messages, a.first_message_id, a.readed_at, b.username, b.lastname')
                ->from('jandoo_message as a')
                ->leftJoin('jandoo_user as b', 'a.from = b.id')
                ->where(['a.id_messages' => $id_messages])->orderBy('a.id ASC')->all();


        if(empty($messages))
            throw new \yii\web\HttpException(404, "Такой переписки нету");

        if($messages[0]['from'] != $my_id && $messages[0]['for'] != $my_id){
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));return;
        }
        
        $up = (new \yii\db\Query())->select('a.*')->from('jandoo_message as a')->where(['a.id_messages' => $id_messages])->andWhere(['a.for' => $my_id])->orderBy('a.id ASC')->all();
        foreach ($up as $key) {
            if(!$key['unread'])
                Yii::$app->db->createCommand("UPDATE `jandoo_message` as a SET a.unread = 1, a.readed_at = " . time() . " WHERE a.id = " . $key['id'] . " AND a.for = '" . $my_id . "'")->execute();
        }
       

        $ad = Ads::find()->where(['id' => $messages[0]['ads_id']])->with('mainImg')->limit(1)->one(); 



        $new_message = new Message;


        if ($new_message->load(Yii::$app->request->post())) {


            ($messages[0]['author_ads_id'] == $my_id) ? $new_message->for = $messages[0]['from'] : $new_message->for = $messages[0]['for'];
            ($messages[0]['author_ads_id'] == $my_id) ? $new_message->from = $my_id : $new_message->from = $messages[0]['from'];

        	$new_message->author_ads_id = $messages[0]['author_ads_id'];
        	$new_message->ads_id = $messages[0]['ads_id'];
            $new_message->id_messages = $id_messages;
            $new_message->first_message_id = $messages[0]['first_message_id'];
            $new_message->unread = 0;

            // echo '<pre>';
            // print_r($new_message);
            // die;


    		if($new_message->save()){
    			Yii::$app->session->setFlash('success', 'Сообщение отправлено');
                return $this->refresh();
    		}
        }
    	
    	return $this->render('view', [
    		'messages' => $messages,
    		'my_id' => $my_id,
            'ad' => $ad,
    		'new_message' => $new_message,
    	]);
    }

    public function actionProfile()
    {
    	$ads = Ads::find()->where(['user_id' => Yii::$app->user->id])->asArray()->all();
        $ads_ids = array_column($ads, 'id');

        $promotionName = Promotion::getNamePromo();
        $packageName = Promotion::getNamePackage();

        $dataProvider = new ActiveDataProvider([
            'query' => AdsHasImage::find()->orderBy('id DESC')->with('ads')->with('package')->asArray()->where(['in', 'ads_id', $ads_ids]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $promotion = $dataProvider->getModels();

        // echo '<pre>';
        // print_r($promotion);
        // die;


    	return $this->render('profile', [
            'ads_ids' => $ads_ids,
            'dataProvider' => $dataProvider,
            'promotionName' => $promotionName,
            'packageName' => $packageName,
        ]);
    }

    public function actionMessages()
    {
        $my_id = Yii::$app->user->identity->id;


        $my_messages = (new \yii\db\Query())
                ->select('a.ads_id, a.magazine_id, max(a.created_at) as created_at, a.id_messages, a.unread, b.username, b.lastname, c.name, c.alias')
                ->from('jandoo_message as a')
                ->leftJoin('jandoo_user as b', '(SELECT DISTINCT g.from FROM jandoo_message as g WHERE g.author_ads_id = '.$my_id.' AND g.first_message_id != '.$my_id.' ORDER BY id ASC LIMIT 1) = b.id')
                ->leftJoin('jandoo_ads as c', 'a.ads_id = c.id')
                ->where(['a.author_ads_id' => $my_id])->andWhere(['<>', 'first_message_id', $my_id])->orderBy('a.id DESC')->groupBy('a.author_ads_id, a.ads_id, a.id_messages')->all();


        $send_messages = (new \yii\db\Query())
                ->select('a.ads_id, a.magazine_id, a.id, max(a.created_at) as created_at, a.id_messages, a.unread, b.username, b.lastname, c.name, c.alias')
                ->from('jandoo_message as a')
                ->leftJoin('jandoo_user as b', 'a.author_ads_id = b.id')
                ->leftJoin('jandoo_ads as c', 'a.ads_id = c.id')
                ->where(['first_message_id' => $my_id])->orderBy('a.id DESC')->groupBy('a.author_ads_id, a.ads_id, a.id_messages')->all();


        $my_messages_magazine = (new \yii\db\Query())
                ->select('a.ads_id, a.magazine_id, max(a.created_at) as created_at, a.id_messages, a.unread, b.username, b.lastname, c.name, c.alias, gg.name as magazine_name')
                ->from('jandoo_message as a')
                ->leftJoin('jandoo_user as b', '(SELECT DISTINCT g.from FROM jandoo_message as g WHERE g.author_ads_id = '.$my_id.' AND g.first_message_id != '.$my_id.' ORDER BY id ASC LIMIT 1) = b.id')
                ->leftJoin('jandoo_magazine_ads as c', 'a.ads_id = c.id')
                ->leftJoin('jandoo_magazine as gg', 'gg.id = c.magazine_id')
                ->where(['a.author_ads_id' => $my_id])
                ->andWhere(['<>', 'first_message_id', $my_id])
                ->orderBy('a.id DESC')
                ->groupBy('a.author_ads_id, a.ads_id, a.id_messages')
                ->indexBy('id_messages')
                ->all();


        $send_messages_magazine = (new \yii\db\Query())
                ->select('a.ads_id, a.magazine_id, a.id, max(a.created_at) as created_at, a.id_messages, a.unread, b.username, b.lastname, c.name, c.alias, gg.name as magazine_name')
                ->from('jandoo_message as a')
                ->leftJoin('jandoo_user as b', 'a.author_ads_id = b.id')
                ->leftJoin('jandoo_magazine_ads as c', 'a.ads_id = c.id')
                ->leftJoin('jandoo_magazine as gg', 'gg.id = c.magazine_id')
                ->where(['first_message_id' => $my_id])
                ->andWhere('c.magazine_id IS NOT NULL')
                ->orderBy('a.id DESC')
                ->groupBy('a.author_ads_id, a.ads_id, a.id_messages')
                ->indexBy('id_messages')
                ->all();


        for($i = 0; $i < count($send_messages); $i++){
            if(!empty($send_messages_magazine[$send_messages[$i]['id_messages']])){
                $send_messages[$i] = $send_messages_magazine[$send_messages[$i]['id_messages']];
            }
        }

        for($i = 0; $i < count($my_messages); $i++){
            if(!empty($my_messages_magazine[$my_messages[$i]['id_messages']])){
                $my_messages[$i] = $my_messages_magazine[$my_messages[$i]['id_messages']];
            }
        }


    	
    	return $this->render('message', [
            'my_messages' => $my_messages,
            'send_messages' => $send_messages,
        ]);
    }

    public function actionDeleteMessage($id)
    {
        $messages = Message::find()->where(['id_messages' =>$id ])->all();

        foreach ($messages as $key) {
            $key->delete();
        }

        Yii::$app->session->setFlash('success', 'Переписка удалено.');

        return $this->redirect(['myaccount/messages']);
    }


    public function actionUpdatephonecount()
    {
        if(Yii::$app->request->isAjax){
            $ad_id = Yii::$app->request->post('ads_id');
            $ad = Ads::find()->where(['id' => $ad_id])->andWhere(['user_id' => Yii::$app->user->identity->id])->limit(1)->one();
            Yii::$app->db->createCommand('DELETE FROM jandoo_ads_phone WHERE ads_id = :ads_id')->bindValue(':ads_id', $ad_id)->execute();
            if(!empty($ad)){
                $ad->number_views = 0;
                return $ad->save(false);
            }
            
            return false;
        }
        return $this->redirect(['index']);
    }

    public function actionUpdateadcount()
    {
        if(Yii::$app->request->isAjax){
            $ad_id = Yii::$app->request->post('ads_id');
            $ad = Ads::find()->where(['id' => $ad_id])->andWhere(['user_id' => Yii::$app->user->identity->id])->limit(1)->one();
            if(!empty($ad)){
                $ad->views = 0;
                return $ad->save(false);
            }
            return false;
        }
        return $this->redirect(['index']);
    }

    public function actionUpdatephonecountt()
    {
        if(Yii::$app->request->isAjax){
            $ad_id = Yii::$app->request->post('ads_id');
            $ad = \frontend\models\MagazineAds::find()->where(['id' => $ad_id])->andWhere(['user_id' => Yii::$app->user->identity->id])->limit(1)->one();
            if(!empty($ad)){
                $ad->number_views = 0;
                return $ad->save(false);
            }
            return false;
        }
        return $this->redirect(['index']);
    }

    public function actionUpdateadcountt()
    {
        if(Yii::$app->request->isAjax){
            $ad_id = Yii::$app->request->post('ads_id');
            $ad = \frontend\models\MagazineAds::find()->where(['id' => $ad_id])->andWhere(['user_id' => Yii::$app->user->identity->id])->limit(1)->one();
            if(!empty($ad)){
                $ad->views = 0;
                return $ad->save(false);
            }
            return false;
        }
        return $this->redirect(['index']);
    }

    public function actionRefresh($id)
    {
        $ad = Ads::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere('active = 1')->andWhere(['id' => $id])->limit(1)->one();

        $refreshed_ads = AdsHasUpdate::getCurrentRefresh($id);

        if(empty($refreshed_ads)){
            $refresh_ad = new AdsHasUpdate();
            $refresh_ad->SaveRef($id);
            $ad->validity_at = strtotime('+1 month');
            $ad->save(false);
            Yii::$app->session->setFlash('success', '<strong>Обьявление поднято.</strong> У вас осталось еще 1 бесплатное поднятие в этом месяце!');

            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo(Yii::$app->user->identity->email)
                ->setSubject( 'Jandooo - рекламирование объявления')
                ->setTextBody('<strong>Обьявление поднято.</strong> У вас осталось еще 1 бесплатное поднятие в этом месяце!')
                ->send();
        }elseif(count($refreshed_ads) < 2){


            $refresh_ad = new AdsHasUpdate();
            $refresh_ad->SaveRef($id);
            $ad->validity_at = strtotime('+1 month');
            $ad->save(false);
            Yii::$app->session->setFlash('success', '<strong>Обьявление поднято.</strong> Вы больше не можете поднимать обьявления в этом месяце!');

            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo(Yii::$app->user->identity->email)
                ->setSubject( 'Jandooo - рекламирование объявления')
                ->setTextBody('<strong>Обьявление поднято.</strong> Вы больше не можете поднимать обьявления в этом месяце!')
                ->send();

        }elseif(count($refreshed_ads) >= 2){
            Yii::$app->session->setFlash('warning', '<strong>Обьявление можно поднимать только 2 раза в месяц.</strong> Последнее обновление: <strong>' . Yii::$app->formatter->asDate($refreshed_ads[count($refreshed_ads)-1]['updated_at'], 'php:d M в H:s') . '</strong>');
        }

        $referer_url = parse_url($_SERVER['HTTP_REFERER']);
        $url = explode('/', $referer_url['path']);
        if(strcmp($url[1], 'ads') == 0)
            return $this->redirect($_SERVER['HTTP_REFERER']);
        return $this->redirect(['myaccount/index']);
    }

    protected static function getSort()
    {
        $sort = new Sort([
            'defaultOrder' => ['id' => SORT_DESC],
            'attributes' => [
                'name' => [
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Заголовок',
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
                ],
            ],
        ]);
        return $sort;
    }


    public function actionSettings()
    {
    	$this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';
        $user = \common\models\User::find()->where(['id' => Yii::$app->user->id])->one();
        // print_r($user);die;
    	$updatePassword = new \frontend\models\MyaccountChangePassword;
    	if ($updatePassword->load(Yii::$app->request->post())) {
    		if($updatePassword->savePassword()){
    			Yii::$app->session->setFlash('success', 'Пароль успешно изменен.');
                return $this->redirect(['myaccount/settings']);
    		}
        }

        $updatePhone = new \frontend\models\MyaccountChangePhone;
        if ($updatePhone->load(Yii::$app->request->post())) {
    		if($updatePhone->savePhone()){
    			Yii::$app->session->setFlash('success', 'Телефон успешно изменен.');
                return $this->redirect(['myaccount/settings']);
    		}
        }

        $deleteAccount = new \frontend\models\MyaccountDeleteAccount;
        if ($deleteAccount->load(Yii::$app->request->post())) {
    		if($deleteAccount->deleteAccount()){
    			//Yii::$app->session->setFlash('success', 'Аккаунт удален.');
    			$this->goHome();
    		}
        }

        $userName = new \frontend\models\MyaccountChangeName;
        if ($userName->load(Yii::$app->request->post())) {
            if($userName->save()){
                Yii::$app->session->setFlash('success', 'Имя и фамилия изменены.');
                return $this->redirect(['myaccount/settings']);
            }
        }

        $updatePhoto = new \frontend\models\MyaccountUpdatePhoto;
        if ($updatePhoto->load(Yii::$app->request->post())) {
            if($updatePhoto->save()){
                Yii::$app->session->setFlash('success', 'Фото изменено.');
                return $this->redirect(['myaccount/settings']);
            }
        }


    	return $this->render('settings', [
            'currentImg' => $userImg,
            'userName' => $userName,
    		'updatePassword' => $updatePassword,
        	'updatePhone' => $updatePhone,
        	'deleteAccount' => $deleteAccount,
            'updatePhoto' => $updatePhoto,
        ]);
    }

    public function actionDeleteimg()
    {
        if(Yii::$app->request->isAjax){
            $img_id = Yii::$app->request->post('img_id');
           
            $model = \common\models\User::find()->where(['id' => Yii::$app->user->identity->id])->one();
            $images = $model->getImages();

            foreach($images as $image)
                if($image->id == $img_id)
                    $model->removeImage($image);
        }
    }

    public function actionDeleteimgg()
    {
        if(Yii::$app->request->isAjax){
            $img_id = Yii::$app->request->post('img_id');

            $model_id = Yii::$app->request->post('model_id');
           
            $model = \frontend\models\Magazine::find()->where(['id' => $model_id])->one();
            $images = $model->getImages();
            
            foreach($images as $image)
                if($image->id == $img_id)
                    $model->removeImage($image);
        }
    }

    public function actionDeleteimggg()
    {
        if(Yii::$app->request->isAjax){
            $img_id = Yii::$app->request->post('img_id');

            $model_id = Yii::$app->request->post('model_id');
           
            $model = \frontend\models\MagazineHasCategories::find()->where(['id' => $model_id])->one();
            $images = $model->getImages();

            foreach($images as $image)
                if($image->id == $img_id)
                    $model->removeImage($image);
        }
    }


    public function actionDeleteimgggg()
    {
        if(Yii::$app->request->isAjax){
            $img_id = Yii::$app->request->post('img_id');

            $model_id = Yii::$app->request->post('model_id');
           
            $model = \frontend\models\MagazineAds::find()->where(['id' => $model_id])->one();
            $images = $model->getImages();

            foreach($images as $image)
                if($image->id == $img_id)
                    $model->removeImage($image);
        }
    }

    
    public function actionDeleteimggggold()
    {
        if(Yii::$app->request->isAjax){
            $img_id = Yii::$app->request->post('img_id');
            $model_id = Yii::$app->request->post('model_id');
            $old = Yii::$app->request->post('old');
           
            unset($_SESSION['Image_copy_' . $old][$img_id]);
        }
    }

}
