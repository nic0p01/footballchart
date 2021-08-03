<?php

/* @var $this yii\web\View */
use app\models\Calendar;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\Tutorials;
$this->title = 'Статистика и прогнозы на футбол за '.date('d-m-Y', strtotime($n_date));
$this->registerMetaTag(
    [
      'name' => 'description', 
      'content' => 'Список футбольных матчей за '.date('d-m-Y', strtotime($n_date)).'. В таблице указаны страна, лига, время, команды и прогнозы в виде процентного соотношения на исходы. Учитывайте вероятность в процентах и статистику за 10 и 50 игр для более точного прогноза.'
    ]
);
?>
<div class="site-index">

    
        
        <h1>Статистика и прогнозы на футбол за <?=date('d-m-Y', strtotime($n_date))?></h1>
        <h5 class="text-center">Список футбольных матчей за <?=date('d-m-Y', strtotime($n_date))?>. В таблице указаны страна, лига, время, команды и прогнозы в виде процентного соотношения на исходы. Учитывайте вероятность в процентах и статистику за 10 и 50 игр для более точного прогноза.</h5>
        <div class="row">
            <div class="col-md-6">
                <?php Pjax::begin(['id' => 'ser']) ?>
                <?php echo $this->render('_search', ['model' => $searchModel, 'date' => $n_date]); ?>
                <?php $models = $dataProvider->getModels(); ?>
                <?php Pjax::end() ?>
            </div>
            <div class="col-md-6 text-right">
                <?php if(Yii::$app->user->isGuest){
                    
                }else{ 
                    if(Yii::$app->user->identity->role == 10){
                ?>
                <?php Pjax::begin() ?>
                <?= Html::a('download', ['txt', 'dt' => $n_date], ['class' => 'btn btn-success']) ?>
                <?php Pjax::end() ?>
                <?php }} ?>
            </div>
        </div>
        <?php Pjax::begin(['id' => 'tablem']) ?>
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
        <?php Pjax::end() ?>
    
       <?php
    $this->registerJs(
        '$("document").ready(function(){
            $("#ser").on("pjax:end", function() {
            $.pjax.reload({container:"#tablem"});  //Reload GridView
        });
    });'
    );
?>
    
</div>
