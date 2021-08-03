<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Odds;
use app\models\Calendar;
use app\models\Predictions;
use app\models\Tutorials;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-153705830-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-153705830-1');
</script>

    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/web/favicon.ico" type="image/x-icon" />
    <meta name="yandex-verification" content="d734beaa5f0aef31" />
    <meta name="yandex-verification" content="0f4a3792a2d33c9c" />
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <!-- Yandex.Metrika counter -->
<script type="text/javascript">
    var fired = false;
 
window.addEventListener('scroll', () => {
    if (fired === false) {
        fired = true;
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(56184088, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
       }
});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/56184088" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<?php $this->beginBody() ?>

<div class="wrap">
    <div class="logo">
        <div class="row">
            <div class="col-md-6"><a href="/"><?= \yii\bootstrap\Html::img('/web/img/logo.png', ['alt' => 'logo']) ?></a></div>
            <div class="col-md-6 text-right">
                <div class="nav">
                    <div class="row">
                        <?php
                        $tooooo = date('Y-m-d');
                            if (Yii::$app->user->isGuest) {
                                echo '<div class="col-md-12">';
                                echo Html::a('Вход', ['/login'], ['class' => 'btn mr5 btn-sm btn-success login']);
                                echo Html::a('Регистрация', ['/signup'], ['class' => 'btn btn-sm btn-action signup']);
                                echo '</div>';

                            } else {
                                if(Yii::$app->user->identity->role == 5){
                                    
                                    $left = (strtotime(Yii::$app->user->identity->date_end)-strtotime($tooooo))/3600/24;
                                    if($left <= 0){
                                        $user = \app\models\User::findOne(Yii::$app->user->identity->id);
                                        $user->role = 1;
                                        $user->save();
                                        Yii::$app->getResponse()->redirect('https://footballcharts.ru');
                                    }
                                    echo '<div class="col-xs-3 col-md-4">';
                                    echo '<p> Подписка: осталось '.(int)$left.'<p>';
                                    echo '</div>';
                                    echo '<div class="col-xs-3 col-md-4">';
                                    if($left < 7){
                                        echo Html::a('Оплатить', ['/pay'], ['class' => 'btn btn-sm btn-action']);
                                    }
                                    echo '</div>';
                                }elseif(Yii::$app->user->identity->role == 1){
                                    echo '<div class="col-xs-0 col-md-3">';
                                    echo '</div>';
                                    echo '<div class="col-xs-3 col-md-3">';
                                    if(Yii::$app->user->identity->date_end != ''){
                                        echo '<p class="red bold mt5"> Подписка истекла <p>';
                                    }else{
                                        echo '<p class="red bold mt5"> Поблемы с оплатой <p>';
                                    }
                                    echo '</div>';
                                    echo '<div class="col-xs-3 col-md-2">';
                                    echo Html::a('Оплатить', ['/pay'], ['class' => 'btn btn-sm btn-action']);
                                    echo '</div>';
                                }elseif (Yii::$app->user->identity->role == 8){
                                    echo '<div class="col-xs-2 col-md-4">';
                                    echo '</div>';
                                    echo '<div class="col-xs-4 col-md-4">';
                                    echo Html::a('Кабинет', ['/cabinet'], ['class' => 'btn btn-outline-primary']);
                                    echo '</div>';
                                }elseif (Yii::$app->user->identity->role == 10) {
                                    echo '<div class="col-xs-0 col-md-4">';
                                    echo '</div>';
                                    echo '<div class="col-xs-3 col-md-2 text-right">';
                                    echo Html::a('Админка', ['/admin'], ['class' => 'btn btn-outline-primary']);
                                    echo '</div>';
                                    echo '<div class="col-xs-3 col-md-2">';
                                    echo Html::a('Кабинет', ['/cabinet'], ['class' => 'btn btn-outline-primary']);
                                    echo '</div>';
                                }
                                
                                echo '<div class="col-xs-6 col-md-4">';
                                echo  Html::beginForm(['/logout'], 'post')
                                    . Html::submitButton(
                                        'Выход (' . Yii::$app->user->identity->username . ')',
                                        ['class' => 'btn btn-outline-secondary btn-sm btn-block logout']
                                    )
                                    . Html::endForm();
                                echo '</div>';
                            }
                        ?>
                        </div>
                    </div>
            </div>
        </div>
        
    </div>
    <div class="row site">
        <div class="col-md-2">
            <div class="aside">
                <div class="sidebar">
                    <div class="calendar">
                       <?php $cal = new Calendar(); 
                        $selected = '';
                        $session = Yii::$app->session;
                        $selected = $session->get('selected');
                       ?>
                        <?= $cal->getCalendar($tooooo, $selected); ?>
                    </div> 
                    <div class="faq"><a href="/faq" title="Как работать с сайтом и графиками">Как работать с сайтом и графиками</a></div>
                    <div class="baner-aside">
                        <iframe scrolling='no' frameBorder='0' style='padding:0px; margin:0px; border:0px;border-style:none;border-style:none;' width='160' height='300' src="https://aff1xstavka.com/I?tag=s_364409m_34155c_&site=364409&ad=34155" ></iframe>
                    </div>
                </div>
                <div class="foot">
                    <p>Контакты: <a class="under-line" href="mailto:info@footballcharts.ru">info@footballcharts.ru</a></p>
                    <p><a class="under-line" href="/policy">Политика конфиденциальности</a></p>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div id="content" class="content">
              
                                       
                
        <?= Alert::widget() ?>
        <?= $content ?>
        </div>
        </div>
    </div>
    
        
    
</div>
    

<footer class="footer">
    
</footer>
<?php
    yii\bootstrap\Modal::begin([
     'header' => '<h2 class="medium">Вход</h2>',
     'id' => 'login',
     ]);
 
    yii\bootstrap\Modal::end();
?>
<?php $this->endBody() ?>
    <script type='application/ld+json'> 
{
  "@context": "http://www.schema.org",
  "@type": "WebSite",
  "name": "Footballcharts.ru - Футбольная статистика в графиках!",
  "url": "https://footballcharts.ru/"
}
 </script>
</body>
</html>
<?php $this->endPage() ?>
