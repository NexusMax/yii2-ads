<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%attr_fields_options}}".
 *
 * @property int $id
 * @property int $field_id
 * @property string $name
 * @property int $sort
 *
 * @property AttrFields $field
 * @property AttrFieldsValues[] $attrFieldsValues
 */
class AttrOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attr_fields_options}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['field_id', 'name'], 'required'],
            [['field_id', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['field_id'], 'exist', 'skipOnError' => true, 'targetClass' => AttrField::className(), 'targetAttribute' => ['field_id' => 'id']],
            [['sort'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'field_id' => Yii::t('app', 'Field ID'),
            'name' => Yii::t('app', 'Name'),
            'sort' => Yii::t('app', 'Sort'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(AttrField::className(), ['id' => 'field_id'])->inverseOf('attrOption');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttrValue()
    {
        return $this->hasMany(AttrValue::className(), ['option_id' => 'id'])->inverseOf('option');
    }
}
