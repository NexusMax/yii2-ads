<?php 
use yii\helpers\Url;
$this->params['breadcrumbs'][] = ['label' => 'Блог', 'url' => Url::to(['blog/index'])];
$this->params['breadcrumbs'][] =  $blog['name'] ;

?>
<section id="blog">
<div class="container clear">
    <div class="blog-content id-<?= $blog['id'] ?>" style="background-color: #fff">
        <div class="blog-content-tittle">
            <h1><?= $blog['name'] ?></h1>
        </div>

        <div class="blog-article">
            <?php if($blog['id'] == '23' || $blog['id'] == '22' || $blog['id'] == '20') : ?>
                <div class="col-md-12">
                    <div class="blog-article-text-with-icon">
                        <p>
                            <?php if(!empty($blog['image'])): ?><img src="/web/uploads/blog/<?php echo $blog['image'] ?>" alt="admin_img"><?php else: ?><img src="/backend/web/images/noimage-min.jpg" alt="admin_img"><?php endif; ?>
                        </p>
                        <p>
                            <?= htmlspecialchars_decode($blog['full_text']) ?>
                        </p>
                    </div>
                </div>
            <?php else : ?>
                <div class="col-md-12 text-center">
                    <div class="blog-article-item-img">
                        <?php if(!empty($blog['image'])): ?><img src="/web/uploads/blog/<?php echo $blog['image'] ?>" alt="admin_img"><?php else: ?><img src="/backend/web/images/noimage-min.jpg" alt="admin_img"><?php endif; ?>
                    </div>
                </div>

                <div class="blog-article-item-text">
                    <p>
                        <?= htmlspecialchars_decode($blog['full_text']) ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</section>
    <style> #blog > div.container { padding-top: 30px; } </style>