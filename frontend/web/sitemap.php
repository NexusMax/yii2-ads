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

class SiteMap{

	private $path;
	private $siteName;
	private $priorityList;
	private $mainPage;
	private $category;
	private $pages;
	private $ads;
	private $categoryDb;
	private $adsDb;


	public function __construct()
	{	
		$this->path = __DIR__;
		$this->siteName = $_SERVER['HTTPS'] ? 'https://' . $_SERVER['HTTP_HOST'] . '/' : 'http://' . $_SERVER['HTTP_HOST'] . '/';
		$this->priorityList = [
        	'priorityOne' => '1.0',
        	'priorityTwo' => '0.9',
        	'priorityThree' => '0.8',
        	'priorityFour' => '0.5',
        ];
		$this->mainPage = [
 			['loc' => $this->siteName, 'priority' => $this->priorityList['priorityOne']],
 		];
		$this->category = [
 			['loc' => $this->siteName . 'category/', 'priority' => $this->priorityList['priorityTwo']],
 			['loc' => $this->siteName . 'category/all-vip', 'priority' => $this->priorityList['priorityTwo']],
 		];
		$this->pages = [
 			['loc' => $this->siteName . 'how-it-work', 'priority' => $this->priorityList['priorityThree']],
 			['loc' => $this->siteName . 'important-know', 'priority' => $this->priorityList['priorityThree']],
 			['loc' => $this->siteName . 'advertising', 'priority' => $this->priorityList['priorityThree']],
 			['loc' => $this->siteName . 'blog', 'priority' => $this->priorityList['priorityThree']],
 			['loc' => $this->siteName . 'rules', 'priority' => $this->priorityList['priorityThree']],
 			['loc' => $this->siteName . 'security', 'priority' => $this->priorityList['priorityThree']],
 			['loc' => $this->siteName . 'security', 'priority' => $this->priorityList['priorityThree']],
 		];

		$this->categoryDb = (new \yii\db\Query())->select('id, alias, parent_id')->from('jandoo_categories')->where('active = 1')->orderBy('id ASC')->indexBy('id')->all();
		$this->adsDb = (new \yii\db\Query())->select('id, alias, updated_at')->from('jandoo_ads')->where('active = 1')->orderBy('id ASC')->indexBy('id')->all();

		$this->setCategory();
		$this->setAds();

	}

	public function run()
	{
		$sitemap = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

 		foreach ($this->getAll() as $key => $value) {
    
        	foreach ($value as $key => $value) {

        		$sitemap .= '<url>';

        		foreach ($value as $key => $value)
        			$sitemap .= '<' . $key . '>' . $value . '</' . $key . '>';

        		$sitemap .= '</url>';
        	}
        	
        }

 		$sitemap .= '</urlset>';

 		$this->setFile($sitemap);
	}

	private function setCategory()
	{
		foreach ($this->categoryDb as $key => $value)
        	if(!empty($this->categoryDb[$value['parent_id']]))
        		$this->category[] = ['loc' => $this->siteName . 'category/' . $this->categoryDb[$value['parent_id']]['alias'] . '/' . $value['alias'], 'priority' => $this->priorityList['priorityTwo']];
        	else
        		$this->category[] = ['loc' => $this->siteName . 'category/' . $value['alias'], 'priority' => $this->priorityList['priorityTwo']];
	}

	private function setAds()
	{
		foreach ($this->adsDb as $key => $value)
        	$this->ads[] = ['loc' => $this->siteName . 'ads/' . $value['alias'], 'lastmod' => date('Y-m-d', $value['updated_at']), 'priority' => $this->priorityList['priorityFour']];
	}

	private function getAll()
	{
		return [$this->mainPage, $this->category, $this->pages, $this->ads];
	}
       
    private function setFile($sitemap)
    {
		if(file_exists($this->path . '/sitemap.xml'))
			@unlink($this->path . '/sitemap.xml');

		$fp = fopen($this->path . "/sitemap.xml", "w");
		fwrite($fp, $sitemap);
		fclose($fp);
    }
 		
}

$siteMap = new SiteMap();
$siteMap->run();
