<?php 
$this->params['breadcrumbs'][] = 'Блог';

use yii\helpers\Url;
?>
<section id="blog">

        <div class="container clear">
            <div class="blog-content">
               <div class="blog-content-tittle">
                   <h1>Блог Портала объявлений Jandooo</h1>
                    <p>Интересные статьи, которые мы создаем специально для вас</p>
               </div>
                
               <div class="blog-article">
                  <?php foreach ($blogs as $key): ?>
                   <div class="blog-article-item">
                       <div class="blog-article-item-img text-center">
                           <?php if(!empty($key['image'])): ?><img src="/web/uploads/blog/<?php echo $key['image'] ?>" alt="admin_img"><?php else: ?><img src="/backend/web/images/noimage-min.jpg" alt="admin_img"><?php endif; ?>
                       </div>
                       <div class="blog-article-item-tittle">
                           <a href="<?= Url::to(['blog/view', 'alias' => $key['alias']]) ?>"><span><?= $key['name'] ?></span></a>
                       </div>
                       <div class="blog-article-item-text">
                           <p>
                               <?= mb_substr(htmlspecialchars_decode($key['intro_text']), 0, 100) . '...' ?>
                           </p>
                           <div class="blog-article-item-text-next">
                               <a href="<?= Url::to(['blog/view', 'alias' => $key['alias']]) ?>"><span>
                                   &#8594;
                               </span></a>
                           </div>
                       </div>
                       <div class="blog-article-item-info-post">
                           <a class="blog-article-date">
                               <i class="fa fa-clock-o" aria-hidden="true"></i>
                               <span class="value value-date">26.09.2017, 11:01</span>
                           </a>
                           <a class="blog-article-view">
                               <i class="fa fa-eye" aria-hidden="true"></i>
                               <span class="value value-view">20</span>
                           </a>
                           <a class="blog-article-like">
                               <i class="fa fa-heart-o" aria-hidden="true"></i>
                               <span class="value value-like">5</span>
                           </a>
                       </div>
                   </div>
                  <?php endforeach; ?>
               </div>
            </div>
        </div>
    </section>