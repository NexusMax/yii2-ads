<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%magazine_eav_options}}".
 *
 * @property integer $id
 * @property string $field_id
 * @property string $name
 */
class MagazineEavOptions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%magazine_eav_options}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'name'], 'string', 'max' => 45],
            [[ 'field_id'], 'integer'],
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
            'name' => 'Name',
        ];
    }
}
