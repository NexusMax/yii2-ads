<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use \frontend\models\Categories;
use \frontend\models\Ads;
use \frontend\models\MagazineAds;
use \frontend\models\Promotion;
use \frontend\models\UserStatus;
use \frontend\models\Message;
use yii\filters\AccessControl;
use frontend\models\AdsFavorites;
use yii\helpers\Url;

use common\models\User;

class AdsController extends Controller
{ 

    public $ads_id_update;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Ads::isUserAuthor();
                        }
                    ]
                ],
            ],
        ];
    }

	public function beforeAction($action)
    {      
        if ($this->action->id == 'create' || $this->action->id == 'message' || $this->action->id == 'countphone' || $this->action->id == 'favoritedelete' || $this->action->id == 'favorite' || $this->action->id == 'selectcity' || $this->action->id == 'userstatus' || $this->action->id == 'userstatusdel') {
            Yii::$app->controller->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);

    }

    public function actionCreate()
    {   
        $this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';

        if(!Yii::$app->user->isGuest){
        	$user = Yii::$app->user->identity;
        }

        $reg = Yii::$app->db->createCommand('Select id, db_rootid, db_defnamelang as name from jandoo_vd_region')->queryAll();
        // echo '<pre>';
        // print_r($reg);
        // die;

        $ads = (new \yii\db\Query())
                    ->select('a.id, a.name, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, dd.id as parent_parent_category_id, dd.alias as parent_parent_category_alias, dd.name as parent_parent_category_name, count(d.id) as count')
                    ->from('jandoo_ads as a')
                    ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
                    ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
                    ->leftJoin('jandoo_categories as dd', 'dd.id = d.parent_id')
                    ->where('a.user_id =  ' . Yii::$app->user->identity->id )->groupBy('d.name')->all();


        
        foreach ($ads as $key) {
            if(empty($key['parent_parent_category_alias']))
                Yii::$app->params['myaccount']['cat_limit'][$key['parent_category_id']]['count'] = $key['count'];
            else
                Yii::$app->params['myaccount']['cat_limit'][$key['parent_parent_category_id']]['count'] += $key['count'];
        }

        // echo '<pre>';
        // print_r(Yii::$app->params['myaccount']['cat_limit']);die;

        $categories = ['0'=> ['id' => '', 'name' => 'Выберите категорию']];
        $categories += Categories::find()->select('id, name')->where('active = 1')->andWhere(['parent_id' => 0])->asArray()->indexBy('id')->orderBy('sort ASC')->all();

        $model = new Ads;

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                $model->alias = $model->str2url2($model->name, $model->id);
                $model->save(false);
                return $this->redirect(['myaccount/index']);
            }
        }

        return $this->render('create', [
            'reg' => $reg,
            'categories' => $categories,
            'model' => $model,
            'user' => $user,
        ]);
    }

    public function actionGetCity()
    {
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            $city = Yii::$app->db->createCommand('Select id, db_rootid, db_defnamelang as name from jandoo_vd_city WHERE db_rootid = ' . intval($id))->queryAll();
            $options = '';
            foreach ($city as $key) {
               $options .= '<option value="' . $key['id'] . '">' . $key['name'] . '</option>';
            }
            return $options;
        }
    }

    public function actionReklama($alias)
    {   
        $this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';

        $ad = (new \yii\db\Query())
            ->select('a.*, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, dd.id as parent_parent_category_id, dd.alias as parent_parent_category_alias, dd.name as parent_parent_category_name, count(d.id) as count')
            ->from('jandoo_ads as a')
            ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
            ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
            ->leftJoin('jandoo_categories as dd', 'dd.id = d.parent_id')
            ->where('a.user_id =  ' . Yii::$app->user->identity->id )->andWhere(['a.alias' => $alias])->one();



        if(empty($ad['parent_parent_category_id']))
            $parent_category_id = $ad['parent_category_id'];
        else
            $parent_category_id = $ad['parent_parent_category_id'];

        $model = new Promotion();
        $model->ads_id = $ad['id'];

        if($parent_category_id == 15 || $parent_category_id == 17 || $parent_category_id == 28){
            $current_course = $model->getPackageList();
            $model->setPackagePrice($current_course['start'] + 10, $current_course['medium'] + 10, $current_course['full'] + 10);
        }
        $current_course = $model->getPackageList();
        $current_price = $model->getSelectList();


        if ($model->load(Yii::$app->request->post())) {


          if($model->getTotalPrice() < Yii::$app->user->identity->balance){
                $transaction = Yii::$app->db->beginTransaction();

                try{
                    if($model->save()){

                        Yii::$app->session->setFlash('success', $model->message);

                        Yii::$app->mailer->compose()
                            ->setFrom(Yii::$app->params['adminEmail'])
                            ->setTo(Yii::$app->user->identity->email)
                            ->setSubject( 'Jandooo - рекламирование объявления')
                            ->setTextBody($model->sms_message)
                            ->send();

                        $user = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
                        $user->balance = $user->balance - $model->getTotalPrice();
                        $user->save(false);

                        $transaction->commit();
                        return $this->redirect(['myaccount/index']);
                    }
                }
                catch (Exception $e) {
                    $transaction->rollback();
                    // Yii::log("Error occurred while saving artist or its 'songs'. Rolling back... . Failure reason as reported in exception: " . $e->getMessage(), CLogger::LEVEL_ERROR, __METHOD__);
                    return false;
                }
           }else{
               Yii::$app->session->setFlash('danger', 'На Вашем счёте недостаточно средств. <strong><a href="#">Пополнить</a></strong>');
           }
                
        }





        return $this->render('page1', [
            'model' => $model,
            'current_course' => $current_course,
            'current_price' => $current_price,
            'parent_category_id' => $parent_category_id,
            'ad' => $ad
        ]);
    }

    public function actionPaket($alias)
    {   
        $this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';
        
        
        $ad = (new \yii\db\Query())
            ->select('a.*, b.name as category_name, b.alias as category_alias, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, dd.id as parent_parent_category_id, dd.alias as parent_parent_category_alias, dd.name as parent_parent_category_name, count(d.id) as count')
            ->from('jandoo_ads as a')
            ->leftJoin('jandoo_categories as b', 'a.category_id = b.id')
            ->leftJoin('jandoo_categories as d', '(SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id')
            ->leftJoin('jandoo_categories as dd', 'dd.id = d.parent_id')
            ->where('a.user_id =  ' . Yii::$app->user->identity->id )->andWhere(['a.alias' => $alias])->one();



        return $this->render('page2', [
            'ad' => $ad
        ]);
    }

    public function actionUpdate($alias)
    {
        $this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';


        if(!Yii::$app->user->isGuest){
            $user = Yii::$app->user->identity;
        }


        $model = Ads::find()->where(['alias' => $alias])->one();
        $model->text = strip_tags($model->text);

        $reg = Yii::$app->db->createCommand('Select id, db_rootid, db_defnamelang as name from jandoo_vd_region')->queryAll();
        if(!empty($model->reg_id)){
        $city = Yii::$app->db->createCommand('Select id, db_rootid, db_defnamelang as name from jandoo_vd_city WHERE db_rootid=' . $model->reg_id)->queryAll();}

        $this->ads_id_update = $model->id;

        if ($model->load(Yii::$app->request->post())) {

            if($model->save()){
                $model->alias = $model->str2url2($model->name, $model->id);
                $model->save();
                Yii::$app->session->setFlash('success', 'Объявление успешно отредактировано!');
                return $this->redirect(['myaccount/index']);
            }
        }


        $categories = ['0'=> ['id' => '', 'name' => 'Выберите категорию']];
        $child_cat = Categories::find()->select('id, name, parent_id')->where('active = 1')->andWhere(['id' => $model->category_id])->asArray()->indexBy('id')->one();

        $childs_cat = Categories::find()->select('id, name, parent_id')->where('active = 1')->andWhere(['parent_id' => $child_cat['parent_id']])->asArray()->indexBy('id')->all();

        $childs_childs_cat = (new \yii\db\Query())
            ->select('id, name, parent_id')
            ->from('jandoo_categories')
            ->where('parent_id = (SELECT parent_id FROM jandoo_categories WHERE id = "'.$child_cat['parent_id'].'") AND active = 1')->indexBy('id')->all();


        $main_cat = Categories::find()->select('id, name')->where('active = 1')->andWhere(['parent_id' => 0])->asArray()->indexBy('id')->orderBy('sort ASC')->all();
        $categories += $main_cat;

        // echo '<pre>';
        // print_r($child_cat); die;
        
        if(current($childs_childs_cat)['parent_id']){
            $current = $childs_childs_cat;
            $childs_childs_cat = $childs_cat;
            $childs_cat = $current;
            $child_cat['parent_id'] = current($childs_cat)['parent_id'];
        }else{
            $childs_childs_cat = false;
        }


        if(!$model->isNewRecord){
            $images = $model->getImages();
            if($images[0]->urlAlias == 'placeHolder')
                $images = 0;
        }


        $startSubField = $this->actionSubfields($model->category_id, $model->id);
        // echo '<pre>';print_r($childs_childs_cat);die;
        return $this->render('update', [
            'categories' => $categories,
            'model' => $model,
            'user' => $user,
            'parent_child' => $child_cat['parent_id'],
            'childs_childs_cat' => $childs_childs_cat,
            'childs_cat' => $childs_cat,
            'startSubField' => $startSubField,
            'images' => $images,
            'city'=> $city,
            'reg' => $reg,
        ]);
    }

    public function actionSubcategory()
    {

    	if(Yii::$app->request->isAjax){
    		$category_id = Yii::$app->request->post('category_id');

    		if(!is_numeric($category_id))
    			$category_id = -1;
    		
    		$categories = Categories::find()->where('active = 1')->andWhere(['parent_id' => $category_id])->asArray()->indexBy('id')->orderBy('sort ASC')->all();

    		if($category_id == -1 || empty($categories)) return '<option value="">Выберите категорию</option>';

        	return Categories::getHtmlOptionCat($categories);
    	}
    	return false;
    }


    public function actionSubfields($category_id = null, $id = null, $search = null)
    {
        if(Yii::$app->request->isAjax){
            $category_id = Yii::$app->request->post('category_id');
            $id = Yii::$app->request->post('ads_id');
            $search = Yii::$app->request->post('search');
        }

        if(!is_numeric($category_id))
            $category_id = -1;

        if(!empty($search))
            $sub_fields = \backend\models\Fields::find()->indexBy('id')->where(['like', 'category_id', ':"-1"'])->orWhere(['like', 'category_id', ':"'.$category_id.'"'])->andWhere('search = 1')->asArray()->all();
        else
            $sub_fields = \backend\models\Fields::find()->indexBy('id')->where(['like', 'category_id', ':"-1"'])->orWhere(['like', 'category_id', ':"'.$category_id.'"'])->asArray()->all();

        $values_sub_fields = null;
        if($id !== null){
            $values_sub_fields = \backend\models\FieldValue::find()->where(['ads_id' => $id])->asArray()->one();
            $values_sub_fields = json_decode($values_sub_fields['value_sub_field'], true);
        }
        if(Yii::$app->request->isAjax)
            if(!empty($search))
                print_r(Ads::renderSubFields($sub_fields, $values_sub_fields, 1));
            else
                print_r(Ads::renderSubFields($sub_fields, $values_sub_fields));
        else 
            return Ads::renderSubFields($sub_fields, $values_sub_fields);
    }
  

    public function actionView($alias)
    {
        $this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';


        $ad = Yii::$app->db->createCommand('
            SELECT a.*, b.name as category_name, b.alias as category_alias, c.username, c.created_at as user_create, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id, e.value_sub_field
            FROM jandoo_ads as a 
            LEFT JOIN jandoo_categories as b ON a.category_id = b.id 
            LEFT JOIN jandoo_user as c ON a.user_id = c.id
            LEFT JOIN jandoo_categories as d ON (SELECT parent_id FROM jandoo_categories WHERE id = a.category_id) = d.id
            LEFT JOIN jandoo_field_value as e ON e.ads_id = a.id
            WHERE a.alias = :alias')->bindValue(':alias', $alias)->queryOne();


        // $userStatusModel = new UserStatus();
        $userStatus = UserStatus::find()->where(['author_id' => $ad['user_id']])->asArray()->with('user')->all();
        // echo '<pre>';
        // print_r($userStatus);
        // echo '</pre>';die;
        $comments = new Message;

        if ($comments->load(Yii::$app->request->post())) {
        	$comments->from = Yii::$app->user->identity->id;

            $comments->ads_id = $ad['id'];
            $comments->first_message_id = Yii::$app->user->identity->id;
            $comments->author_ads_id = $ad['user_id'];
            $comments->for = $comments->author_ads_id;
            $comments->id_messages = $comments->author_ads_id . Yii::$app->user->identity->id . $comments->ads_id;
            
            if($comments->save()){
                Yii::$app->session->setFlash('success', 'Сообщение отправлено.');
                return $this->refresh();
            }
        }

		if (empty($ad))
            throw new \yii\web\HttpException(404, "Обьявление не найдено");
 

        Yii::$app->db->createCommand('UPDATE `jandoo_ads` SET `views` = :views WHERE `id` = :id')->bindValue(':views', $ad['views']+1)->bindValue(':id', $ad['id'])->execute();
        $course = \frontend\models\CourseMoney::getCurrentCourse();
        

        // echo '<pre>';
        // print_r($course);die;

        $images = \rico\yii2images\models\Image::find()->where(['itemId' => $ad['id']])->andWhere('modelName = "Ads"')->asArray()->all();


        $sub_fields = \backend\models\Fields::find()->where(['like', 'category_id', ':"-1"'])->orWhere(['like', 'category_id', ':"'.$ad['category_id'].'"'])->asArray()->all();
        $values_sub_fields = json_decode($ad['value_sub_field'], true);
        $sub_fields = Ads::listSubFields($sub_fields, $values_sub_fields);


        $user_ads = Ads::getUserAds($ad['user_id'], $ad['id']);
        $like_ads = Ads::getLikeAds($ad['id'], $ad['category_id'], $ad['parent_category_id']);


        $user = \common\models\User::find()->where(['id' => $ad['user_id']])->limit(1)->one();
        
        $_ad = Ads::find()->where(['alias' => $alias])->one();

        Yii::$app->view->registerMetaTag([
            'name' => 'og:image',
            'content' => Url::to($_ad->image->getUrl(), true)
        ]);

        return $this->render('view', [
        	'ad' => $ad,
            'course' => $course,
        	'sub_fields' => $sub_fields,
            'images' => $images,
            'user_ads' => $user_ads,
            'like_ads' => $like_ads,
            'comments' => $comments,
            'user' => $user,
            // 'userStatusModel' => $userStatusModel,
            'userStatus' => $userStatus,
            '_ad' => $_ad,
        ]);
    }

    public function actionUserstatus()
    {
        if(Yii::$app->request->isAjax){
            parse_str(Yii::$app->request->post('data'), $data);
            $userStatus = new UserStatus();

            $userStatus->author_id = $data['author_id'];
            $userStatus->user_id = Yii::$app->user->identity->id;
            $userStatus->created_at = time();
            $userStatus->text = $data['text'];
            if($userStatus->save()){
                echo json_encode([Yii::$app->formatter->asDate(time(), 'php:d M H:i'), Yii::$app->user->identity->id]);
            }
            else
                echo 'error';
        }
    }

    public function actionUserstatusdel()
    {
         if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');

            $stat = UserStatus::find()->where(['id' => $id])->limit(1)->one();
            $stat->delete();

            if($stat->delete()){
                echo 'success';
            }
            else
                echo 'error';
        }
    }

    public function actionDeleteImage()
    {
        if(Yii::$app->request->isAjax){
            $image_id = Yii::$app->request->post('image_id');
            $ads_id = Yii::$app->request->post('ads_id');

            $ads = Ads::find()->where(['id' => $ads_id])->one();

            foreach ($ads->getImages() as $key) {
                if($key->id == $image_id){

                    $str = explode('/', $key->filePath);
                    $alias_mini = Yii::getAlias('@appWeb') . '/images/store/' . $str[0] . '/' . $str[1] . '/mini_' . $str[2];
                    @unlink($alias_mini);

                    $ads->removeImage($key);
                }
            }

            return 'success';
        }
    }


    public function actionFavorite()
    {


        $app = Yii::$app;
        if($app->request->isAjax){
            $ads_id = Yii::$app->request->post('ads_id');

            // print_r($ads_id);die;

            if(!$app->user->isGuest){
                $user_id = $app->user->identity->id;
                if(strcmp($app->request->cookies->getValue('ad_' . $ads_id, 'en'), $ads_id) !== 0){

                    AdsFavorites::setFavorite($ads_id, $user_id);
                    $app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'ad_' . $ads_id,
                        'value' => $ads_id,
                    ]));
                }
            }else{
                $app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'ad_' . $ads_id,
                    'value' => $ads_id,
                ]));
            }
        }
    }


    public function actionFavoritedelete()
    {
        $app = Yii::$app;
        if($app->request->isAjax){
            $ads_id = $app->request->post('ads_id');

            if(!$app->user->isGuest){
                $user_id = $app->user->identity->id;
                AdsFavorites::deleteFavorite($ads_id, $user_id);
                $app->response->cookies->remove('ad_' . $ads_id);
            }else{
                $app->response->cookies->remove('ad_' . $ads_id);
            }
        }
    }


    public function actionMessage()
    {
        $app = Yii::$app;
        if($app->request->isAjax){

            $my_id = $app->user->identity->id;
            $ads_id = $app->request->post('ads_id');
            $magazine_id = $app->request->post('magazine_id');

            if(!empty($magazine_id)){
                $author_id = MagazineAds::find()->select('user_id')->where(['id' => $ads_id])->asArray()->limit(1)->one()['user_id'];
            }else{
                $author_id = Ads::find()->select('user_id')->where(['id' => $ads_id])->asArray()->limit(1)->one()['user_id'];
            }
            
            $message = $app->request->post('message');

            Message::sendMessage($ads_id, $message, $author_id, $my_id, $magazine_id);

            echo $author_id . $my_id . $ads_id;
        }
    }

    public function actionCountphone()
    {	
    	$app = Yii::$app;
    	$ip = $_SERVER['REMOTE_ADDR'];
    	
    	if($app->request->isAjax){
			$ads_id = $app->request->post('ads_id');

			//$view = Yii::$app->db->createCommand('
           // SELECT * FROM jandoo_ads_phone WHERE ads_id = :ads_id AND ip LIKE "%'.$ip.'%"')->bindValue(':ads_id', $ads_id)->queryall();

			//if(empty($view)){
				Yii::$app->db->createCommand('INSERT INTO jandoo_ads_phone (ads_id, ip) VALUES(:ads_id, :ip)')->bindValue(':ads_id', $ads_id)->bindValue(':ip', $ip)->execute();
			//}
    	}
    }


    public function actionSelectcity()
    {
        $app = Yii::$app;
        if($app->request->isAjax){

            $city = $app->request->post('city');

            $city = (new \yii\db\Query())
                ->select('a.db_defnamelang as city, a.id as city_id, a.db_rootid as reg_id, b.db_defnamelang as region')
                ->from('jandoo_vd_city as a')
                ->leftJoin('jandoo_vd_region as b', 'a.db_rootid = b.id')
                ->where(['like', 'a.db_defnamelang', $city])->limit(5)->all();

            echo json_encode($city);
        }

    }

    public function actionPreview()
    {
        if(Yii::$app->request->isAjax){
            $ad = json_decode(Yii::$app->request->post('ad'), true);

            echo json_encode(Ads::getPreview($ad));
        }
    }

}
