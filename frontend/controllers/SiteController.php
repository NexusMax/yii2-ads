<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use \frontend\models\Categories;
use \frontend\models\Ads;
use frontend\models\LoginForm;
use frontend\models\SignupForm;
use backend\models\Blog;
use backend\models\Pages;
use backend\models\Stock;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\MagazineCategory;

use frontend\models\AdsFavorites;

use frontend\models\Search;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;
use yii\helpers\Html;


class SiteController extends Controller
{


    public function beforeAction($action)
    {      
        if ($this->action->id == 'login-social' || $this->action->id == 'adminmessage') {
            Yii::$app->controller->enableCsrfValidation = false;
        }


        return parent::beforeAction($action);

    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {	
    	$this->view->title = 'Доска бесплатных объявлений Jandoo - частные объявления в Украине';
        $this->view->registerMetaTag(['name' => 'title', 'content' => 'Доска бесплатных объявлений Jandoo - частные объявления в Украине']);
        $this->view->registerMetaTag(['name' => 'description', 'content' => 'Портал объявлений Jandooo ➜ Размещайте объявления купли/продажи товаров, услуг, недвижимости, работы и транспорта бесплатно!']);

        $categories = Categories::getDb()->cache(function ($db) {
            return Categories::find()->where('active = 1')->asArray()->indexBy('id')->orderBy('sort ASC')->all();
        }, 600);
        

        $categories_cache = Yii::$app->cache->get('categories');
        if($categories_cache === false){
            $categories = Categories::getHtmlCategories($categories);
            Yii::$app->cache->set('categories', $categories, 600);
        }else $categories = $categories_cache;

        
        $vip_ads = Ads::getAds(15, 1);
        shuffle($vip_ads);
        // echo '<pre>';
        // print_r();
        // die;
        $vip_ads = Ads::getFullHtmlProducts($vip_ads, 5);
        $last_ads = Ads::getAds(15);
        $last_ads = Ads::getFullHtmlProducts($last_ads, 5);
        
        $reg_ads = Ads::getRegs();

        $blogs = Blog::find()->where('active = 1')->orderBy('id ASC')->asArray()->limit(3)->all();

        $magazineCategories = MagazineCategory::find()->where('active = 1')->limit(10)->asArray()->all();
        
        $search = new Search();
        if($search->load(Yii::$app->request->post()) && $search->validate()){

            $q = strtr(Html::encode($search->q), ['+' => '-', ' ' => '-']);
            $city = strtr(Html::encode($search->city), ['+' => '-', ' ' => '-']);

            // echo '<pre>';print_r($q);die;
            if(empty($city))
                return $this->redirect(['category/view', 'cat' => 'q-' . $q]);
            
            if(empty($q))
            	return $this->redirect(['category/view', 'cat' => 'c-' . $city]);
            
            return $this->redirect(['category/view', 'cat' => 'q-' . $q, 'subcat' => 'c-' . $city]);
        }


        return $this->render('index', [
        	'categories' => $categories,
            'last_ads' => $last_ads,
            'vip_ads' => $vip_ads,
            'reg_ads' => $reg_ads,
            'search' => $search,
            'blogs' => $blogs,
            'magazineCategories' => $magazineCategories,
        ]);
    }


    public function actionSettings()
    {
        return $this->render('settings');
    }

    public function actionLogin()
    {
    	$this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';

    	if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $signup = new SignupForm();
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('success', 'Вы успешно авторизировались.');
            return $this->redirect(['myaccount/index']);
        }elseif ($signup->load(Yii::$app->request->post())) {
         
            if ($user = $signup->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    Yii::$app->session->setFlash('success', 'Вы успешно зарегистрировались.');
                    return $this->redirect(['myaccount/index']);
                }
            }
        }else {
        	return $this->render('login', [
                'model' => $model,
                'signup' => $signup,
            ]);
        }
        return $this->render('login', [
                'model' => $model,
                'signup' => $signup,
            ]);
    }

    public function actionAdvertisingsite ()
    {
        return $this->render('advertisingsite', [

        ]);
    }
    public function actionLoginSocial()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if(isset($_POST['token']))
        {
            $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
            $user = json_decode($s, true);
            
            $old_user = \common\models\User::find()->where(['identity' => $user['uid']])->andWhere(['network' => $user['network']])->orWhere(['email' => $user['email']])->one();

            if(!empty($old_user)){
                // if(empty($old_user->identity)){
                //     $old_user->identity = $user['uid'];
                // }
                Yii::$app->getUser()->login($old_user);
                return $this->redirect(['myaccount/index']);
            }

            $stock = Stock::find()->where(['>' ,'validity_at', time()])->orderBy('id DESC')->one();
            $balance = 0;
            if(!empty($stock)){
                $stock->count = $stock->count +1;
                $stock->save();
                $balance = $stock['sum']; 
            }

            $signup = new \common\models\User();
            $signup->username = $user['first_name'];
            $signup->lastname = $user['last_name'];
            $signup->email = $user['email'];
            $signup->identity = $user['uid'];
            $signup->network = $user['network'];
            $signup->lastvisit = time();
            $signup->balance = $balance;
            $signup->setPassword(Yii::$app->security->generateRandomString());
            $signup->generateAuthKey();

            if($signup->save()){
                Yii::$app->getUser()->login($signup);
                return $this->redirect(['myaccount/index']);
            }else{
                return $this->goHome();
            }   
        }else{
            return $this->goHome();
        }
    }

    public function actionAjax()
    {

        $signup = new SignupForm();
        if (Yii::$app->request->isAjax && $signup->load(Yii::$app->request->post())) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($signup);
        }
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте ваш емейл для дальнейших инструкций.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'К сожалению, мы не можем сбросить пароль для указанного адреса электронной почты.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль сохранён.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }


    public function actionLogout()
    {
        

        $adsaa = AdsFavorites::find()->where(['user_id' => Yii::$app->user->identity->id])->asArray()->all();

        foreach ($adsaa as $key) {
            Yii::$app->response->cookies->remove('ad_' . $key['ads_id']);
        }
        
        AdsFavorites::deleteAll(['user_id' => Yii::$app->user->identity->id]);
        

        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionAdminmessage()
    {
        if(Yii::$app->request->isAjax){

            parse_str(Yii::$app->request->post('data'), $data);
            $user_id = htmlspecialchars($data['user_id']);
            $text = htmlspecialchars($data['text']);
            $email = htmlspecialchars($data['email']);
            $phone = htmlspecialchars($data['phone']);

            Yii::$app->db->createCommand('INSERT INTO jandoo_admin_message (text, email, user_id, created_at, phone) VALUES(:text, :email, :user_id, :created_at, :phone)')
            ->bindValue(':text', $text)
            ->bindValue(':email', $email)
            ->bindValue(':user_id', $user_id)
            ->bindValue(':created_at', time())
            ->bindValue(':phone', $phone)
            ->execute();

            Yii::$app->mailer->compose()
                ->setFrom('info@sago-group.com.ua')
                ->setTo( $data['email'])
                ->setSubject( $data['tema'])
                ->setTextBody($data['message'])
                ->send();

            Yii::$app->mailer->compose()
                ->setFrom('jandooo@yandex.ru')
                ->setTo( $data['email'])
                ->setSubject( $data['tema'])
                ->setTextBody($data['message'])
                ->send();

            return true;
        }
        return false;
    }

    public function actionImportantKnow()
    {   


        $page = Pages::find()->where('id = 23')->one();
        $this->view->title = $page->name;
        
        return $this->render('importantKnow', [
            'page' => $page,
        ]);
    }

    public function actionHowItWork()
    {   
        $this->view->title = '';


        
        return $this->render('howItWork', []);
    }

    public function actionAdvertising()
    {
        $this->view->title = 'Jandooo | Реклама на сайте';
        
        return $this->render('advertising', []);
    }

    public function actionSecurity()
    {
        $this->view->title = 'Jandooo | Безопасность';

        
        return $this->render('security', []);
    }

    // public function actionRules()
    // {
    //     $this->view->title = 'Правила использования';
        
    //     return $this->render('rules', []);
    // }

    public function actionJandooo()
    {
    	return $this->goHome();

            $host = 'jandooo.mysql.ukraine.com.ua';
            $db   = 'jandooo_db';
            $user = 'jandooo_db';
            $pass = '88hsJepY';
            $charset = 'utf8';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $opt = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new \PDO($dsn, $user, $pass, $opt);

            $stmt = $pdo->prepare('SELECT * FROM hlsk8_users WHERE id BETWEEN 9796 AND 11000');
            $stmt->execute();
            $data = $stmt->fetchAll();

            for($i = 0; $i < count($data); $i++){

                $name = explode(' ', $data[$i]['name']);
                $password = $data[$i]['email'];
                $signup = new \common\models\User();
                $signup->id = $data[$i]['id'];
                $signup->username = $name[0];
                $signup->lastname = $name[1];
                $signup->email = $data[$i]['email'];
                $signup->created_at = strtotime($data[$i]['registerDate']);
                $signup->updated_at = strtotime($data[$i]['registerDate']);
                $signup->lastvisit = strtotime($data[$i]['lastvisitDate']);
                $signup->setPassword($password);
                $signup->generateAuthKey();
                $signup->save();
            }

    }

    public function actionJandooosite()
    {
//22111
    	 return $this->goHome();
    	$host = 'jandooo.mysql.ukraine.com.ua';
        $db   = 'jandooo_db';
        $user = 'jandooo_db';
        $pass = '88hsJepY';
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new \PDO($dsn, $user, $pass, $opt);

        $stmt = $pdo->prepare('SELECT ads.id, ads.userid, ads.name, ads.images, ads.ad_city, ads.email, ads.ad_phone, ads.ad_headline, ads.ad_text, ads.ad_price, ads.date_created, ads.date_modified, ads.views, cat.name as cat_name
        	FROM hlsk8_adsmanager_ads as ads
			LEFT JOIN hlsk8_adsmanager_adcat as adcat ON ads.id = adcat.adid
			LEFT JOIN hlsk8_adsmanager_categories as cat ON adcat.catid = cat.id
			WHERE ads.id BETWEEN 22111 AND 31000'); //6024
        $stmt->execute();
        $data = $stmt->fetchAll();

        $mypdo = new \PDO('mysql:host=sago3.mysql.ukraine.com.ua;dbname=sago3_temp9', 'sago3_temp9', '63zlv6j7', $opt);
        for($i = 0; $i < count($data); $i++){

        	if(empty($data[$i]['ad_headline']))
        		continue;

        	$new_ad = new \frontend\models\Ads();

        	$new_ad->user_id = $data[$i]['userid'];
        	$new_ad->name = $data[$i]['ad_headline'];

	        $cat = Categories::find()->select('id')->filterWhere(['like', 'name', $data[$i]['cat_name']])->asArray()->one();

	        if(!empty($cat)){
	        	$new_ad->sub_category = $cat['id'];
				$new_ad->category_id = $cat['id'];
	        }
			else{
				$new_ad->sub_category = -999;
				$new_ad->category_id = -999;
			}

        	$new_ad->text = $data[$i]['ad_text'];
        	$new_ad->active = 1;
        	$new_ad->created_at = strtotime($data[$i]['date_created']);
        	$new_ad->updated_at = strtotime($data[$i]['date_modified']);
        	$new_ad->views = $data[$i]['views'];
        	$new_ad->price = $data[$i]['ad_price'];
        	$new_ad->type_delivery = 5;
        	$new_ad->type_payment = 5;
        	$new_ad->contact = $data[$i]['name'];
        	$new_ad->email = $data[$i]['email'];
        	$new_ad->phone = $data[$i]['ad_phone'];
        	$new_ad->location = $data[$i]['ad_city'];
        	$new_ad->image = json_decode($data[$i]['images'], true)[0]['image'];

        	$image_name = json_decode($data[$i]['images'], true)[0]['image'];
        	$image_url = "https://jandooo.com/images/com_adsmanager/contents/" . $image_name;

			$alias = Yii::getAlias('@appWeb') . '/uploads/ads/' . $i .'.jpg';
            $new_ad->save(false);

        	if(file_put_contents($alias, file_get_contents($image_url))){
                // echo '<pre>';
       //       // print_r($new_ad);die;

                

                if(empty($image_name)){
                    continue;
                }
                // if(!file_exists($alias)){
                //     continue;
                // }

                $new_ad->attachImage($alias);

                $img = Yii::$app->db->createCommand('SELECT filePath FROM jandoo_image WHERE modelName ="Ads" and itemId = '.$new_ad->id.'')->queryOne();

                $new_ad->getImage();
                $str = explode('/', $img['filePath']);
                $alias_mini = Yii::getAlias('@appWeb') . '/images/store/' . $str[0] . '/' . $str[1] . '/mini_' . $str[2];

                

                $image = new \Imagick($alias);
                $image->setImageCompressionQuality(75);
                $image->cropThumbnailImage(220, 120);
                $image->writeImage($alias_mini);

                $watermark = new \abeautifulsite\SimpleImage($alias_mini);
                $watermark->text('JANDOOO', Yii::getAlias('@frontend') . '/web/fonts/DIN.otf', 11, '#FFFFFF', 'top left', 0, 0);
                $watermark->save($alias_mini);
                // echo $new_ad->id;
                // $new_ad->alias = $new_ad->str2url2($new_ad->name, $new_ad->id);
                @unlink($alias);
                // echo '<pre>';
                // print_r($new_ad);
                // echo '</pre>';
            }

   //      	
        }
    }

    public function actionJac()
    {
        return $this->goHome();

        $ads = Ads::find()->where(['alias' => null])->all();

        foreach ($ads as $key) {

            $alias = $key->str2url2($key->name, $key->id);
            $id = $key->id;
            Yii::$app->db->createCommand('UPDATE jandoo_ads SET alias="'.$alias.'" WHERE id='. $id .'')->execute();
        }
       // echo '<pre>';
        //print_r($ads);die;
    }


    public function actionSiteMap(){
        return $this->goHome();


    	$_SERVER['HTTPS'] ? $siteName = 'https://' . $_SERVER['HTTP_HOST'] . '/' : $siteName = 'http://' . $_SERVER['HTTP_HOST'] . '/';

        // echo $siteName;die;


        /*
 		*	
 		*	
 		*	@return all text.xml
 		*/
 		$sitemap = '';

        /*
 		*	
 		*	
 		*	@return list priority number
 		*/

        $priorityList = [
        	'priorityOne' => '1.0',
        	'priorityTwo' => '0.9',
        	'priorityThree' => '0.8',
        	'priorityFour' => '0.5',
        ];


        /*
 		*	@return main page alias
 		*	main page priorityOne
 		*
 		*/

  		$mainPage = [
 			['loc' => $siteName, 'priority' => $priorityList['priorityOne']],
 		];

 		/*
 		*	@return category alias
 		*	category priorityTwo
 		*
 		*/

 		$category = [
 			['loc' => $siteName . 'category', 'priority' => $priorityList['priorityTwo']],
 			['loc' => $siteName . 'category/all-vip', 'priority' => $priorityList['priorityTwo']],
 		];


 		/*
 		*	@return statics, blogs page alias
 		*	statics, blogs priorityThree
 		*
 		*/

 		$pages = [
 			['loc' => $siteName . 'how-it-work', 'priority' => $priorityList['priorityThree']],
 			['loc' => $siteName . 'important-know', 'priority' => $priorityList['priorityThree']],
 			['loc' => $siteName . 'advertising', 'priority' => $priorityList['priorityThree']],
 			['loc' => $siteName . 'blog', 'priority' => $priorityList['priorityThree']],
 			['loc' => $siteName . 'rules', 'priority' => $priorityList['priorityThree']],
 			['loc' => $siteName . 'security', 'priority' => $priorityList['priorityThree']],
 			['loc' => $siteName . 'security', 'priority' => $priorityList['priorityThree']],
 		];

 		/*
 		*	@return ads alias
 		*	ads priorityFour
 		*
 		*/

 		$ads = [];


        $categoryDb = (new \yii\db\Query())->select('id, alias, parent_id')->from('jandoo_categories')->where('active = 1')->orderBy('id ASC')->indexBy('id')->all();
		$adsDb = (new \yii\db\Query())->select('id, alias, updated_at')->from('jandoo_ads')->where('active = 1')->orderBy('id ASC')->indexBy('id')->all();


        foreach ($categoryDb as $key => $value)
        	if(!empty($categoryDb[$value['parent_id']]))
        		$category[] = ['loc' => $siteName . 'category/' . $categoryDb[$value['parent_id']]['alias'] . '/' . $value['alias'], 'priority' => $priorityList['priorityTwo']];
        	else
        		$category[] = ['loc' => $siteName . 'category/' . $value['alias'], 'priority' => $priorityList['priorityTwo']];


        foreach ($adsDb as $key => $value)
        	$ads[] = ['loc' => $siteName . 'ads/' . $value['alias'], 'lastmod' => date('Y-m-d', $value['updated_at']), 'priority' => $priorityList['priorityFour']];


     

        $all = [$mainPage, $category, $pages, $ads];


       
 		$sitemap = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

 		foreach ($all as $key => $value) {
        	
        	foreach ($value as $key => $value) {

        		$sitemap .= '<url>';

        		foreach ($value as $key => $value)
        			$sitemap .= '<' . $key . '>' . $value . '</' . $key . '>';

        		$sitemap .= '</url>';
        	}
        	
        }
 		

 		$sitemap .= '</urlset>';

 		$path = dirname(dirname(__DIR__));

		if(file_exists($path . '/sitemap.xml'))
			@unlink($path . '/sitemap.xml');

		$fp = fopen($path . "/sitemap.xml", "w");
		fwrite($fp, $sitemap);
		fclose($fp);
        
    }

}
