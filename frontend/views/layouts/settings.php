<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
// use frontend\assets\MainAsset;
// use frontend\assets\OptimazeAsset;
use common\widgets\Alert;
use common\widgets\Breadcrumbs;

use yii\helpers\Url;

AppAsset::register($this);
// MainAsset::register($this);
// OptimazeAsset::register($this);

$unread = \frontend\models\Message::getUnreadCount();
if(!empty($unread))
    $unread = '(' . $unread . ')';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>


    


    <?= $content ?>
    






<?php $this->endBody() ?>

<noscript id="deferred-styles">
      <link rel="stylesheet" type="text/css" href="/css/tether.min.css"/>
      <link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.min.css"/>
      <link rel="stylesheet" type="text/css" href="/js/assets/owl.carousel.min.css"/>
      <link rel="stylesheet" type="text/css" href="/js/assets/owl.theme.default.min.css"/>
      <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css"/>
      <link rel="stylesheet" type="text/css" href="/css/jquery.fancybox.min.css"/>
    </noscript>
    <script>
      var loadDeferredStyles = function() {
        var addStylesNode = document.getElementById("deferred-styles");
        var replacement = document.createElement("div");
        replacement.innerHTML = addStylesNode.textContent;
        document.body.appendChild(replacement)
        addStylesNode.parentElement.removeChild(addStylesNode);
      };
      var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
          window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
      if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 0); });
      else window.addEventListener('load', loadDeferredStyles);
</script>
<?php if (!isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Speed Insights') === false): ?><script type="text/javascript" > (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter47280237 = new Ya.Metrika({ id:47280237, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://d31j93rd8oukbv.cloudfront.net/metrika/watch_ua.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/47280237" style="position:absolute; left:-9999px;" alt="" /></div></noscript><?php endif; ?>
</body>
</html>
<?php $this->endPage() ?>