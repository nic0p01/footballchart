<?php

/* @var $this yii\web\View */

$this->title = $content['title'];
$this->registerMetaTag(
    [
      'name' => 'description', 
      'content' => $content['description']
    ]
);
$today = date('Y-m-d');
$tomorow = date("Y-m-d", strtotime("+1 days"));
$easteday = date("Y-m-d", strtotime("-1 days"));
?>
<div class="site-faq">
    <h1><?= $content['title'] ?></h1>
<?= $content['content']?>

<div class="note">
    <p>Наш сайт не проводит азартных игр и не принимает ставки. Вся информация носит ознакомительный характер. Играйте осторожно. При признаках зависимости обратитесь к специалисту. Возрастное ограничение <span class=" bold red">18+</span></p>
</div>
<div class="gt text-center">
    <a target="_blank" href="https://www.gamblingtherapy.org/ru" rel="nofollow noopener">
        <img src="/web/img/gambling.png?prod" alt="gamblingtherapy.org/ru">
    </a>
</div>
    
    


    
    
    
    
</div>
