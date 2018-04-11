<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%magazine_order_item}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $name
 * @property double $price
 * @property integer $product_id
 * @property integer $quantity
 */
class MagazineOrderItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%magazine_order_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'product_id', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 255],
            ['quantity', 'default', 'value' => 1],
            [['product_id'], 'unique', 'targetAttribute' => ['product_id', 'order_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Заказ',
            'name' => 'Название',
            'price' => 'Цена',
            'product_id' => 'Товар',
            'quantity' => 'Количество',
        ];
    }


    public function getOrder()
    {
        return $this->hasOne(\frontend\models\MagazineOrder::className(), ['id' => 'order_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(\frontend\models\MagazineAds::className(), ['id' => 'product_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if($this->isNewRecord){
                $this->name = $this->product->name;
                $this->price = $this->product->price;
            }else{
                $old_item = self::find()->where(['id' => $this->id])->asArray()->one();
                if($this->price != $old_item['price']){
                    $this->price = $old_item['price'];
                }
            }

            return true;
        } else {
            return false;
        }
    }

}
