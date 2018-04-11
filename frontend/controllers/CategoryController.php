<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Categories;
use frontend\models\Ads;
use yii\data\Pagination;
use yii\data\Sort;
use frontend\models\Search;
use yii\helpers\Html;

class CategoryController extends Controller
{

	public function beforeAction($action)
	{
		$this->view->registerCssFile('/css/main.css', ['position' => \yii\web\View::POS_HEAD]);
		// $this->view->registerJsFile('/js/common.js', ['position' => \yii\web\View::POS_END]);
		return parent::beforeAction($action);
	}
    public function actionIndex()
    {	
        $this->view->registerCssFile('/css/nprogress.css');
    	$this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';
        $search = new Search();


        if(stristr($cat, 'q-') || stristr($cat, 'c-') && strcmp($cat, 'all-vip') != 0 && strcmp($cat, 'user-ads') != 0){

            $search = new Search();
            if($search->load(Yii::$app->request->post()) && $search->validate()){
                $cat = 'q-' . strtr(Html::encode($search->q), ['+' => '-', ' ' => '-']);
                $subcat = 'c-' . strtr(Html::encode($search->city), ['+' => '-', ' ' => '-']);
                empty($subcat) ? $subcat = null : 0;
            }
            unset($_GET['cat']);
            unset($_GET['subcat']);

            $_GET['q'] = $cat;
            $_GET['reg'] = $subcat;

            if(stristr($cat, 'c-')){
                $_GET['q'] = null;
                $_GET['reg'] = $cat;
                $subcat = $cat;
                $cat = 'c-';
            }
        

            $this->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow']);

            if(!empty($_GET['ads'])){
                if(strcmp($_GET['ads'], 'ch') == 0)
                    $type_ads = 5;
                if(strcmp($_GET['ads'], 'bz') == 0)
                    $type_ads = 10;
                if(empty($type_ads))
                    $type_ads = null;
            }else
                $type_ads = null; 


            $ads = Ads::getSearchAds(mb_substr($cat, 2), mb_substr($subcat, 2), 40, $type_ads, Yii::$app->request->get('img'), Yii::$app->request->get('sprice'), Yii::$app->request->get('eprice'));
            $category['category_name'] = 'Поиск ' . mb_substr($cat, 2);
        }
        else{

            if(!empty($_GET['ads'])){
                if(strcmp($_GET['ads'], 'ch') == 0)
                    $type_ads = 5;
                if(strcmp($_GET['ads'], 'bz') == 0)
                    $type_ads = 10;
                if(empty($type_ads))
                    $type_ads = null;
            }else
                $type_ads = null; 

    	    $ads = Ads::getAllAds(40, $type_ads, Yii::$app->request->get('img'), Yii::$app->request->get('sprice'), Yii::$app->request->get('eprice'));
        }

        return $this->render('index', [
            'search' => $search,
        	'categories' => $categories,
            'sort' => $ads['sort'],
            'top' => $ads['top'],
            'pagination' => $ads['pagination'],
            'ads' => $ads['ads'],
        ]);
    }

