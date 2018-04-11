<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property integer $price
 * @property string $hot
 * @property string $image
 * @property string $image_2
 * @property string $image_3
 * @property string $image_4
 * @property string $image_5
 * @property integer $rating
 * @property string $new_arrival
 * @property string $best_seller
 * @property string $special_offer
 * @property integer $category_id
 * @property integer $status
 * @property string $keyword
 */
class Product extends \yii\db\ActiveRecord
{
    public $image;
    public $gallery;

    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ]
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'best_seller', 'special_offer', 'category_id', 'keyword'], 'required'],
            [['description', 'hot', 'keyword'], 'string'],
            [['price', 'rating', 'new_arrival', 'best_seller', 'special_offer', 'category_id', 'status'], 'integer'],
            [['name', 'image_2'], 'string', 'max' => 256],
            [['image_3', 'image_4', 'image_5'], 'string', 'max' => 255],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['gallery'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 4],
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
            'description' => 'Description',
            'price' => 'Price',
            'hot' => 'Hot',
            'image' => 'Image',
            'image_2' => 'Image 2',
            'image_3' => 'Image 3',
            'image_4' => 'Image 4',
            'image_5' => 'Image 5',
            'rating' => 'Rating',
            'new_arrival' => 'New Arrival',
            'best_seller' => 'Best Seller',
            'special_offer' => 'Special Offer',
            'category_id' => 'Category ID',
            'status' => 'Status',
            'keyword' => 'Keyword',
        ];
    }
    public function upload()
    {
        if($this->validate()){
            $path = 'images/product/store/' . $this->image->baseName . '.' . $this->image->extension;
            $this->image->saveAs($path);
            $this->attachImage($path, true);
            //@unlink($path);
            return true;
        }else{
            return false;
        }
    }
    public function uploadGallery()
    {
        if($this->validate()){
            foreach ($this->gallery as $file){
                $path = 'images/product/store/' . $file->baseName . '.' . $file->extension;
                $file->saveAs($path);
                $this->attachImage($path);
                //@unlink($path);
            }

            return true;
        }else{
            return false;
        }
    }
}
