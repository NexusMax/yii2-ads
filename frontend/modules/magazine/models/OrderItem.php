<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "order_item".
 *
 * @property integer $id
 * @property integer $id_order
 * @property integer $id_product
 * @property string $name
 * @property double $price
 * @property integer $qty_item
 * @property double $sum_item
 */
class OrderItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_item';
    }

    public function getOrder()
    {
        return $this->hasOne(OrderP::className(), ['id' => 'id_order']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_order', 'id_product', 'name', 'price', 'qty_item', 'sum_item'], 'required'],
            [['id_order', 'id_product', 'qty_item'], 'integer'],
            [['price', 'sum_item'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_order' => 'Id Order',
            'id_product' => 'Id Product',
            'name' => 'Name',
            'price' => 'Price',
            'qty_item' => 'Qty Item',
            'sum_item' => 'Sum Item',
        ];
    }
}