    public function actionView($cat = null, $subcat = null, $q = null, $reg = null)
    {    

    	$this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';
        $this->view->registerCssFile('/css/nprogress.css');

        if(empty($_GET['img'])){
            unset($_GET['img']);
        }
        if(empty($_GET['sprice'])){
            unset($_GET['sprice']);
        }
        if(empty($_GET['eprice'])){
            unset($_GET['eprice']);
        }

        // var_dump($_GET);die;


        if(intval($cat)){
            $search = new Search();
            $this->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow']);
            $category = Ads::getCurrentCity($cat, $subcat);
            $ads = Ads::getAdsByCityId($category, 40);
        }


        // search from index page  [q-cat] 
        // || search without category
        if(stristr($cat, 'q-') || stristr($cat, 'c-') && strcmp($cat, 'all-vip') != 0 && strcmp($cat, 'user-ads') != 0){

            $search = new Search();
            if($search->load(Yii::$app->request->post()) && $search->validate()){
                $cat = 'q-' . strtr(Html::encode($search->q), ['+' => '-', ' ' => '-']);
                $subcat = 'c-' . strtr(Html::encode($search->city), ['+' => '-', ' ' => '-']);
                empty($subcat) ? $subcat = null : 0;
            }
            unset($_GET['cat']);
            unset($_GET['subcat']);

            $_GET['q'] = $cat;
            $_GET['reg'] = $subcat;

            if(stristr($cat, 'c-')){
                $_GET['q'] = null;
                $_GET['reg'] = $cat;
                $subcat = $cat;
                $cat = 'c-';
            }
        

            $this->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow']);

            if(!empty($_GET['ads'])){
                if(strcmp($_GET['ads'], 'ch') == 0)
                    $type_ads = 5;
                if(strcmp($_GET['ads'], 'bz') == 0)
                    $type_ads = 10;
                if(empty($type_ads))
                    $type_ads = null;
            }else
                $type_ads = null; 


            $ads = Ads::getSearchAds(mb_substr($cat, 2), mb_substr($subcat, 2), 40, $type_ads, Yii::$app->request->get('img'), Yii::$app->request->get('sprice'), Yii::$app->request->get('eprice'));
            $category['category_name'] = 'Поиск ' . mb_substr($cat, 2);
        }

        // search in category [cat, subcat, q-search, reg-search] 
        // || search cat or cat and subcat 
        if(!intval($cat) && !stristr($cat, 'q-') && !stristr($subcat, 'q-') && !stristr($subcat, 'c-') && !stristr($cat, 'c-') && strcmp($cat, 'all-vip') != 0 && strcmp($cat, 'user-ads') != 0){


            $search = new Search();
            if($search->load(Yii::$app->request->post()) && $search->validate()){
                $q = 'q-' . strtr(Html::encode($search->q), ['+' => '-', ' ' => '-']);
                $reg = 'c-' . strtr(Html::encode($search->city), ['+' => '-', ' ' => '-']);
                empty($reg) ? $reg = null : 0;
            }

            $_GET['q'] = strtr($q, ['+' => '-', ' ' => '-']);
            $_GET['reg'] = strtr($reg, ['+' => '-', ' ' => '-']);


            if(stristr($_GET['q'], 'c-') || strcmp($_GET['q'], 'c-') ==0){
                $_GET['reg'] = $_GET['q'];
                unset($_GET['q']);
                $reg = $q;
                $q = null;

            }
            if(strcmp($reg, 'c-') == 0){
                $reg = null;
                $_GET['reg']=null;
            } 

            // $this->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow']);
            $category = Categories::getCurrentCategories($cat, $subcat);
            if (empty($category))
                 throw new \yii\web\HttpException(404, "Такой категории нет!");

            strcmp($reg, 'c-') == 0 ? $reg = null : 0;

            if((empty($q) && empty($reg)) || (strcmp($q, 'q-')==0 && empty($reg)) || (strcmp($q, 'q-')==0 && strcmp($reg, 'c-')==0) || (empty($q) && strcmp($reg, 'c-') == 0)){
                if(!empty($_GET['ads'])){
                    if(strcmp($_GET['ads'], 'ch') == 0)
                        $type_ads = 5;
                    if(strcmp($_GET['ads'], 'bz') == 0)
                        $type_ads = 10;
                    if(empty($type_ads))
                        $type_ads = null;
                }else
                    $type_ads = null; 
                    // echo $_GET['img'] ;die;
                    
                $ads = Ads::getAdsByCategoryId($category['category_id'], 40, $type_ads, Yii::$app->request->get('img'), Yii::$app->request->get('sprice'), Yii::$app->request->get('eprice'));
            }
            else{
                if(!empty($_GET['ads'])){
                    if(strcmp($_GET['ads'], 'ch') == 0)
                        $type_ads = 5;
                    if(strcmp($_GET['ads'], 'bz') == 0)
                        $type_ads = 10;
                    if(empty($type_ads))
                        $type_ads = null;
                }else
                    $type_ads = null;

                $ads = Ads::getAdsCatIdSearch(mb_substr($q, 2), mb_substr($reg, 2), $category['category_id'], 40, $type_ads,Yii::$app->request->get('img'), Yii::$app->request->get('sprice'), Yii::$app->request->get('eprice'));
            }
        }


        // search in category [cat, q-subcat, q-search, reg-search]
        // || search with cat
        if(!intval($cat) && !stristr($cat, 'q-') && !stristr($cat, 'c-') && (stristr($subcat, 'q-') || stristr($subcat, 'c-')) && strcmp($cat, 'all-vip') != 0 && strcmp($cat, 'user-ads') != 0){
            
            $search = new Search();
            if($search->load(Yii::$app->request->post()) && $search->validate()){
                $subcat = 'q-' . strtr(Html::encode($search->q), ['+' => '-', ' ' => '-']);
                $q = 'c-' . strtr(Html::encode($search->city), ['+' => '-', ' ' => '-']);
                empty($reg) ? $reg = null : 0;
            }

            unset($_GET['subcat']);
            $_GET['q'] = strtr($subcat, ['+' => '-', ' ' => '-']);
            $_GET['reg'] = strtr($q, ['+' => '-', ' ' => '-']);

            if(stristr($subcat, 'c-')){
                $q = $subcat;
                $subcat = null;
                $_GET['q'] = null;
                $_GET['reg'] = $q;
            }
            // echo $q . '<br>';
            // echo $subcat . '<br>';die;
            
        

            $this->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow']);
            $category = Categories::getCurrentCategories($cat, null);
            if (empty($category))
                 throw new \yii\web\HttpException(404, "Такой категории нет!");

            if(!empty($_GET['ads'])){
                if(strcmp($_GET['ads'], 'ch') == 0)
                    $type_ads = 5;
                if(strcmp($_GET['ads'], 'bz') == 0)
                    $type_ads = 10;
                if(empty($type_ads))
                    $type_ads = null;
            }else
                $type_ads = null;

            $ads = Ads::getAdsCatIdSearch(mb_substr($subcat, 2),  mb_substr($q, 2), $category['category_id'], 40, $type_ads, Yii::$app->request->get('img'), Yii::$app->request->get('sprice'), Yii::$app->request->get('eprice'));
        }


        if(strcmp($cat, 'all-vip') ==0){

             $search = new Search();
            if($search->load(Yii::$app->request->post()) && $search->validate()){
                $subcat = 'q-' . strtr(Html::encode($search->q), ['+' => '-', ' ' => '-']);
                $q = 'c-' . strtr(Html::encode($search->city), ['+' => '-', ' ' => '-']);
                empty($reg) ? $reg = null : 0;
            }

            unset($_GET['subcat']);
            $_GET['q'] = strtr($subcat, ['+' => '-', ' ' => '-']);
            $_GET['reg'] = strtr($q, ['+' => '-', ' ' => '-']);

            if(stristr($subcat, 'c-')){
                $q = $subcat;
                $subcat = null;
                $_GET['q'] = null;
                $_GET['reg'] = $q;
            }
            // echo $q . '<br>';
            // echo $subcat . '<br>';die;
            
        

            $this->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow']);
            $category['category_name'] = 'Вип обьявления'; 

            if(!empty($_GET['ads'])){
                if(strcmp($_GET['ads'], 'ch') == 0)
                    $type_ads = 5;
                if(strcmp($_GET['ads'], 'bz') == 0)
                    $type_ads = 10;
                if(empty($type_ads))
                    $type_ads = null;
            }else
                $type_ads = null;


            $ads = Ads::getVipAds(mb_substr($subcat, 2),  mb_substr($q, 2), 40, $type_ads, Yii::$app->request->get('img'), Yii::$app->request->get('sprice'), Yii::$app->request->get('eprice'));
        }

        if(strcmp($cat, 'user-ads') ==0){

            $search = new Search();
            if($search->load(Yii::$app->request->post()) && $search->validate()){
                $q = 'q-' . strtr(Html::encode($search->q), ['+' => '-', ' ' => '-']);
                $reg = 'c-' . strtr(Html::encode($search->city), ['+' => '-', ' ' => '-']);
                empty($reg) ? $reg = null : 0;
            }

            // unset($_GET['subcat']);
            $_GET['q'] = strtr($q, ['+' => '-', ' ' => '-']);
            $_GET['reg'] = strtr($reg, ['+' => '-', ' ' => '-']);

           
            // echo $q . '<br>';
            // echo $subcat . '<br>';die;
            
        

            $this->view->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow']);
            $category['category_name'] = 'Вип обьявления'; 

            if(!empty($_GET['ads'])){
                if(strcmp($_GET['ads'], 'ch') == 0)
                    $type_ads = 5;
                if(strcmp($_GET['ads'], 'bz') == 0)
                    $type_ads = 10;
                if(empty($type_ads))
                    $type_ads = null;
            }else
                $type_ads = null;


            $ads = Ads::getAdsByUserId(mb_substr($q, 2),  mb_substr($reg, 2), $subcat, 40, $type_ads, Yii::$app->request->get('img'), Yii::$app->request->get('sprice'), Yii::$app->request->get('eprice'));

            if (empty($ads))
                 throw new \yii\web\HttpException(404, "Такого пользователя нет!");
        }

        //  echo '<pre>';
        // var_dump($_GET);die;
        // if(empty($cat)){
        //     die;
        // }


            if(strcmp($_GET['q'], 'q-')==0 || empty($_GET['q']))
                unset($_GET['q']);
            if(empty($_GET['reg']) || strcmp($_GET['reg'], 'c-')==0)
                unset($_GET['reg']);

            // echo '<pre>';
            // print_r($ads['ads']);die;


    	return $this->render('index', [
            'search' => $search,
    		'category' => $category,
    		'sort' => $ads['sort'],
    		'pagination' => $ads['pagination'],
    		'ads' => $ads['ads'],
    		'top' => $ads['top'],
            'sort_cat' => $sort_cat,
        ]);
    }


