<?php

namespace backend\models;

use Yii;
use yii\db\Query;
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

    public function getAds()
    {
        return $this->hasMany(\backend\models\Ads::className(), ['category_id' => 'id']);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'image' => 'Изображение',
            'sort' => 'Номер',
        ];
    }

    public static function getCategories($sort = 'sort ASC')
    {
        return Categories::find()->indexBy('id')->asArray()->orderBy($sort)->all();
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
        $img = Yii::getAlias('@appWeb') . '/uploads/categories/' . $this->getHash($this->image->baseName) . '.' . $this->image->extension;
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
            $str = $str . '_' . mb_substr(Yii::$app->security->generateRandomString(), 0, 6);;
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

    public function setSortMiddle($status)
    {
        if($this->parent_id == 0){
            $this->sort = $status;
            $i = $status + 1;

            $ends_cat = self::find()->where(['>=', 'sort', $status])->andWhere('parent_id = 0')->orderBy('sort ASC')->all();
            
            foreach ($ends_cat as $key) {
                $key->sort = $i++;
                $key->save(false);
            }

        }
        else{
            $this->sort = $status;
            $i = $status + 1;

            $ends_cat = self::find()->where(['>=', 'sort', $status])->andWhere(['parent_id' => $this->parent_id])->orderBy('sort ASC')->all();
            
            foreach ($ends_cat as $key) {
                $key->sort = $i++;
                $key->save(false);
            }
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
}