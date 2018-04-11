<?php
ini_set("max_execution_time", 0);

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);

(new yii\web\Application($config));

use frontend\models\Ads;

class Promotion extends \frontend\models\Promotion{

	private $ads;
	private $ads_validity;

	public function __construct()
	{	

		$promotion = (new \yii\db\Query())->select('*')->from('jandoo_ads_has_images')->where(['>', 'validity_at', time()])->andWhere(['!=', 'type', 6])->all();
		$ads_id = array_unique(array_column($promotion, 'ads_id'));

		$this->ads = (new \yii\db\Query())->select('id, validity_at')->from('jandoo_ads')->where(['in', 'id', $ads_id])->indexBy('id')->all();

		$this->ads_validity = (new \yii\db\Query())->select('id, validity_at')->from('jandoo_ads')->where(['<', 'validity_at', time()])->andWhere('active = 1')->indexBy('id')->all();

		foreach ($promotion as $key) {
			$this->ads[$key['ads_id']]['promotion'][] = $key;
		}
	}

	public function run()
	{
		$ads_id = [];
		foreach ($this->ads as $key) {
			
			foreach ($key['promotion'] as $val) {
				
				if(intval($val['type']) === 4){
					$ads_id[] = $key['id'];
				}

			}
		}

		$ads_validity_id = [];
		foreach ($this->ads_validity as $key) {
			$ads_validity_id[] = $key['id'];
		}

		if(!empty($ads_id)){
			Yii::$app->db->createCommand('UPDATE `jandoo_ads` SET `validity_at` = "' . time() . '" WHERE `id` IN ('. implode(array_unique($ads_id), ', ') . ') AND `active` = 1')->execute();
		}
		if(!empty($ads_validity_id)){
			Yii::$app->db->createCommand('UPDATE `jandoo_ads` SET `active` = 0 WHERE `id` IN ('. implode(array_unique($ads_validity_id), ', ') . ')')->execute();
		}
	}
 		
}

$promotion = new Promotion();
$promotion->run();
