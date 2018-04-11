<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%attr_fields_values}}".
 *
 * @property int $id
 * @property string $value
 * @property int $ad_id
 * @property int $field_id
 * @property int $option_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AttrFieldsOptions $option
 * @property Ads $ad
 * @property AttrFields $field
 */
class AttrValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attr_fields_values}}';
    }

     /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'ad_id', 'field_id'], 'required'],
            [['ad_id', 'field_id', 'option_id', 'created_at', 'updated_at'], 'integer'],
            [['value'], 'string', 'max' => 255],
            [['ad_id'], 'exist', 'targetClass' => Ad::className(), 'targetAttribute' => ['ad_id' => 'id']],
            [['field_id'], 'exist', 'targetClass' => AttrField::className(), 'targetAttribute' => ['field_id' => 'id']],
            [['option_id'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'value' => Yii::t('app', 'Value'),
            'ad_id' => Yii::t('app', 'Ad ID'),
            'field_id' => Yii::t('app', 'Field ID'),
            'option_id' => Yii::t('app', 'Option ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(AttrOption::className(), ['id' => 'option_id'])->inverseOf('attrValue');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAd()
    {
        return $this->hasOne(Ad::className(), ['id' => 'ad_id'])->inverseOf('attrValue');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(AttrField::className(), ['id' => 'field_id'])->inverseOf('attrValue');
    }
}
