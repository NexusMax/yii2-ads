<li <?php if($category['parent_id'] == 0) echo 'class="closed"'; ?>>
    <a href="<?= \yii\helpers\Url::to(['category/view', 'id' => $category['id']]);?>"><?=$category['name']; ?></a>
    <?php if(isset($category['childs'])): ?>
        <ul>
        <?= $this->getCategoryHtml($category['childs']); ?>
        </ul>
    <?php endif; ?>
</li>