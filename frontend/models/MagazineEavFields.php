<?php

namespace frontend\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "{{%magazine_eav_fields}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $name_field
 * @property integer $type_id
 * @property integer $category_id
 * @property integer $active
 * @property integer $search
 */
class MagazineEavFields extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name_field',
                'slugAttribute' => 'name'
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%magazine_eav_fields}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_field'], 'required'],
            [['type_id', 'active', 'search', 'category_id'], 'integer'],
            [['name', 'name_field'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'name_field' => 'Название',
            'type_id' => 'Тип',
            'category_id' => 'Категория',
            'active' => 'Активность',
            'search' => 'Поиск',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(\frontend\models\MagazineCategory::className(), ['id' => 'category_id']);
    }

    public function getType()
    {
        return $this->hasOne(\frontend\models\MagazineEavTypes::className(), ['id' => 'type_id']);
    }

    public function getOpts()
    {
        return $this->hasMany(\frontend\models\MagazineEavOptions::className(), ['field_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        
        if($insert){
            $this->name = $this->name . '-' . $this->id;
            $this->save();
        }


        parent::afterSave($insert, $changedAttributes);
       
    }


    public static function getHtmlFields($fields, $name = false, $value = false)
    {
       
        $html = '';
        for($i = 0; $i < count($fields); $i++){

            ($name === true) ? $name_field = $fields[$i]['name'] : $name_field = 'AttrField[' . $fields[$i]['name'] . '][]';
            $options = $fields[$i]['opts'];

            $html .= '<div class="form-group field-' . $name_field . '">';
            $html .= '<label class="control-label" for="' . $name_field . '">' . $fields[$i]['name_field'] . '</label>';

            switch($fields[$i]['type']['name']){
                case 'select':      $html .= self::getSelect($name_field, $options, $value);break;
                case 'number':      $html .= self::getNumber($name_field, $value);break;
                case 'text':        $html .= self::getText($name_field, $value);break;
                case 'radio':       $html .= self::getRadio($name_field, $options, $value);break;
                case 'checkbox':    $html .= self::getCheckbox($name_field, $options, $value);break;
                case 'multiple':    $html .= self::getCheckbox($name_field, $options, $value);break;
                default: break;
            }

            $html .= '<div class="help-block"></div>';
            $html .= '</div>';
        }

        return $html;
    }

    public static function getHtmlField($fields, $name = false, $value = false)
    {
       // echo '<pre>';
       // print_r($value);
       // die;

       // foreach ($value as $key => $value) {
        
       //      $name_field = 'AttrField[' . $key . '][]';

       //      $html .= '<div class="form-group field-' . $name_field . '">';
       //      $html .= '<label class="control-label" for="' . $name_field . '">' . $fields[$i]['field']['name_field'] . '</label>';
           
           

       //      switch($value['type']){
       //          case 'select':      $html .= self::getSelect($name_field, $options, $value);break;
       //          case 'number':      $html .= self::getNumber($name_field, $value);break;
       //          case 'text':        $html .= self::getText($name_field, $value);break;
       //          case 'radio':       $html .= self::getRadio($name_field, $options, $value);break;
       //          case 'checkbox':  
       //              $par = []; 
       //              for($y = 0; $y < count($fields); $y++){
       //                  if(strcmp($fields[$y]['field']['type']['name'], 'checkbox') === 0){
       //                      $par[$fields[$y]['field_id']][] = $fields[$y]['value'];
       //                      $array = $fields;
       //                      unset($array[$y]);
       //                      // unset($fields['vals'][$y]);
       //                      $fields = $array;
       //                      // echo '<pre>';
       //                      // print_r($fields);
       //                      // die;
       //                  }
       //              }
       //              // echo '<pre>';
       //              // print_r($fields);
       //              // die;
       //              $html .= self::getCheckbox($name_field, $options, $par);break;
       //          case 'multiple':    $html .= self::getCheckbox($name_field, $options, $value);break;
       //          default: break;
       //      }
       //      // echo '<pre>';
       //              // print_r($i);
       //      //         die;
       //      $html .= '<div class="help-block"></div>';
       //      $html .= '</div>';

       // }
        $fields = $fields['vals'];

        // echo '<pre>';
        // print_r($fields);
        // die;
        $html = '';

        // $co_ = count($fields);
        // foreach ($variable as $key => $value) {
        //     # code...
        // }
        foreach($fields as $key){
        	$value_ = $key['value'];
            ($name === true) ? $name_field = $key['field']['name'] : $name_field = 'AttrField[' . $key['field']['name'] . '][]';
            $options = $key['field']['opts'];

            $name = $key['field']['name'];
            if(empty($key)){
                continue;
            }

            $html .= '<div class="form-group field-' . $name_field . '">';
            $html .= '<label class="control-label" for="' . $name_field . '">' . $key['field']['name_field'] . '</label>';
           
           

            switch($key['field']['type']['name']){
                case 'select':      $html .= self::getSelect($name_field, $options, $value_);break;
                case 'number':      $html .= self::getNumber($name_field, $value_);break;
                case 'text':        $html .= self::getText($name_field, $value_);break;
                case 'radio':       $html .= self::getRadio($name_field, $options, $value_);break;
                case 'checkbox':  
                	// $par = []; 
                	// for($y = 0; $y < count($fields); $y++){
                	// 	if(strcmp($fields[$y]['field']['type']['name'], 'checkbox') === 0){
                	// 		$par[$fields[$y]['field_id']][] = $fields[$y]['value'];
                	// 		$array = $fields;
                 //            unset($array[$y]);
                	// 		// unset($fields['vals'][$y]);
                	// 		$fields = $array;
                	// 		// echo '<pre>';
                	// 		// print_r($fields);
                	// 		// die;
                	// 	}
                	// }
                	// echo '<pre>';
                	// print_r($fields);
                	// die;
                	$html .= self::getCheckbox($name_field, $options, $value, $name);break;
                case 'multiple':    $html .= self::getCheckbox($name_field, $options, $value);break;
                default: break;
            }
            // echo '<pre>';
                    // print_r($i);
            //         die;
            $html .= '<div class="help-block"></div>';
            $html .= '</div>';
        }

        return $html;
    }

    public static function getHtmlFieldss($fields, $name = false, $value = false)
    {

        $fields = $fields['vals'];

        // echo '<pre>';
        // print_r($value);
        // print_r($fields);
        // die;

        $html = '';

        foreach($fields as $key){
            $value_ = $key['value'];
            ($name === true) ? $name_field = $key['field']['name'] : $name_field = 'AttrField[' . $key['field']['name'] . '][]';
            $options = $key['field']['opts'];

            $name = $key['field']['name'];
            if(empty($key)){
                continue;
            }

            $html .= '<li><b>' . $key['field']['name_field'] . '</b> ';
            // echo '<pre>';
            // print_r($value); echo '<br>';
            // print_r($value[$key['field']['name']]);
            // die;
            // if(!empty($value[$key['field']['name']])){

            //         foreach($key['field']['opts'] as $val_){
            //             if(in_array($val_['id'], $value[$key['field']['name']])){
            //                 $html .= ' ' . $val_['name'];
            //             }else{
            //                 $html .= $value_;
            //             }
            //         }

            //     }
            // $html .= $value_;
            switch($key['field']['type']['name']){
               case 'select':      
                if(!empty($value[$key['field']['name']])){

                    foreach($key['field']['opts'] as $val_){
                        if(in_array($val_['id'], $value[$key['field']['name']])){
                            $html .= ' ' . $val_['name'];
                        }
                    }

                }
               break;
                case 'number':      $html .= $value_;break;
                case 'text':        $html .= $value_;break;
                case 'radio':       
                if(!empty($value[$key['field']['name']])){
                    foreach($key['field']['opts'] as $val_){
                        if(in_array($val_['id'], $value[$key['field']['name']])){
                            $html .= ' ' . $val_['name'];
   
                        }
                    }

                }
                break;
                case 'checkbox':    
                if(!empty($value[$key['field']['name']])){
             
                    $ccc = 0 ;
                    $cc = count($value[$key['field']['name']]);
                    foreach($key['field']['opts'] as $val_){
                        if(in_array($val_['id'], $value[$key['field']['name']])){
                            $html .= ' ' . $val_['name'];
                            if($cc >= 2 && $cc !== $ccc){
                                $html .= ',';
                            }
                        }
                        $ccc = $ccc + 1;
                    }

                }
                
              //  $html .= self::getCheckboxs($name_field, $options, $value, $name);break;
               // case 'multiple':    $html .= self::getCheckboxs($name_field, $options, $value);break;
                default: break;
            }
            $html .= '</li>';
        }

        return $html;
    }


    public static function getSelect($name_field, $options, $value = null)
    {

        $html = '<div class="selectdiv">';
        $html .= '<select id="' . $name_field . '" class="form-control" name="' . $name_field . '" aria-invalid="false"><option value="">Выбрать</option>';
        for($i = 0; $i < count($options); $i++){
            $html .= '<option value="' . $options[$i]['id'] . '" ';
            if($value == $options[$i]['id'])
                $html .= 'selected';
            $html .= '>' . $options[$i]['name'] . '</option>';
        }

        return $html . '</select></div>';
    }

    public static function getRadio($name_field, $options, $value = null)
    {
        $html = '';

        for($i = 0; $i < count($options); $i++){
            $html .= '<label><input type="radio" value="' . $options[$i]['id'] . '" name="' . $name_field . '"';
            if(intval($value) === intval($options[$i]['id'])){ $html .= ' checked';}
            $html .= '> ' . $options[$i]['name'] . '</label> ';
        }

        return $html;
    }

    public static function getCheckbox($name_field, $options, $value = null, $name = null)
    {
        $html = '';

        // if(!empty($value[$name])){
        //     print_r($value[$name]);
        // }

        // die;

        for($i = 0; $i < count($options); $i++){
            $html .= '<label><input type="checkbox" value="' . $options[$i]['id'] . '" name="' . $name_field . '"';
            if(!empty($value[$name]) && in_array($options[$i]['id'], $value[$name])){ $html .= ' checked';}
            $html .= '> ' . $options[$i]['name'] . '</label> ';
        }

        return $html;
    }

    public static function getNumber($name_field, $value = '')
    {
        return '<input type="number" class="form-control" name="' . $name_field . '" value="' . $value . '">';
    }

    public static function getText($name_field, $value = '')
    {
        return '<input type="text" class="form-control" name="' . $name_field . '" value="' . $value . '">';
    }
}
