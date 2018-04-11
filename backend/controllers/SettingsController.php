<?php

namespace backend\controllers;

use Yii;
use backend\controllers\MainController;
use backend\models\Settings;

class SettingsController extends MainController
{

    public function beforeAction($action)
    {
        if ($this->action->id == 'save')
            Yii::$app->controller->enableCsrfValidation = false;


        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $this->view->title = "Настройки сайта";

//         $dom_tekst = \backend\models\Categories::find()->where(['parent_id' => '105'])->all();
//         $dom_int = \backend\models\Categories::find()->where(['id' => '130'])->all();
// die;
//     			foreach ($dom_tekst as $val) {
//     				$sub_cat = new \backend\models\Categories();

//     				$sub_cat->parent_id = 130;
//     				$sub_cat->alias = $val->alias;
//     				$sub_cat->name = $val->name;
//     				$sub_cat->active = $val->active;
//     				$sub_cat->sort = $val->sort;
//     				$sub_cat->save();
//     			}
        // echo '<pre>';
        // print_r($dom_int);
        // die;

        $switch = Settings::find()->where(['id' => 1])->one();

        return $this->render('index',[
            'switch' => $switch,
        ]);
    }

    public function actionAlias()
    {

    	$posts = Yii::$app->db->createCommand('SELECT * FROM sago3_temp9.jandoo_categories WHERE alias IN (
SELECT alias FROM sago3_temp9.jandoo_categories GROUP BY alias HAVING count(*)>1)')
            ->queryAll();

            // foreach ($posts as $key) {
            // 	$cat = \backend\models\Categories::find()->where(['id' => $key['id']])->one();
            // 	$cat->alias = $cat->str2url($cat->name);
            // 	$cat->save();
            // }

            // echo '<pre>';
            // print_r($posts);die;
    	return $this->render('index');
    }

    public function actionSave()
    {
        if(Yii::$app->request->isAjax){
            $status = Yii::$app->request->post('status');

            $switch = Settings::find()->where(['id' => 1])->one();

            echo $status;

            if($status === 'true')
                $switch->active = 1;
            else
                $switch->active = 0;

            $switch->save();

        }
    }
}