    public function actionList()
    {
        if(Yii::$app->request->isAjax){
            $data_list = Yii::$app->request->post('data_list');

            if($data_list == 1){
                Yii::$app->session->set('list', 'list');
            }
            if($data_list == 0){
                Yii::$app->session->remove('list');
            }
        }
    }


    public function getCatLst($category_id)
    {
        $categories = ['0'=> ['id' => '', 'name' => 'Выберите категорию']];
        $child_cat = Categories::find()->select('id, name, parent_id')->where('active = 1')->andWhere(['id' => $category_id])->asArray()->indexBy('id')->one();

        $childs_cat = Categories::find()->select('id, name, parent_id')->where('active = 1')->andWhere(['parent_id' => $child_cat['parent_id']])->asArray()->indexBy('id')->all();

        $childs_childs_cat = (new \yii\db\Query())
            ->select('id, name, parent_id')
            ->from('jandoo_categories')
            ->where('parent_id = (SELECT parent_id FROM jandoo_categories WHERE id = "'.$child_cat['parent_id'].'") AND active = 1')->indexBy('id')->all();


        $main_cat = Categories::find()->select('id, name')->where('active = 1')->andWhere(['parent_id' => 0])->asArray()->indexBy('id')->orderBy('sort ASC')->all();
        $categories += $main_cat;

        
        if(current($childs_childs_cat)['parent_id']){
            $current = $childs_childs_cat;
            $childs_childs_cat = $childs_cat;
            $childs_cat = $current;
            $child_cat['parent_id'] = current($childs_cat)['parent_id'];
        }else{
            $childs_childs_cat = false;
        }

        return [
            'categories' => $categories,
            'parent_child' => $child_cat['parent_id'],
            'childs_childs_cat' => $childs_childs_cat,
            'childs_cat' => $childs_cat
        ];
    }

}
