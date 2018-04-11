<?php

namespace frontend\models;

use Yii;

use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;


/**
 * This is the model class for table "{{%magazine_has_categories}}".
 *
 * @property integer $id
 * @property integer $magazine_id
 * @property integer $parent_id
 * @property string $alias
 * @property string $name
 * @property integer $active
 * @property integer $sort
 * @property integer $created_at
 * @property integer $updated_at
 */
class MagazineHasCategories extends \yii\db\ActiveRecord
{
    public $imageFile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%magazine_has_categories}}';
    }

    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ],
            TimestampBehavior::className(),
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'alias'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['magazine_id', 'parent_id', 'active', 'sort', 'created_at', 'updated_at'], 'integer'],
            [['alias', 'name', 'magazine_id'], 'required'],
            ['magazine_id', 'exist', 'targetClass' => Magazine::className(), 'targetAttribute' => 'id'],
            [['alias', 'name'], 'string', 'max' => 255],
            [['name'], 'unique', 'targetAttribute' => ['magazine_id', 'name', 'parent_id']],
            ['active', 'default', 'value' => 1],
            [['sort', 'parent_id'], 'default', 'value' => 0],
            [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'magazine_id' => 'Магазин',
            'parent_id' => 'Родительская категория',
            'alias' => 'Алиас',
            'name' => 'Название',
            'active' => 'Активность',
            'sort' => 'Порядковый номер',
            'created_at' => 'Дата создания',
            'updated_at' => 'Последнее обновление',
            'imageFile' => 'Изображение',
        ];
    }


    public function getMagazin()
    {
        return $this->hasOne(Magazine::className(), ['id' => 'magazine_id']);
    }

    public function getMainImage()
    {
        return $this->hasOne(\rico\yii2images\models\Image::className(), ['itemId' => 'id'])->andWhere(['modelName' => 'MagazineHasCategories']);
    }

    public function getParent()
    {
        return $this->hasOne(MagazineHasCategories::className(), ['id' => 'parent_id']);
    }

    public function upload()
    {
        if ($this->validate()) {

            $alias = Yii::getAlias('@appWeb') . '/uploads/magazinecategory/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($alias);

            $this->removeImages();
            $this->attachImage($alias, true);
            @unlink($alias);

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes){
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');

        if(!empty($this->imageFile))
            $this->upload();

        parent::afterSave($insert, $changedAttributes);
    }


    public function setSort()
    {   
        if(empty($this->sort)){
            $sort = MagazineHasCategories::find()->where(['magazine_id' => $this->magazine_id])->andWhere(['parent_id' => $this->parent_id])->andWhere('active = 1')->orderBy('sort DESC')->asArray()->one();
            $this->sort = $sort['sort'] + 1;
        }else{
            $categories = MagazineHasCategories::find()->where(['magazine_id' => $this->magazine_id])->andWhere(['>=', 'sort', $this->sort])->andWhere(['parent_id' => $this->parent_id])->andWhere('active = 1')->all();

            if(!empty($categories)){
                $i = 0;
                foreach ($categories as $key) {
                    $key->sort = $this->sort + ++$i;
                    $key->save(false);
                }
            }
        }
    }

}
