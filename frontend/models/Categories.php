<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
/**
 * This is the model class for table "{{%categories}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $intro_text
 * @property integer $full_text
 * @property string $alias
 * @property string $name
 * @property string $image
 * @property integer $active
 * @property integer $sort
 */
class Categories extends \yii\db\ActiveRecord
{
    // public $image;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%categories}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['alias'], 'required'],
            [['parent_id'], 'required'],
            [['intro_text'], 'safe'],
            [['full_text'], 'safe'],
            [['active', 'sort'], 'integer'],
            // [['sort'], 'min' => 0],
            [['name', 'image'], 'string', 'max' => 255],
            // [['image'], 'file', 'extensions' => 'png, jpg']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'image' => 'Изображение',
            'sort' => 'Номер',
        ];
    }

    public static function getCategories()
    {
        return Categories::find()->indexBy('id')->asArray()->all();
    }

    public static function getHash($str){

        $str = $str . strtotime(date('H:i:s'));
        $file = md5($str);
        return $file;

    }

    public static function getCategoriesById($id)
    {
        return Categories::find()->where(['id' => $id])->one();
    }

    public function upload()
    {
        $img = '/home/sago3/sago.in.ua/temp9/web/uploads/categories/' . $this->getHash($this->image->baseName) . '.' . $this->image->extension;
        $img_tmp = $this->image->tempName;
        
        $this->image = $this->getHash($this->image->baseName) . '.' . $this->image->extension;
        move_uploaded_file($img_tmp, $img);

        // echo '<pre>';
        // print_r($this->image);
        //  echo '<br>';
        // print_r($img_tmp);
        // echo '<br>';
        // echo '</pre>';die;

        $this->save(false);
        return true;
      
    }

    public function rus2translit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
            
            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }
    public function str2url($str) {
        // переводим в транслит
        $str = $this->rus2translit($str);
        // в нижний регистр
        $str = strtolower($str);
        // заменям все ненужное нам на "-"
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        // удаляем начальные и конечные '-'
        $str = trim($str, "-");
        $old_str = Categories::find()->where(['alias' => $str])->one();
        if(!empty($old_str))
            $str = $str . strtotime(time());
        return $str;
    }

    public function setSort()
    {
        $query = new Query;
        if($this->parent_id == 0){
            $query->select('MAX(sort)')->from('jandoo_categories')->where('parent_id = 0')->limit(1);
            $rows = $query->all();
            $command = $query->createCommand();
            $rows = $command->queryAll();
            $this->sort = $rows[0]['MAX(sort)'] + 1;
        }
        else{
            $query->select('MAX(sort)')->from('jandoo_categories')->where("parent_id = " . $this->parent_id . " ")->limit(1);
            $rows = $query->all();
            $command = $query->createCommand();
            $rows = $command->queryAll();
            $this->sort = $rows[0]['MAX(sort)'] + 1;
        }
    }

    public static function getTree($data)
    {
        $tree = [];
        foreach ($data as $id => &$node){
            if(!$node['parent_id']){
                $tree[$id] = &$node;
            }else{
                $data[$node['parent_id']] ['childs'][$node['id']] = &$node;
            }
        }

        return $tree;

       //  for($i=0; $i<count($tree); $i++){
       //     for($j=$i+1; $j<count($tree); $j++){
       //         if($tree[$i]['sort']>$tree[$j]['sort']){
       //             $temp = $tree[$j];
       //             $tree[$j] = $tree[$i];
       //             $tree[$i] = $temp;
       //         }
       //    }         
       // }
       // array_multisort($tree, SORT_ASC, 'sort', SORT_ASC);
        $child_tree = null;
        $child_id = null;
        foreach ($tree as $key) {

            if(isset($key['childs'])){

                $child_tree = $key['childs'];
                $child_id = $key['id'];

                    // echo '<pre>';
                    // print_r($tree[$child_id]); 
// die;
                usort($child_tree, function($a,$b){
                    return ($a['sort']-$b['sort']);
                });
                unset($tree[$child_id]['childs']);
                $tree[$child_id]['childs'] = $child_tree;
                // array_push($tree[$child_id]['childs'], $child_tree);
                // $key['childs'] = $child_tree;
                // print_r($child_tree); 
                //array_replace($tree, $child_tree);
            }
        }
        // ASC cat
        // usort($tree, function($a,$b){
        //             return ($a['sort']-$b['sort']);
        //         });
        
       // echo '<pre>';
       //  print_r($tree); die;
       //  echo '</pre>';


        return $tree;
    }

    public static function getHtmlOptionCat($categories)
    {
        $cat = '<option value="">Выберите категорию</option>';
        foreach ($categories as $key) {
            $cat .= '<option value="'. $key['id'] .'">'. $key['name'] .'</option>';
        }

        return $cat;
    }

    public static function getHtmlListCategories()
    {   
        $cat_list = Yii::$app->cache->get('list_category_f');
        if($cat_list === false){
            $categories = Categories::find()->where('active = 1 AND parent_id = 0')->asArray()->indexBy('id')->orderBy('sort ASC')->all();

            $cat_list = '';
            $count = 0;
            $j = 1;
            $s = 1;

            foreach ($categories as $key) {
                $count++;
            }

            foreach ($categories as $key) {
                if($j == 1)
                    $cat_list .= '<div class="col-md-3"><p class="title_in_footer">Категории объявлений</p>';

                $cat_list .= '<a href="'. Url::to(['category/view', 'cat' => $key['alias']]) .'" class="link-white">'. $key['name'] .'</a>';

                if($j == 9 || $s == $count){
                    $cat_list .= '</div>';
                    $j = 0;
                }
                $s++;
                $j++;
            }


            Yii::$app->cache->set('list_category_f', $cat_list, 600);
        }

            
        return $cat_list;
    }

    public static function getHtmlCategories($categories)
    {
        $categories = self::getTree($categories);

        $cat_team = '';
        $subcategories = '';
        $count = 0;
        $s = 1;
        $j = 1;

        foreach ($categories as $key)
            if(isset($key['id']))
                $count++;

        foreach ($categories as $key) {

            if(isset($key['id'])){
                if($s == 1)
                    $cat_team .= '<div class="row">';

                $url_img = !empty($key['image']) ? Yii::getAlias('@categories') .'/'. $key['image'] : Yii::getAlias('@images') . '/noimage-min.jpg';

                $cat_team .= '<div id="id-cat-team'. $j .'" class="item"><a href="'. Url::to(['category/view', 'cat' => $key['alias']]) .'"><img src="'. $url_img .'" alt="'. $key['name'] .'"><div class="icon">'. $key['name'] .'</div></a></div>';
                if($s == 9 || $count == $j){
                    $cat_team  .= '</div>';
                }

                if(!empty($key['childs'])){
                    $subcategories .= '<div class="subcategories" id="subcategories'. $j .'"><div class="sub-p"><div class="subcategories-title"><a href="'. Url::to(['category/view', 'cat' => $key['alias']]) .'"><i class="fa fa-angle-right" aria-hidden="true"></i><div class="icon">Смотреть все объявления</div> в '. $key['name'] .'</a></div>';

                    foreach ($key['childs'] as $val){
                        $subcategories .= '<a href="'. Url::to(['category/view', 'cat' => $key['alias'], 'subcat' => $val['alias']]) .'"><i class="fa fa-angle-right" aria-hidden="true"></i><div class="icon">'. $val['name'] .'</div></a>';
                    }
                    $subcategories .= '</div></div>';
                }

                if($s == 9 || $count == $j){
                    $s = 0;
                    $cat_team  .= $subcategories;
                    $subcategories = '';
                }

                $s++;
                $j++;
            }
        }
        return $cat_team;
    }

    public static function getCurrentCategories($cat, $subcat = null)
    {
        if($subcat !== null)
            return $category = Yii::$app->db->createCommand('SELECT a.alias as category_alias, a.name as category_name, a.id as category_id, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id
            FROM jandoo_categories as a 
            LEFT JOIN jandoo_categories as d ON a.parent_id = d.id
            WHERE a.alias = :subcat AND d.alias = :cat ')->bindValue(':cat', $cat)->bindValue(':subcat', $subcat)->queryOne();

        $category = Yii::$app->db->createCommand('SELECT a.alias as category_alias, a.name as category_name, a.id as category_id
            FROM jandoo_categories as a WHERE a.alias = :cat AND parent_id = 0')->bindValue(':cat', $cat)->queryOne();

        if(empty($category)){
           return $category = Yii::$app->db->createCommand('SELECT a.alias as category_alias, a.name as category_name, a.id as category_id, d.name as parent_category_name, d.alias as parent_category_alias, d.id as parent_category_id
            FROM jandoo_categories as a 
            LEFT JOIN jandoo_categories as d ON a.parent_id = d.id
            WHERE a.alias = :alias')->bindValue(':alias', $cat)->queryOne();
            // var_dump($category);die;
        }

        return $category;

    }
}