<li data-category-id="<?= $category['id'] ?>" data-category-alias="<?= $category['alias'] ?>"
    <?php if(strcmp($category['alias'],  Yii::$app->controller->action->controller->actionParams['cat']) == 0 || strcmp($category['alias'],  Yii::$app->controller->action->controller->actionParams['subcat']) == 0){ Yii::$app->params['Category']['selected_id'] = $category['id']; echo ' class="active"'; }?>>
    	<a href="#"><?= $category['name'] ?></a>
<?php if(isset($category['childs'])): ?>
    <ul>
        <?= $this->getCategoryHtml($category['childs']); ?>
    </ul>
<?php endif; ?>
</li>