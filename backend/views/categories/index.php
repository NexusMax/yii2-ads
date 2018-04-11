<?php
use yii\helpers\Html;

?>
<div class="categories-index">
    <p>
        <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="wrap-inputs">
        <div class="table-wrap">
            <div class="table-inner-wrap">
                <div class="table-header">
                    <div class="header-section header-section-info">
                        <input type="checkbox">
                        <span class="header-number-sort"><?= $sort->link('sort'); ?></span>
                    </div>
                    <div class="header-section header-section-photo">Фото</div>
                    <div class="header-section header-section-name"><?= $sort->link('name') ?></div>
                    <div class="header-section header-section-active"><?= $sort->link('active'); ?></div>
                    <div class="header-section header-section-settings">Действия</div>
                </div>
                
                <?php foreach ($categories as $key): ?>
                   
                        <div class="table-body">
                            <div class="body-section body-section-info">
                            <?php if(isset($key['childs'])): ?>
                                <a href="#" class="child-category" data-id="<?php echo $key['id']; ?>"><i class="fa fa-plus-square"></i></a>
                            <?php endif;?>
                                <span class="body-number-sort"><?php echo $key['sort']; ?></span>
                            </div>
                            <div class="body-section body-section-photo"><?php if(!empty($key['image'])): ?><img src="/web/uploads/categories/<?php echo $key['image'] ?>" alt="admin_img"><?php else: ?><img src="/backend/web/images/noimage-min.jpg" alt="admin_img"><?php endif; ?></div>
                            <div class="body-section body-section-name"><a href="/admin/categories/update?id=<?php echo $key['id']; ?>"><?php echo $key['name'] ?></a></div>
                            <div class="body-section body-section-active">
                                <label class="switch">
                                  <input type="hidden" name="categories" value="<?php echo $key['id']; ?>">
                                  <input type="checkbox" name="checkbox_active" value="<?php echo $key['active']; ?>">
                                  <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="body-section body-section-settings">
                                <a href="/admin/categories/delete?id=<?php echo $key['id']; ?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" class="body-section-settings-delete"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 47.971 47.971" width="20px" height="20px"><path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88   c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242   C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879   s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z" fill="currentColor"></path></svg></a>
                            </div>
                        </div>

                        <?php if(isset($key['childs'])): ?>
                        <div class="hidden-categories" data-wrap-id="<?php echo $key['id']; ?>">
                        <?php foreach ($key['childs'] as $value): ?>
                                       
                            <div class="table-body">
                                <div class="body-section body-section-info">
                                <?php if(isset($value['childs'])): ?>
                                    <a href="#" data-id="<?php echo $value['id']; ?>" class="child-category"><i class="fa fa-plus-square"></i></a>
                                <?php endif;?>
                                <span class="body-number-sort"><?php echo $value['sort']; ?></span>
                                </div>
                                <div class="body-section body-section-photo"><?php if(!empty($value['image'])): ?><img src="/web/uploads/categories/<?php echo $value['image'] ?>" alt="admin_img"><?php else: ?><img src="/backend/web/images/noimage-min.jpg" alt="admin_img"><?php endif; ?></div>
                                <div class="body-section body-section-name body-section-name-level"><a href="/admin/categories/update?id=<?php echo $value['id']; ?>"><?php echo $value['name'] ?></a></div>
                                <div class="body-section body-section-active">
                                    <label class="switch">
                                      <input type="hidden" name="categories" value="<?php echo $value['id']; ?>">
                                      <input type="checkbox" name="checkbox_active" value="<?php echo $value['active']; ?>">
                                      <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="body-section body-section-settings">
                                    <a href="/admin/categories/delete?id=<?php echo $value['id']; ?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" class="body-section-settings-delete"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 47.971 47.971" width="20px" height="20px"><path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88   c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242   C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879   s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z" fill="currentColor"></path></svg></a>
                                </div>
                            </div>

                            <div class="hidden-categories" data-wrap-id="<?php echo $value['id']; ?>">
                                <?php foreach ($value['childs'] as $val): ?>
                                               
                                    <div class="table-body" style="background-color: #ccc;">
                                        <div class="body-section body-section-info">
                                        <?php if(isset($val['childs'])): ?>
                                            <a href="#" class="child-category"><i class="fa fa-plus-square"></i></a>
                                        <?php endif;?>
                                        <span class="body-number-sort"><?php echo $val['sort']; ?></span>
                                        </div>
                                        <div class="body-section body-section-photo"><?php if(!empty($val['image'])): ?><img src="/web/uploads/categories/<?php echo $val['image'] ?>" alt="admin_img"><?php else: ?><img src="/backend/web/images/noimage-min.jpg" alt="admin_img"><?php endif; ?></div>
                                        <div class="body-section body-section-name body-section-name-level"><a href="/admin/categories/update?id=<?php echo $val['id']; ?>"><?php echo $val['name'] ?></a></div>
                                        <div class="body-section body-section-active">
                                            <label class="switch">
                                              <input type="hidden" name="categories" value="<?php echo $val['id']; ?>">
                                              <input type="checkbox" name="checkbox_active" value="<?php echo $val['active']; ?>">
                                              <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="body-section body-section-settings">
                                            <a href="/admin/categories/delete?id=<?php echo $val['id']; ?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" class="body-section-settings-delete"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 47.971 47.971" width="20px" height="20px"><path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88   c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242   C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879   s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z" fill="currentColor"></path></svg></a>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                                </div>

                        <?php endforeach; ?>
                        </div>
                        <?php endif;?>

                <?php endforeach; ?>
             
            </div>
        </div>
    </div>

</div>
