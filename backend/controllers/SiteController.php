<?php
namespace backend\controllers;

use Yii;
use backend\controllers\MainController;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends MainController
{

    public function actionIndex()
    {
        $this->view->title = 'Админ панель';

        return $this->render('index');
    }

    public function actionAbout()
    {
        $this->view->title = 'Админ панель';

        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->loginAdmin()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
