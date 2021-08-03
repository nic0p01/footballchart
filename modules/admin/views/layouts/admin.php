<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use app\assets\AdminAsset;



AdminAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/web/favicon.ico" type="image/x-icon" />
    <?php $this->registerCsrfMetaTags() ?>
    <title> Админка | <?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="logo">
        <div class="row">
            <div class="col-md-6"><a href="/"><?= \yii\bootstrap\Html::img('/web/img/logo.png', ['alt' => 'logo']) ?></a></div>
        </div>
        
    </div>
    <div class="row site">
        <div class="col-md-2">
            <div class="aside">
                <div class="sidebar">
                    <ul class="menu">
                        <li><a href="/gii">GII</a></li>
                        <li><a href="/admin/invoice">Оплаты</a></li>
                        <li><a href="/admin/user">Пользователи</a></li>
                        <li><a href="/admin/blog">Статьи</a></li>
                    </ul>
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


<?php $this->endBody() ?>
    
</body>
</html>
<?php $this->endPage() ?>
