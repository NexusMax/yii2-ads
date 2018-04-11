<?php

namespace backend\controllers;

use Yii;
use backend\models\Ads;
use backend\models\SendMessage;
use backend\models\Categories;
use backend\models\FieldValue;
use backend\models\AdsHasImage;
use common\models\User;
use backend\controllers\MainController;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\data\Sort;
use yii\data\Pagination;


class AdsController extends MainController
{

    public function actionIndex()
    {
        $this->view->title = 'Обьявления';
        $this->view->params['breadcrumbs'][] = $this->view->title; 

        if (Yii::$app->request->isAjax) {
            $ads = Ads::find()->where(['id' => Yii::$app->request->post('id')])->one();
            $ads->active = Yii::$app->request->post('checkbox_active');
            $ads->save();
            return;
        }

         $sort = new Sort([
            'defaultOrder' => ['id' => SORT_DESC],
            'attributes' => [
                'name' => [
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Название',
                ],
                'id' => [
                    'asc' => ['id' => SORT_ASC],
                    'desc' => ['id' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => '№',
                ],
                'user' => [
                    'asc' => ['user_id' => SORT_ASC],
                    'desc' => ['user_id' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Пользователь',
                ],
                'created_at' => [
                    'asc' => ['created_at' => SORT_ASC],
                    'desc' => ['created_at' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Дата',
                ],
                'category' => [
                    'asc' => ['category_id' => SORT_ASC],
                    'desc' => ['category_id' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Категория',
                ],
                'active' =>[
                    'asc' => ['active' => SORT_ASC],
                    'desc' => ['active' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Активность',
                ],
            ],
        ]);


        $query = Ads::find()->asArray()->orderBy($sort->orders)->with('user')->with('category')->with('oncePromotion')->with('mainImg');
        if(!empty(Yii::$app->request->get('q')))
            $query = $query->where(['id' => Yii::$app->request->get('q')]);

        if(!empty(Yii::$app->request->get('name')))
            $query = $query->andFilterWhere(['like', 'name', Yii::$app->request->get('name')]);

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
        $ads = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

  

        // echo '<pre>';
        // print_r($ads);die;
        return $this->render('index', compact('ads', 'pages', 'model', 'promotion', 'sort'));
    }



    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $this->view->title = 'Создание обьявлений';
        $this->view->params['breadcrumbs'][] = ['label' => 'Обьявления', 'url' => ['index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        $model = new Ads();


        if ($model->load(Yii::$app->request->post())) {

            if($model->save()){
                
                foreach ($model->ads_has_image as $key) {
                	$ads_has_image = new AdsHasImage();
                	$ads_has_image->ads_id = $model->id;
                    $ads_has_image->type = $key;
                    $ads_has_image->created_at = time();
            		$ads_has_image->validity_at = strtotime('+7 day');
            		$ads_has_image->save();
                }


                $fieldValue = new FieldValue();
                $fieldValue->ads_id = $model->id;
                $fieldValue->value_sub_field = json_encode(Yii::$app->request->post()['Ads']['sub_fields']);
                $fieldValue->save();
            	
               
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'sort' => $sort,
            ]);
        }
    }

    public function actionAddFields()
    {
        if(Yii::$app->request->isAjax){

            $id = Yii::$app->request->post('ads_id');

            $sub_fields = \backend\models\Fields::find()->indexBy('id')->where(['like', 'category_id', ':"-1"'])->orWhere(['like', 'category_id', ':"'.Yii::$app->request->post('category_id').'"'])->asArray()->all();


       

            $values_sub_fields = \backend\models\FieldValue::find()->where(['ads_id' => $id])->asArray()->one();
            $values_sub_fields = json_decode($values_sub_fields['value_sub_field'], true);
            
            $render_html = '';
            foreach ($sub_fields as $sub_field){
                $j_values = array_values(json_decode($sub_field['value_sub_field'], true));
                $j_keys = array_keys(json_decode($sub_field['value_sub_field'], true));


                if($sub_field['type'] == 'text' || $sub_field['type'] == 'number'){
                    $form_control = 'form-control';

                    $required = '';
                    if($sub_field['required'] == '1'){
                        $required = 'required="required"';
                    }

                    if(in_array($sub_field['name_field'], array_keys($values_sub_fields)))
                        $render_html .= '<div class="col-md-6 inputs_js"><div class="wrap-inputs"><div class="form-group"><label> ' . $sub_field['name'] . ' </label><input '.$required.' class="' . $form_control . '" name="Ads[sub_fields][' . $sub_field['name_field'] . ']" type="' . $sub_field['type'] . '"  value="'.$values_sub_fields[$sub_field['name_field']].'"></div></div></div>';
                }
                elseif($sub_field['type'] == 'radio' || $sub_field['type'] == 'checkbox'){

                    $required = '';
                    if($sub_field['required'] == '1'){
                        $required = 'required="required"';
                    }

                    $j_sub_files = '';
                    for($j = 0; $j < count($j_values); $j++)
                        if($sub_field['type'] == 'radio')
                            if(in_array($j_keys[$j], $values_sub_fields))
                                $j_sub_files .= '<label><input '.$required.' name="Ads[sub_fields]['. $j_values[$j] . ']" type="'. $sub_field['type'] . '" value="'.$j_keys[$j].'" checked> '. $j_keys[$j] . '</label>';
                            else $j_sub_files .= '<label><input '.$required.' name="Ads[sub_fields]['. $j_values[$j] . ']" type="'. $sub_field['type'] . '" value="'.$j_keys[$j].'"> '. $j_keys[$j] . '</label>';
                        else                    
                            if(in_array($j_values[$j], array_keys($values_sub_fields)))
                                $j_sub_files .= '<label><input '. $required .' name="Ads[sub_fields]['. $j_values[$j] . ']" type="'. $sub_field['type'] . '" value="'.$j_keys[$j].'" checked> '. $j_keys[$j] . '</label>';
                            else $j_sub_files .= '<label><input '. $required .' name="Ads[sub_fields]['. $j_values[$j] . ']" type="'. $sub_field['type'] . '" value="'.$j_keys[$j].'"> '. $j_keys[$j] . '</label>';

                    
                    
                    $render_html .= '<div class="col-md-6 inputs_js"><div class="wrap-inputs"><div class="form-group"><div><label>' . $sub_field['name'] .' </label></div> '.$j_sub_files.' </div></div></div>';
                }
                elseif($sub_field['type'] == 'select'){

                    $required = '';
                    if($sub_field['required'] == '1'){
                        $required = 'required="required"';
                    }

                    $j_sub_files = '<select '.$required.' class="form-control" name="Ads[sub_fields]['. $sub_field['name_field'] . ']">';
                    for($j = 0; $j < count($j_values); $j++){
                        if(strcmp($j_values[$j], $values_sub_fields[$sub_field['name_field']]) == 0)
                            $j_sub_files .= '<option value="'. $j_values[$j] .'" selected> '. $j_keys[$j] . '</option>';
                        else $j_sub_files .= '<option value="'. $j_values[$j] .'"> '. $j_keys[$j] . '</option>';
                    }
                    $j_sub_files .= '</select>';
                    
                    $render_html .= '<div class="col-md-6 inputs_js"><div class="wrap-inputs"><div class="form-group"><div><label> '. $sub_field['name'] .' </label></div> '. $j_sub_files. ' </div></div></div>';

                }
                elseif($sub_field['type'] == 'select["multiple" => true]'){

                    $required = '';
                    if($sub_field['required'] == '1'){
                        $required = 'required="required"';
                    }

                    $j_sub_files = '<select '.$required.' class="form-control" name="Ads[sub_fields]['. $sub_field['name_field'] . '][]" multiple="multiple">';
                    for($j = 0; $j < count($j_values); $j++){
                        if(in_array($j_values[$j], $values_sub_fields[$sub_field['name_field']]))
                            $j_sub_files .= '<option value="'. $j_values[$j] .'" selected> '. $j_keys[$j] . '</option>';
                        else $j_sub_files .= '<option value="'. $j_values[$j] .'"> '. $j_keys[$j] . '</option>';
                    }
                    $j_sub_files .= '</select>';
                    
                    $render_html .= '<div class="col-md-6 inputs_js"><div class="wrap-inputs"><div class="form-group"><div><label> '. $sub_field['name'] .' </label></div> '. $j_sub_files. ' </div></div></div>';
                }
            }
            print_r($render_html);
        }
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // echo '<pre>';
        // print_r($model->promotion->top);
        // die;
        foreach (AdsHasImage::find()->select('type')->where(['ads_id' => $id])->asArray()->indexBy('type')->all() as $key) {
            $model->ads_has_image[] = $key['type'];
        }

        if ($model->load(Yii::$app->request->post())) {

            if($model->save()){

                // $ads_has_image = AdsHasImage::find()->where(['ads_id' => $id])->all();
                // if(!empty($ads_has_image)){
                //     foreach ($ads_has_image as $key) {
                //         $key->delete();
                //     }
                // }

                $promo_arr = [];
                if(!empty($model->top_) || !empty($model->vip) || !empty($model->up) || !empty($model->fire) || !empty($model->once_up)){
                    $time_promo = AdsHasImage::getTimePromo();
                    if(!empty($model->top_))
                        $promo_arr[] = [$model->id, 3, time(), $time_promo[$model->top_], $model->top_];
                    if(!empty($model->vip))
                        $promo_arr[] = [$model->id, 2, time(), $time_promo[$model->vip], $model->vip];
                    if(!empty($model->up))
                        $promo_arr[] = [$model->id, 4, time(), $time_promo[$model->up], $model->up];
                    if(!empty($model->fire))
                        $promo_arr[] = [$model->id, 5, time(), strtotime('+7 day'), $model->fire];
                    if(!empty($model->once_up)){
                        $model->validity_at = strtotime('+30 day');
                        $model->save(false);
                        $promo_arr[] = [$model->id, 6, time(), strtotime('+30 day'), $model->once_up];
                    }
                    // echo '<pre>';print_r($promo_arr);die;
                    Yii::$app->db->createCommand()->batchInsert('jandoo_ads_has_images', ['ads_id', 'type', 'created_at', 'validity_at', 'type_time'], $promo_arr)->execute();
                }

              
                $del = FieldValue::find()->where(['ads_id' => $id])->one();
                if(!empty($del)) $del->delete();

                $fieldValue = new FieldValue();
                $fieldValue->ads_id = $model->id;
                $fieldValue->value_sub_field = json_encode(Yii::$app->request->post()['Ads']['sub_fields']);
                $fieldValue->save();
                
                
                return $this->redirect(['view', 'id' => $model->id]);
            }

        } else {
            return $this->render('update', compact('model', 'sub_fields'));
        }
    }

    /**
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);

        // echo '<pre>';
        // print_r($model->image);
        // die;
        // if(!empty($model->image)){
        //     @unlink('/home/sago3/sago.in.ua/temp9/web/uploads/ads/'. $model->image);
        // }
        $ads_has_image = AdsHasImage::find()->where(['ads_id' => $id])->all();
        $sub_field = FieldValue::find()->where(['ads_id' => $id])->one();
        if(!empty($sub_field)){
            
                $sub_field->delete();
            
          
        }
        if(!empty($ads_has_image)){
        	foreach ($ads_has_image as $key) {
        		$key->delete();
        	}
        }
        
        $model->removeImages();
        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteAjax()
    {
        $id = Yii::$app->request->post('id');
        $model = $this->findModel($id);

        // echo '<pre>';
        // print_r($model->image);
        // die;
        // if(!empty($model->image)){
        //     @unlink('/home/sago3/sago.in.ua/temp9/web/uploads/ads/'. $model->image);
        // }
        $ads_has_image = AdsHasImage::find()->where(['ads_id' => $id])->all();
        $sub_field = FieldValue::find()->where(['ads_id' => $id])->one();
        if(!empty($sub_field)){
            
                $sub_field->delete();
            
          
        }
        if(!empty($ads_has_image)){
            foreach ($ads_has_image as $key) {
                $key->delete();
            }
        }
        
        $model->removeImages();
        $model->delete();
    }

    public function actionDeleteImg()
    {
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('key');

            $image = \rico\yii2images\models\Image::find()->where(['id' => $id])->one();

            $str = explode('/', $image->filePath);
            $alias = Yii::getAlias('@appWeb') . '/images/store/' . $image->filePath;
            $alias_mini = Yii::getAlias('@appWeb') . '/images/store/' . $str[0] . '/' . $str[1] . '/mini_' . $str[2];
            @unlink($alias);
            @unlink($alias_mini);

            $image->delete();

            return true;
        }

        return false;
    }

    public function actionSendMessage()
    {
         if (Yii::$app->request->isAjax) {

            if(Yii::$app->request->post('user_id')){
            	$user = User::findIdentity(Yii::$app->request->post('user_id'));
				$arr = array('username' => $user->username, 'lastname' => $user->lastname, 'email' => $user->email);

				echo json_encode($arr);
            }
            if(Yii::$app->request->post('email')){
            	$text = Yii::$app->request->post('message') . "\n" . Yii::$app->request->post('sub');
            	Yii::$app->mailer->compose()
				    ->setFrom(Yii::$app->params['adminEmail'])
				    ->setTo(Yii::$app->request->post('email'))
				    ->setSubject(Yii::$app->request->post('tema'))
				    ->setTextBody($text)
				    ->send();

                $redirect_url = Yii::$app->request->post('redirect');
				Yii::$app->session->setFlash('success', 'Сообщение успешно отправлено');
            	return $this->redirect([$redirect_url]);
            }

        }
    }

    /**
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ads::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
