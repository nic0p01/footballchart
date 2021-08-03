<?php

// comment out the following two lines when deployed to production
//defined('YII_DEBUG') or define('YII_DEBUG', true);
//defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../params.php';
require __DIR__ . '/scripts/selector.php';

if( isset($_COOKIE['referer'])){

}else{
    if ( isset($_GET['utm_source']) && trim($_GET['utm_source']) != '' ) {
        $utm_source = trim($_GET['utm_source']);
        setcookie('utm_source',  $utm_source, time() + 60 * 60 * 24 * 30); // срок действия ~ 1 год
    }
    if ( isset($_GET['utm_medium']) && trim($_GET['utm_medium']) != '' ) {
        $utm_source = trim($_GET['utm_medium']);
        setcookie('utm_medium',  $utm_source, time() + 60 * 60 * 24 * 30); // срок действия ~ 1 год
    }
    if ( isset($_GET['utm_campaign']) && trim($_GET['utm_campaign']) != '' ) {
        $utm_source = trim($_GET['utm_campaign']);
        setcookie('utm_campaign',  $utm_source, time() + 60 * 60 * 24 * 30); // срок действия ~ 1 год
    }
    if ( isset($_GET['utm_term']) && trim($_GET['utm_term']) != '' ) {
        $utm_source = trim($_GET['utm_term']);
        setcookie('utm_term',  $utm_source, time() + 60 * 60 * 24 * 30); // срок действия ~ 1 год
    }
    if ( isset($_GET['utm_content']) && trim($_GET['utm_content']) != '' ) {
        $utm_source = trim($_GET['utm_content']);
        setcookie('utm_content',  $utm_source, time() + 60 * 60 * 24 * 30); // срок действия ~ 1 год
    }
    if ( isset($_GET['referer']) && trim($_GET['referer']) != '' ) {
        $utm_source = trim($_GET['referer']);
        setcookie('referer',  $utm_source, time() + 60 * 60 * 24 * 30); // срок действия ~ 1 год
    }
}

//require __DIR__ . '/scripts/Classes/PHPExcel.php';
//require __DIR__ . '/scripts/Classes/PHPExcel/Writer/Excel5.php';

$config = require __DIR__ . '/../config/web.php';
$application = new yii\web\Application($config);
$application->on(yii\web\Application::EVENT_BEFORE_REQUEST, function(yii\base\Event $event){
$event->sender->response->on(yii\web\Response::EVENT_BEFORE_SEND, function($e){
ob_start("ob_gzhandler");
});
$event->sender->response->on(yii\web\Response::EVENT_AFTER_SEND, function($e){
ob_end_flush();
});
});
$application->run();
