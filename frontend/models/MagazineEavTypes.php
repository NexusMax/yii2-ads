<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%magazine_eav_types}}".
 *
 * @property integer $id
 * @property string $name
 */
class MagazineEavTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%magazine_eav_types}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 45],
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
        ];
    }
}
