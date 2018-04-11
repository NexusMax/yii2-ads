<?php
namespace backend\components;
use yii\base\Widget;
use backend\models\Categories;
use frontend\models\MagazineHasCategories;
use yii;
class CategoryWidget extends Widget
{
    public $template;
    public $model;
    public $data;
    public $tree;
    public $categoryHTML;

    public $category_id;

    public $magazine_id;

    public function init()
    {
        parent::init();
        if($this->template === null){
            $this->template = 'widget-category';
        }
        $this->template .= '.php';
    }
    public function run()
    {

       if($this->template == 'widget-category.php') {
            $categoryCache = Yii::$app->cache->get('category');
            if (!empty($categoryCache)){
             return $categoryCache;
            }
        }

        if(!empty($this->magazine_id)){
            $this->data = MagazineHasCategories::find()->where(['magazine_id' => $this->magazine_id])->indexBy('id')->asArray()->all();
        }else{
            $this->data = Categories::find()->indexBy('id')->asArray()->all();
        }

        
        if($this->template == 'widget-category-ul.php') {

            $array_cat = [];
            foreach ($this->data as $key) {
                $array_cat[$key['parent_id']][] = $key;
            }

            $this->categoryHTML = '';
            foreach ($array_cat as $key => $value) {

                ($value[0]['parent_id'] == 0 ) ? $class_ = 'class="parent-ul"' : $class_ = 'class="secondary-ul"';

                $this->categoryHTML .= '<ul data-parent-id="' . $value[0]['parent_id'] . '" ' . $class_  . '>';
                $parent_ziro = true;
                foreach ($value as $val) {

                    if($val['parent_id'] == 0 && $parent_ziro){
                        $this->categoryHTML .= '<li data-category-id="" data-category-alias=""><a href="">Все категории</a></li>';
                        $parent_ziro = false;
                    }
                    $this->categoryHTML .= '<li ';

                    if(
                        strcmp($val['alias'],  Yii::$app->controller->action->controller->actionParams['cat']) == 0 || 
                        strcmp($val['alias'],  Yii::$app->controller->action->controller->actionParams['subcat']) == 0
                    ){
                        Yii::$app->params['Category']['selected_id'] = $val['id']; 
                        $this->categoryHTML .= ' class="active" '; 

                    }

                    $this->categoryHTML .=' data-category-id="' . $val['id'] .'" data-category-alias="' . $val['alias'] . '"><a href="">' . $val['name'] . '</a></li>';

                }
                $this->categoryHTML .= '</ul>';
            }


            // echo '<pre>';
            // print_r($this->categoryHTML);
            // die;
        }else{
            $this->tree = $this->getTree();

            $this->categoryHTML = $this->getCategoryHtml($this->tree);
            if($this->template == 'widget-category.php') {
                Yii::$app->cache->set('category', $this->categoryHTML, 60);
            } 
        }

        // echo '<pre>';
        // print_r($array_cat);
        // die;

        



        return $this->categoryHTML;
    }


    protected function getTree()
    {
        $tree = [];
        foreach ($this->data as $id => &$node){
            if(!$node['parent_id']){
                $tree[$id] = &$node;
            }else{
                $this->data[$node['parent_id']] ['childs'][$node['id']] = &$node;
            }
        }

        return $tree;
    }
    protected function getCategoryHtml($tree, $tab = '')
    {
        $str = '';
        foreach ($tree as $category){
            $str .= $this->catToTemplate($category, $tab);
        }
        return $str;
    }
    protected function catToTemplate($category, $tab)
    {
        ob_start();
        include __DIR__ . '/views/' . $this->template;
        return ob_get_clean();
    }
}