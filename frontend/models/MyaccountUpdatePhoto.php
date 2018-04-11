<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use \common\models\User;
/**
 * Login form
 */
class MyaccountUpdatePhoto extends Model
{
    public $imageFile;


    private $_user;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'extensions' => 'png, jpg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'imageFile' => 'Фото',
        ];
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Yii::$app->user;

        }

        return $this->_user;
    }


    public function save()
    {	
    	$this->imageFile = UploadedFile::getInstance($this, 'imageFile');
    	if(!empty($this->imageFile))
	    	if ($this->validate()) {
	        	$user = \common\models\User::find()->where(['id'=>Yii::$app->user->id])->one(); 
	        	$this->imageFile = UploadedFile::getInstance($this, 'imageFile');
	            $alias = Yii::getAlias('@appWeb') . '/uploads/users/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
	            // echo '<pre>';
	            // print_r($alias);die;
	            $this->imageFile->saveAs($alias);

	            $user->removeImages();
	            $user->attachImage($alias, true);
	            @unlink($alias);

	            return true;
	        } else {
	            return false;
	        }
	    else 
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

}
