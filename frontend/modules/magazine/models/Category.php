<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $id_parent
 * @property string $name
 * @property integer $sort_order
 * @property integer $status
 * @property string $keyword
 * @property string $description
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'id_parent']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_parent', 'sort_order', 'status'], 'integer'],
            [['name', 'keyword', 'description'], 'required'],
            [['keyword', 'description'], 'string'],
            [['name'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер категории',
            'id_parent' => 'Родительская категория',
            'name' => 'Название категории',
            'sort_order' => 'Sort Order',
            'status' => 'Статус',
            'keyword' => 'Ключевые слова',
            'description' => 'Описание',
        ];
    }
}
