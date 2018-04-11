<?php

namespace frontend\controllers;

use Yii;
use frontend\models\MagazineOrder;
use frontend\models\MagazineOrderItem;
use frontend\models\MagazineAds;

use yz\shoppingcart\ShoppingCart;

class CartController extends \yii\web\Controller
{

    public function actionAdd($id)
    {
        $product = MagazineAds::findOne($id);
        if ($product) {
            \Yii::$app->cart->put($product);

            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionList()
    {
        $this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';

        $cart = \Yii::$app->cart;
        $total = $cart->getCost();
        $magazineProduct = $this->sortMagazineProduct();
        $magazineIds = implode(', ', array_keys($magazineProduct));
        if(!empty($magazineIds)){
            $magazines = Yii::$app->db->createCommand('SELECT id,name FROM jandoo_magazine WHERE id IN (' . $magazineIds . ')')->queryAll();

            foreach ($magazines as $key => $value) {
                $magazines[$value['id']] = $value;
                unset($magazines[$key]);
            }
        }


        return $this->render('list', [
           'magazineProduct' => $magazineProduct,
           'total' => $total,
           'magazines' => $magazines,
        ]);
    }

    public function actionRemove($id)
    {
        $product = MagazineAds::findOne($id);
        if ($product) {
            \Yii::$app->cart->remove($product);
            $this->redirect(['cart/list']);
        }
    }

    public function actionUpdate($id, $quantity)
    {
        $product = MagazineAds::findOne($id);
        if ($product) {
            \Yii::$app->cart->update($product, $quantity);
            $this->redirect(['cart/list']);
        }
    }


    public function actionOrder($id)
    {
        $this->view->title = 'Jandooo - Портал бесплатных объявлений в Украине';
        
        $order = new MagazineOrder();
        $products = $this->getMagazineProduct($id);

        $order->magazine_id = $id;

        if ($order->load(\Yii::$app->request->post()) && $order->validate()) {

            $transaction = $order->getDb()->beginTransaction();
            $order->save(false);
            foreach($products as $product) {
                $orderItem = new MagazineOrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->name = $product->name;
                $orderItem->price = $product->getPrice();
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $product->getQuantity();
                if (!$orderItem->save(false)) {
                    $transaction->rollBack();
                    \Yii::$app->session->addFlash('error', 'Невозможно оформить заказ. Пожалуйста свяжитесь с нами.');
                    return $this->redirect('/myaccount/index');
                }
                \Yii::$app->cart->remove($product);
            }
            $transaction->commit();

            \Yii::$app->session->addFlash('success', 'Спасибо за заказ. Мы скоро свяжемся с вами.');
            $order->sendEmail();
            return $this->redirect('/myaccount/index');
        }
        return $this->render('order', [
            'order' => $order,
            'products' => $products,
            'total' => $total,
        ]);
    }

    public function sortMagazineProduct()
    {
        $cart = \Yii::$app->cart;
        $products = $cart->getPositions();

        $magazineProduct = [];
        foreach ($products as $key => $value) {
            $magazineProduct[$value->magazine_id][] = $products[$key];
        }
        return $magazineProduct;
    }

    public function getMagazineProduct($id)
    {
        return $this->sortMagazineProduct()[$id];
    }
}