<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "{{%attr_fields}}".
 *
 * @property int $id
 * @property string $name
 * @property string $name_field
 * @property int $type_id
 * @property int $active
 * @property int $required
 * @property int $search
 * @property int $sort
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AttrFieldsTypes $type
 * @property AttrFieldsHasCategories[] $attrFieldsHasCategories
 * @property AttrFieldsOptions[] $attrFieldsOptions
 * @property AttrFieldsValues[] $attrFieldsValues
 */
class AttrField extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attr_fields}}';
    }

     /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'name_field'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type_id'], 'required'],
            [['type_id', 'active', 'required', 'search', 'sort', 'created_at', 'updated_at'], 'integer'],
            [['name', 'name_field'], 'string', 'max' => 45],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AttrType::className(), 'targetAttribute' => ['type_id' => 'id']],
            [['active', 'required'], 'default', 'value' => 1],
            [['sort', 'search'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'name_field' => Yii::t('app', 'Name Field'),
            'type_id' => Yii::t('app', 'Type ID'),
            'active' => Yii::t('app', 'Active'),
            'required' => Yii::t('app', 'Required'),
            'search' => Yii::t('app', 'Search'),
            'sort' => Yii::t('app', 'Sort'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(AttrType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttrCategories()
    {
        return $this->hasMany(AttrCategory::className(), ['field_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttrOption()
    {
        return $this->hasMany(AttrOption::className(), ['field_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttrValue()
    {
        return $this->hasMany(AttrValue::className(), ['field_id' => 'id'])->inverseOf('field');
    }

    public function afterSave($insert, $changedAttributes){

        if($insert){
            $this->name_field = $this->name_field . '-' . $this->id;
            $this->save();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public static function getHtmlFields($fields, $name = false)
    {
        $html = '';
        for($i = 0; $i < count($fields); $i++){

            ($name === true) ? $name_field = $fields[$i]['fieldsWithOptions']['name_field'] : $name_field = 'AttrField[' . $fields[$i]['fieldsWithOptions']['name_field'] . '][]';
            $options = $fields[$i]['fieldsWithOptions']['attrOption'];

            $html .= '<div class="form-group field-' . $name_field . '">';
            $html .= '<label class="control-label" for="' . $name_field . '">' . $fields[$i]['fieldsWithOptions']['name'] . '</label>';

            switch($fields[$i]['fieldsWithOptions']['type']['name']){
                case 'select':      $html .= self::getSelect($name_field, $options);break;
                case 'number':      $html .= self::getNumber($name_field);break;
                case 'text':        $html .= self::getText($name_field);break;
                case 'radio':       $html .= self::getRadio($name_field, $options);break;
                case 'checkbox':    $html .= self::getCheckbox($name_field, $options);break;
                case 'multiple':    $html .= self::getCheckbox($name_field, $options);break;
                default: break;
            }

            $html .= '<div class="help-block"></div>';
            $html .= '</div>';
        }

        return $html;
    }

    public static function getSelect($name_field, $options)
    {
        $html = '<div class="selectdiv">';
        $html .= '<select id="' . $name_field . '" class="form-control" name="' . $name_field . '" aria-invalid="false"><option value="">Выбрать</option>';
        for($i = 0; $i < count($options); $i++){
            $html .= '<option value="' . $options[$i]['id'] . '" ';
            if(Yii::$app->request->get($name_field) == $options[$i]['id'])
                $html .= 'selected';
            $html .= '>' . $options[$i]['name'] . '</option>';
        }

        return $html . '</select></div>';
    }

    public static function getRadio($name_field, $options)
    {
        $html = '';

        for($i = 0; $i < count($options); $i++){
            $html .= '<label><input type="radio" value="' . $options[$i]['id'] . '" name="' . $name_field . '"> ' . $options[$i]['name'] . '</label> ';
        }

        return $html;
    }

    public static function getCheckbox($name_field, $options)
    {
        $html = '';

        for($i = 0; $i < count($options); $i++){
            $html .= '<label><input type="checkbox" value="' . $options[$i]['id'] . '" name="' . $name_field . '"> ' . $options[$i]['name'] . '</label> ';
        }

        return $html;
    }

    public static function getNumber($name_field)
    {
        return '<input type="number" class="form-control" name="' . $name_field . '">';
    }

    public static function getText($name_field)
    {
        return '<input type="text" class="form-control" name="' . $name_field . '">';
    }
}
