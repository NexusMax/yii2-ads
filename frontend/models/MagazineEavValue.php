<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%magazine_eav_value}}".
 *
 * @property integer $id
 * @property string $field_id
 * @property string $option_id
 * @property string $value
 * @property string $product_id
 */
class MagazineEavValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%magazine_eav_value}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['field_id', 'option_id', 'product_id'], 'string', 'max' => 45],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'field_id' => 'Field ID',
            'option_id' => 'Option ID',
            'value' => 'Value',
            'product_id' => 'Product ID',
        ];
    }

    public function getField()
    {
        return $this->hasOne(\frontend\models\MagazineEavFields::className(), ['id' => 'field_id'])->with('type')->with('opts');
    }
}
