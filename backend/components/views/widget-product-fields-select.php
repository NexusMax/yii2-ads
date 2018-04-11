<option value="<?= $category['id'] ?>"
    <?php if(in_array($category['id'], $this->model->category_id)) echo ' selected'; ?>>
    <?= $tab . $category['name'] ?></option>
<?php if(isset($category['childs'])): ?>
    <ul>
        <?= $this->getCategoryHtml($category['childs'], $tab . '    *   '); ?>
    </ul>
<?php endif; ?>