<?php

namespace common\widgets;

use Yii;
use yii\widgets\Breadcrumbs as BaseBreadcrumbs;

class Breadcrumbs extends BaseBreadcrumbs
{
	public $options = ['class' => 'breadcrumb', 'itemscope itemtype' => 'https://schema.org/BreadcrumbList'];
	public $itemTemplate = "<li itemprop='itemListElement' itemscope itemtype='https://schema.org/ListItem'>{link}</li>\n";
    public $activeItemTemplate = "<li itemprop='itemListElement' itemscope itemtype='https://schema.org/ListItem' class='active'>{link}</li>\n";
}
