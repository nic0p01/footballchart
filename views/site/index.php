<?php

/* @var $this yii\web\View */

$this->title = 'Footballcharts.ru - Футбольная статистика в графиках!';
$this->registerMetaTag(
    [
      'name' => 'description', 
      'content' => 'Мы предоставляем подробную статистику футбольных матчей в виде удобных графиков. Узнайте, как сделать ваши прогнозы более точными прямо сейчас!'
    ]
);
$today = date('Y-m-d');
$tomorow = date("Y-m-d", strtotime("+1 days"));
$easteday = date("Y-m-d", strtotime("-1 days"));
?>
<div class="site-index-main">

    
    <div class="about">
        <div class="row">
            <div class="col-md-7">
                <h1 style="text-align: left;">Футбольная аналитика от Footballcharts.ru - мы не обещаем гарантированный куш, а предоставляем реальные возможности для его получения! </h1>
                <h5 style="text-align: left;">Наш сервис футбольной аналитики — это надёжный помощник в принятии конкретных решений, от которых зависит ваша будущая финансовая стабильность. Увы, но в спорте мало одной удачи!</h5>
            </div>
            <div class="col-md-5 top-lef">
                <div class="top-prog">
                    <a data-fancybox="gallery" href="/web/img/example/eksp1.png" title="Нажмите, что бы посмотреть пример нашего прогноза"><img src="/web/img/top-progn.png" alt="посмотреть пример нашего прогноза"></a>
                    <span class="top-prog-text">
                        
                        <a data-fancybox="gallery" href="/web/img/example/eksp2.png" title="Нажмите, что бы посмотреть пример нашего прогноза">Нажмите, что бы посмотреть пример нашего прогноза</a>
                    </span>
                </div>
                <div class="top-img">
                    <img src="/web/img/just.png" alt="только у нас">
                </div>
                
            </div>
        </div>
        <div class="row mt45 title-data">
            <div class="col-md-3 first">
                <!--<div class="button first-button"><a href="/signup">Получить доступ</a></div>-->
                <!--<div class="price"><span class="bold fs18">1190 &#8381;</span><span class="litle">/мес</span></div>-->
            <div class="list-item">
                        <div class="img text-center"><a href="/matches/date/<?= $easteday ?>" title="Список вчерашних матчей"><img src="/web/img/last.png" alt="Список вчерашних матчей"></a></div>
                        <div class="title text-center"><a class="bold" href="/matches/date/<?= $easteday ?>" title="Список вчерашних матчей">Список вчерашних матчей</a></div>
                    </div>
            </div>
            <div class="col-md-6 text-center second">
                <h3>Список футбольных матчей на сегодня</h3>
            </div>
                <div class="col-md-3 text-right last">
                <div class="list-item">
                        <div class="img text-center"><a href="/matches/date/<?= $tomorow ?>" title="Список матчей на завтра"><img src="/web/img/next.png" alt="Список матчей на завтра"></a></div>
                        <div class="title text-center"><a class="bold" href="/matches/date/<?= $tomorow ?>" title="Список матчей на завтра">Список матчей на завтра</a></div>
                    </div>
            </div>
            
        </div>
        <div class="matches-index">
            <table class="table table-striped">
            <tr>
                <th class="text-center nomob">Время</th>
                <th class="text-center">Хозяева</th>
                <th class="text-center">Счет</th>
                <th class="text-center">Гости</th>
                <th class="text-center nomob">1</th>
                <th class="text-center nomob">X</th>
                <th class="text-center nomob">2</th>

            </tr>
                    <?php $models = $dataProvider->getModels(); ?>
            <?php
            
            $c = 0;
                $l = 0;
                
            foreach($models as $item):?>
            <?php 
