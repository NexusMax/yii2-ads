<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use yii\web\UploadedFile;

class MagazineCategory extends \yii\db\ActiveRecord
{
    public $imageFile;

    public static function tableName()
    {
        return '{{%magazine_categories}}';
    }


    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'alias'
            ],
        ];
    }


    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'active'], 'integer'],
            ['active', 'default', 'value' => 1],
            [['name', 'alias', 'image'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'alias' => 'Алиас',
            'image' => 'Изображение',
            'active' => 'Активность',
            'created_at' => 'Дата создания',
            'updated_at' => 'Последнее обновление',

        ];
    }



    public function afterSave($insert, $changedAttributes)
    {
        $this->upload();

        parent::afterSave($insert, $changedAttributes);
    }

    public function upload()
    {
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
        if(!empty($this->imageFile)){

            if ($this->validate()) {
                $alias = Yii::getAlias('@appWeb') . '/uploads/magazinecategories/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
                $this->imageFile->saveAs($alias);
                $this->image = $this->imageFile->baseName . '.' . $this->imageFile->extension;
                $this->save(false);


                // @unlink($alias);
                return true;
            }
        }
        return false;
    }

    public static function deleteImg($id)
    {
        $model = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
        echo Yii::$app->user->id;
        
        $images = $model->getImages();
            foreach($images as $image){
                    if($image->id === $id){
                            $model->removeImage($image);
                            break;
                    }
            }
    }


    public function getImage()
    {
        return '/web/uploads/magazinecategories/' . $this->image;
    }

    public function getInitImage($urldel = '/admin/magazine-categories/imgdel/')
    {
        $return_json = [];

        $return_json[] = [
          'caption'=> $this->getImage(),
          'width'=> '100px',
          'url'=> $urldel,
          'key'=> $this->id,
          'extra'=>['id'=>$this->id]
        ];


      return $return_json;
    }
   
}