//debug($item);
if($item->match_hometeam_id == 0 || $item->match_awayteam_id == 0) continue;
            
            if($item->match_hometeam_score == 8888 && $item->match_date < date('Y-m-d')) continue;
                if($c != $item->country_id || $l != $item->league_id)
                {?>
            <tr style="background-color: #5f6368; color: white;"><td colspan="7"><?= $item->country_n.', '.$item->league_name ?></td></tr>
                <?php 
                
                }
                $c = $item->country_id;
                $l = $item->league_id;
            ?>
            <?php $item->match_hometeam_score == '8888' ? $score = ' - ' : $score = $item->match_hometeam_score.' - '.$item->match_awayteam_score ?>
            <?php
            
            $aliash = str_replace(" ","-",$item->match_hometeam_name );
            $aliash = str_replace("(", "", $aliash);
            $aliash = str_replace('.', '', $aliash);
            $aliash = str_replace(")", "", $aliash);
            $aliash = str_replace("'", "", $aliash);
            $aliash = str_replace("-&", "", $aliash);
            $aliash = strtolower($aliash);
            $aliasa = str_replace(" ","-",$item->match_awayteam_name );
            $aliasa = str_replace("(", "", $aliasa);
            $aliasa = str_replace('.', '', $aliasa);
            $aliasa = str_replace(")", "", $aliasa);
            $aliasa = str_replace("'", "", $aliasa);
            $aliasa = str_replace("-&", "", $aliasa);
            $aliasa = strtolower($aliasa);
            $alias = 'matches/'.$aliash.'-'.$aliasa.'-'.date("d-m-Y", strtotime($item->match_date)).'-'.$item->match_id;
            if($item->pred == null){
                $prob_HW = 'нет';
                $prob_D = 'нет';
                $prob_AW = 'нет';
            }else{
                if($item->pred->prob_HW == ''){
                    $prob_HW = 'нет';
                }else{
                    $prob_HW = $item->pred->prob_HW.'%';
                }
                if($item->pred->prob_D == ''){
                    $prob_D = 'нет';
                }else{
                    $prob_D = $item->pred->prob_D.'%';
                }
                if($item->pred->prob_AW == ''){
                    $prob_AW = 'нет';
                }else{
                    $prob_AW = $item->pred->prob_AW.'%';
                }
            }
            ?>
            <tr>
                
                <td class="text-center nomob"><a href="/<?= $alias ?>"><?= $item->match_time ?></a></td>
                <td class="text-center"><a href="/<?= $alias ?>"><?= $item->match_hometeam_name ?></a></td>
                <td class="text-center"><a href="/<?= $alias ?>"><?= $score ?></a></td>           
                <td class="text-center"><a href="/<?= $alias ?>"><?= $item->match_awayteam_name ?></a></td>
                <td class="text-center nomob"><a href="/<?= $alias ?>"><?= $prob_HW ?> </a></td>
                <td class="text-center nomob"><a href="/<?= $alias ?>"><?= $prob_D ?> </a></td>           
                <td class="text-center nomob"><a href="/<?= $alias ?>"><?= $prob_AW ?> </a></td>
                

            </tr>

            <?php endforeach; ?>
                    
        </table>
                </div>
    </div>
    <div class="info">
        <div class="info-wrp">
            <h2 class="bold upper">Информация</h2>
            <div class="title-line"></div>
            <p>Точные прогнозы на спорт интересуют всех, кто стремится получить прибыль на этой информации. Однако стабильно получать доход на ставках удаётся далеко не всем. Как правило, таких пользователей не более 8% от общего числа. Как же попасть в этот заветный процент? Нужно уметь быстро находить и получать максимум проверенной информации о будущих матчах, выбирать данные, которые действительно могут повлиять на исход и, конечно же, делать грамотные выводы.</p>
            <p>Каждый день мы собираем максимум информации, проверяем и анализируем её, чтобы у вас была возможность ей правильно воспользоваться. Вся статистика систематизируется и воспроизводится в виде удобных и понятных графиков. При их составлении учитываются все основополагающие факторы: положение в турнирной таблице, динамика результатов прошедших игр, физическая форма команд перед матчем и другие факторы, которые могут влиять на исходы матчей.</p>
            <p>Платный доступ включает рассылку с прогнозами от нашей команды, в качестве которой Вы можете убедиться по ссылке: <a class="bold" style="text-decoration: underline;" href="https://www.betonsuccess.ru/sub/128429/Footballcha.SOC.U/stats/all/">Статистика прогнозов в платной подписке</a></p>
        </div>
    </div>
    <div class="adv">
        <h2 class="bold upper">Наши преимущества</h2>
        <div class="title-line"></div>
        <p>Уникальные возможности, сочетание которых вы не найдёте больше нигде</p>
        <div class="adv-items">
            <div class="adv-item">
                <div class="img"><img src="/web/img/match.png" alt="Статистика игр"></div>
                <div class="title">Статистика игр</div>
                <div class="desc">из 160+ стран<br>и 400+ лиг</div>
            </div>
            <div class="adv-item">
                <div class="img"><img src="/web/img/analytics.png" alt="Прогнозы – вероятности"></div>
                <div class="title">*Прогнозы – вероятности</div>
                <div class="desc">в процентах на исход, тотал,<br>обе забьют или нет</div>
            </div>
            <div class="adv-item">
                <div class="img"><img src="/web/img/junction.png" alt="Раздельная статистика"></div>
                <div class="title">Раздельная статистика</div>
                <div class="desc">по последним<br>50 и 10 играм</div>
            </div>
            <div class="adv-item">
                <div class="img"><img src="/web/img/profit.png" alt="График физической формы"></div>
                <div class="title">График физической формы</div>
                <div class="desc">рассчитывается<br>по количеству побед,<br>ничьих и поражений</div>
            </div>
            <div class="adv-item">
                <div class="img"><img src="/web/img/bar-chart.png" alt="Гистограммы"></div>
                <div class="title">Гистограммы</div>
                <div class="desc">по исходам, голам,<br>индивидуальным и общим<br>тоталам, обе забивают или нет</div>
            </div>
            <div class="adv-item">
                <div class="img"><img src="/web/img/line-graph.png" alt="Забитые и пропущенные"></div>
                <div class="title">Забитые и пропущенные</div>
                <div class="desc">две линии на одном<br>графике для каждой<br>команды</div>
            </div>
            <div class="adv-item">
                <div class="img"><img src="/web/img/pie-chart-stats.png" alt="Статистика игр"></div>
                <div class="title">Очные встречи</div>
                <div class="desc">Круговые диаграммы:<br>исходы, тоталы, забивают,<br>пропускают и среднее<br>Линейный график по тоталам</div>
            </div>
            <div class="adv-item">
                <div class="img"><img src="/web/img/cards.png" alt="Статистика"></div>
                <div class="title">Статистика</div>
                <div class="desc">жёлтых и красных карт<br>Гистограммы<br>и линейные графики</div>
            </div>
            <div class="adv-item">
                <div class="img"><img src="/web/img/post.png" alt="Статистика"></div>
                <div class="title">Рассылка</div>
                <div class="desc">с прогнозами от нашей<br>команды.<br><a data-fancybox="gallery" href="/web/img/example/odin.png" class="adv-link">Пример</a></div>
            </div>
            <div class="adv-item">
                <div class="img"><img src="/web/img/free.png" alt="Статистика"></div>
                <div class="title">Бесплатный</div>
                <div class="desc">досуп к статистике по<br>всем играм</div>
            </div>
        </div>
    </div>
    <!--<div class="mt30 second-left down">-->
    <!--            <div class="button first-button"><a href="/signup">Получить доступ</a></div>-->
    <!--            <div class="price"><span class="bold fs18">1190 &#8381;</span><span class="litle">/мес</span></div>-->
    <!--        </div>-->
    <div class="note">
        <p>Наш сайт не проводит азартных игр и не принимает ставки. Вся информация носит ознакомительный характер. Играйте осторожно. При признаках зависимости обратитесь к специалисту. Возрастное ограничение <span class=" bold red">18+</span></p>
    </div>
    <div class="gt text-center">
        <a target="_blank" href="https://www.gamblingtherapy.org/ru" rel="nofollow noopener">
            <img src="/web/img/gambling.png?prod" alt="gamblingtherapy.org/ru">
        </a>
    </div>
    
    


    
    
    
    
</div>
