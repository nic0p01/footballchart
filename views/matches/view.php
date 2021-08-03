<?php
use app\models\Calendar;
use yii\helpers\Html;
use app\models\Predictions;
use app\models\Tutorials;
use app\models\Odds;
?>
<div class="site-view">
<?php
    $match_id = $model->match_id;
    $match_date = $model->match_date;
    $match_time = $model->match_time;
    $m_id = $match_id;
    $match_hometeam_name = $model->match_hometeam_name;
    $match_hometeam_id = $model->match_hometeam_id;
    $match_awayteam_name = $model->match_awayteam_name;
    $match_awayteam_id = $model->match_awayteam_id;
    $match_hometeam_score = $model->match_hometeam_score;
    $match_awayteam_score = $model->match_awayteam_score;
    $t1_photo = '/web/img/teamlogos/'.$match_hometeam_id.'.png';
    $t2_photo = '/web/img/teamlogos/'.$match_awayteam_id.'.png';
    $normal_date = date('d-m-Y', strtotime($match_date));
    
    $this->title = 'Прогнозы и статистика '.$match_hometeam_name.' vs '.$match_awayteam_name.' '.$normal_date;
    $this->registerMetaTag(
    [
      'name' => 'description', 
      'content' => 'Прогнозы и статистика матча '.$match_hometeam_name.' vs '.$match_awayteam_name.' за '.$normal_date.' в лиге '.$model->league_name.', '.$model->country_n.'. Удобные графики: исходы, физическая форма, забитые и пропущенные голы, тоталы, жёлтые и красные карты.'
    ]
);
    
    $odds = Odds::find()->where(['match_id' => $m_id])->asArray()->one();
//    debug($odds);
    $lpos1 = getLpos($model->league_id, $match_hometeam_id);
    $lpos2 = getLpos($model->league_id, $match_awayteam_id);
//    debug($odds);
    

  ?>



        
        
        
    <div class="post" id="post-<?= $model->match_id ?>">
        
        
      <div class="post-title">
      <h1><?= $this->title ?></h1> 
      </div>
      <div class="entry">
          <div class="match_panel">
              <div class="row">
                  <div class="col-md-5 text-center">
                      <div class="team_photo"><img src="<?php echo $t1_photo ?>" alt="<?= $match_hometeam_name ?>" /></div>
                      <div class="team_name"><?= $match_hometeam_name ?></div>
                      <?php if($lpos1 != ''):?>
                      <div class="lpos">Позиция команды в лиге - <?= $model->league_name ?>: <?= $lpos1 ?></div>
                      <?php endif; ?>
                  </div>
                  <div class="col-md-2 text-center">
                      <?php $match_hometeam_score == '8888' ? $del = 'VS' : $del = $match_hometeam_score.' : '.$match_awayteam_score;?>
                      <div class="match_score"><?php echo $del; ?></div>
                      <div class="match_date"><div class="date"><?php echo $normal_date; ?></div><div class="time"><?php echo $match_time; ?></div></div>
                      
                  </div>
                  <div class="col-md-5 text-center">
                      <div class="team_photo"><img src="<?php echo $t2_photo ?>" alt="<?= $match_awayteam_name ?>"/></div>
                      <div class="team_name"> <?= $match_awayteam_name ?> </div>
                      <?php if($lpos2 != ''):?>
                      <div class="lpos">Позиция команды в лиге - <?= $model->league_name ?>: <?= $lpos2 ?></div>
                      <?php endif; ?>
                  </div>
                      </div>
          </div>
          </div>
          <div class="text text-center"><?= 'Прогнозы и статистика матча '.$match_hometeam_name.' vs '.$match_awayteam_name.' за '.$normal_date.' в лиге '.$model->league_name.', '.$model->country_n.'. Удобные графики: исходы, физическая форма, забитые и пропущенные голы, тоталы, жёлтые и красные карты.' ?></div>
          
              
            <h3 class="mt15" style="text-align: center; font-weight: bold; color: #0468b4;">Прогнозы</h3>
        <div class="prob">
             
             <?php if($model->pred == null){
                 echo '<div style="text-align: center;">Прогнозов на это событие нет</div>';
             }else{?>
             
            <table>
                <tr>
                    <th>1</th>
                    <th>X</th>
                    <th>2</th>
                    <th>ТБ 2.5</th>
                    <th>ТМ 2.5</th>
                    <th>ОЗ - Да</th>
                    <th>ОЗ - Нет</th>
                </tr>
                <tr>
                    <td><?php echo $model->pred->prob_HW; ?>%</td>
                    <td><?php echo $model->pred->prob_D; ?>%</td>
                    <td><?php echo $model->pred->prob_AW; ?>%</td>
                    <td><?php echo $model->pred->prob_O; ?>%</td>
                    <td><?php echo $model->pred->prob_U; ?>%</td>
                    <td><?php echo $model->pred->prob_bts; ?>%</td>
                    <td><?php echo $model->pred->prob_ots; ?>%</td>
                </tr>         
            </table>
             <?php } ?>
         </div>
        <h3 style="text-align: center; font-weight: bold; color: #0468b4;">Коэффициенты</h3>
        <div class="prob">
             
             <?php if($odds == NULL){
                 echo '<div style="text-align: center;">На это событие коэффициентов нет</div>';
             }else{?>
             
            <table>
                <tr>
                    <th>1</th>
                    <th>Х</th>
                    <th>2</th>
                    <th>ТБ 2.5</th>
                    <th>ТМ 2.5</th>
                    <th>ОЗ - Да</th>
                    <th>ОЗ - Нет</th>
                </tr>
                <tr>
                    <td><?php echo $odds['odd_1'] == '' ? 'нет' : $odds['odd_1']; ?></td>
                    <td><?php echo $odds['odd_x'] == '' ? 'нет' : $odds['odd_x']; ?></td>
                    <td><?php echo $odds['odd_2'] == '' ? 'нет' : $odds['odd_2']; ?></td>
                    <td><?php echo $odds['o25'] == '' ? 'нет' : $odds['o25']; ?></td>
                    <td><?php echo $odds['u25'] == '' ? 'нет' : $odds['u25']; ?></td>
                    <td><?php echo $odds['bts_yes'] == '' ? 'нет' : $odds['bts_yes']; ?></td>
                    <td><?php echo $odds['bts_no'] == '' ? 'нет' : $odds['bts_no']; ?></td>
                </tr>         
            </table>
             <?php } ?>
         </div>
        
        
    <?php
    $count_match = Tutorials::find()->where(['match_hometeam_id' => $match_hometeam_id])->orWhere(['match_awayteam_id' => $match_hometeam_id])->andWhere(['<', 'match_date', $match_date])->andWhere(['!=', 'match_hometeam_score', 8888])->andWhere(['!=', 'match_awayteam_score', 8888])->limit(20)->count();
    $count_match2 = Tutorials::find()->where(['match_hometeam_id' => $match_awayteam_id])->orWhere(['match_awayteam_id' => $match_awayteam_id])->andWhere(['<', 'match_date', $match_date])->andWhere(['!=', 'match_hometeam_score', 8888])->andWhere(['!=', 'match_awayteam_score', 8888])->limit(20)->count();
    if($count_match < 1 || $count_match2 < 1)
        {
        
        }
        else
        { 
            $s = new Selector();
        ?>
      
        
	<div class="chars">
            <div class="row">
                <div class="col-md-6">
                    <div class="chart_wrapper" id="bar_div5"></div>
                </div>
                <div class="col-md-6"><div class="chart_wrapper" id="bar_div6"></div></div>
            </div>
            <div class="charts_w">
<div class="chart_wrapper" id="chart_div" style="width: 100%; height: auto;"></div>
<div class="chart_wrapper" id="chart_div2" style="width: 100%; height: auto;"></div>
</div>
            <div class="baner-math">
            <iframe scrolling='no' frameBorder='0' style='padding:0px; margin:0px; border:0px;border-style:none;border-style:none;' width='700' height='270' src="https://aff1xstavka.com/I?tag=s_364409m_33545c_&site=364409&ad=33545" ></iframe>
        </div>
<div class="row">
    <div class="col-md-6"><div class="chart_wrapper" id="bar_goal"></div><div class="chart_wrapper" id="bar_goal_per"></div></div>
    <div class="col-md-6"><div class="chart_wrapper" id="bar_goal2"></div><div class="chart_wrapper" id="bar_goal_per2"></div></div>
</div>
        <div class="charts_w">
<div class="chart_wrapper" id="chart_divg" style="width: 100%; height: auto;"></div>
<div class="chart_wrapper" id="chart_divg2" style="width: 100%; height: auto;"></div>
        </div>
<div class="row">
    <div class="col-md-6"><div class="chart_wrapper" id="bar_total_over"></div><div class="chart_wrapper" id="bar_total_ind"></div></div>
    <div class="col-md-6"><div class="chart_wrapper" id="bar_total1_over"></div><div class="chart_wrapper" id="bar_total1_ind"></div></div>
</div>
            <div class="charts_w">
<div class="chart_wrapper" id="chart_divt" style="width: 100%; height: auto;"></div>
<div class="chart_wrapper" id="chart_divt2" style="width: 100%; height: auto;"></div>
            </div>
<?php if($s->GetDirectCount($match_hometeam_name, $match_awayteam_name, $match_date, $match_hometeam_id, $match_awayteam_id) != 0) {?>
<h3 style="text-align: center; font-weight: bold; color: #0468b4;">Очные встречи</h3>
<div class="row">
    <div class="col-md-4"><div class="chart_wrapper" id="pie_win"></div></div>
    <div class="col-md-4"><div class="chart_wrapper" id="pie_uo15"></div></div>
    <div class="col-md-4"><div class="chart_wrapper" id="pie_uo25"></div></div>
    <div class="col-md-4"><div class="chart_wrapper" id="pie_scored"></div></div>
    <div class="col-md-4"><div class="chart_wrapper" id="pie_conceded"></div></div>
    <div class="col-md-4"><div class="chart_wrapper" id="pie_gpm"></div></div>
</div>
<div class="charts_w">
<div class="chart_wrapper" id="direct_totals" style="width: 100%; height: auto;"></div>
</div>
<?php } ?>
<h3 style="text-align: center; font-weight: bold; color: #0468b4;">Нарушения</h3>
<div class="row">
    <div class="col-md-6"><div class="chart_wrapper" id="bar_ycard"></div></div>
    <div class="col-md-6"><div class="chart_wrapper" id="bar_ycard1"></div></div>
</div>
<div class="row">
    <div class="col-md-6"><div class="chart_wrapper" id="bar_ycard_per"></div></div>
    <div class="col-md-6"><div class="chart_wrapper" id="bar_ycard_per1"></div></div>
</div>
<div class="charts_w">
<div class="chart_wrapper" id="chart_divcard" style="width: 100%; height: auto;"></div>
<div class="chart_wrapper" id="chart_divcard1" style="width: 100%; height: auto;"></div>
</div>
        </div>
      
        
        
        <div class="text">
            <p>Статистика последних матчей: <?= $match_hometeam_name ?> - <?= $match_awayteam_name ?></p>
            <p>За последние 10 матчей команда <?= $match_hometeam_name ?> выиграла <?= $s->GetWins($match_hometeam_id, 10, $match_date); ?>, проиграла <?= $s->GetLosses($match_hometeam_id, 10, $match_date); ?> и <?= $s->GetDraws($match_hometeam_id, 10, $match_date); ?> сыграла в ничью. В этих играх они забили <?= $s->GetGoalScored($match_hometeam_id, 10, $match_date); ?> и пропустили <?= $s->GetGoalConceded($match_hometeam_id, 10, $match_date); ?>. В среднем команда <?= $match_hometeam_name ?> забивала <?= $s->GetGoalsPerMatchScored($match_hometeam_id, 10, $match_date) ?> и пропускала <?= $s->GetGoalsPerMatchConceded($match_hometeam_id, 10, $match_date) ?> за игру. Команда <?= $match_awayteam_name ?> выиграла <?= $s->GetWins($match_awayteam_id, 10, $match_date); ?>, проиграла <?= $s->GetLosses($match_awayteam_id, 10, $match_date) ?> и <?= $s->GetDraws($match_awayteam_id, 10, $match_date) ?> сыграла в ничью. В этих играх они забили <?= $s->GetGoalScored($match_awayteam_id, 10, $match_date) ?> и пропустили <?= $s->GetGoalConceded($match_awayteam_id, 10, $match_date) ?>. В среднем команда <?= $match_awayteam_name ?> забивала <?= $s->GetGoalsPerMatchScored($match_awayteam_id, 10, $match_date) ?> и пропускала <?= $s->GetGoalsPerMatchConceded($match_awayteam_id, 10, $match_date) ?> за игру.</p>
            <p>Физическая форма команды рассчитывается по количеству побед, ничьих и поражений (+1 победа, -1 поражение, 0 ничья). По этому принципу строится график для каждой команды. Учитываются домашние и выездные игры.</p>
            <p>На графике забитых и пропущенных голов вы видите 2 линии. Зеленая или синяя линия - это забитые голы, красная линия - пропущенные. Учитывая историю движения волны, возможно спрогнозировать дальнейшее движение каждой линии.</p>
            <p>Учитывайте статистику очных встреч за 2 года, а также оцените графики по тоталам, жёлтым и красным картам. Доступно общее и среднее количество, линейные графики для каждой команды.</p>
            
        </div>
        
         <div class="last10">
             <div class="row">
                 <div class="col-md-6">
                     <h3>Последние 10 игр команды <?= $match_hometeam_name ?></h3>
                     
                     <table class="table table-striped">
            <tr>

                <th class="text-center nomob">Дата</th>
                <th class="text-center">Хозяева</th>
                <th class="text-center">Счет</th>
                <th class="text-center">Гости</th>
            </tr>
                        
                    
            <?php
            $count_match10 = Tutorials::find()->where(['match_hometeam_id' => $match_hometeam_id])->orWhere(['match_awayteam_id' => $match_hometeam_id])->andWhere(['<', 'match_date', $match_date])->andWhere(['!=', 'match_hometeam_score', 8888])->andWhere(['!=', 'match_awayteam_score', 8888])->orderBy(['match_date' => SORT_DESC])->limit(10)->all();
            foreach($count_match10 as $item):?>
            
            <?php $item->match_hometeam_score == '8888' ? $score = ' - ' : $score = $item->match_hometeam_score.' - '.$item->match_awayteam_score ?>
            <?php
            if($item->match_hometeam_id == 0 || $item->match_awayteam_id == 0) continue;
            
            if($item->match_hometeam_score == 8888 && $item->match_date < date('Y-m-d')) continue;
            $aliash = str_replace(" ","-",$item->match_hometeam_name );
            $aliash = str_replace("(", "", $aliash);
            $aliash = str_replace('.', '', $aliash);
            $aliash = str_replace(")", "", $aliash);
            $aliash = strtolower($aliash);
            $aliasa = str_replace(" ","-",$item->match_awayteam_name );
            $aliasa = str_replace("(", "", $aliasa);
            $aliasa = str_replace('.', '', $aliasa);
            $aliasa = str_replace(")", "", $aliasa);
            $aliasa = strtolower($aliasa);
            $alias = 'matches/'.$aliash.'-'.$aliasa.'-'.date("d-m-Y", strtotime($item->match_date)).'-'.$item->match_id;
            ?>
            <tr>
                

                <td class="text-center nomob aaaa"><a href="/<?= $alias ?>"><?=  date("d.m.Y", strtotime($item->match_date)) ?></a></td>
                <td class="text-center"><a href="/<?= $alias ?>"><?= $item->match_hometeam_name ?></a></td>
                <td class="text-center"><a href="/<?= $alias ?>"><?= $score ?></a></td>           
                <td class="text-center"><a href="/<?= $alias ?>"><?= $item->match_awayteam_name ?></a></td>

            </tr>

            <?php endforeach; ?>
                    
        </table>
                     
                 </div>
                 <div class="col-md-6">
                     <h3>Последние 10 игр команды <?= $match_awayteam_name ?></h3>
                     
                     <table class="table table-striped">
            <tr>
                <th class="text-center nomob">Дата</th>
                <th class="text-center">Хозяева</th>
                <th class="text-center">Счет</th>
                <th class="text-center">Гости</th>
            </tr>
                        
                    
            <?php 
            $count_match10 = Tutorials::find()->where(['match_hometeam_id' => $match_awayteam_id])->orWhere(['match_awayteam_id' => $match_awayteam_id])->andWhere(['<', 'match_date', $match_date])->andWhere(['!=', 'match_hometeam_score', 8888])->andWhere(['!=', 'match_awayteam_score', 8888])->orderBy(['match_date' => SORT_DESC])->limit(10)->all();
            foreach($count_match10 as $item):?>
            
            <?php $item->match_hometeam_score == '8888' ? $score = ' - ' : $score = $item->match_hometeam_score.' - '.$item->match_awayteam_score ?>
            <?php
            if($item->match_hometeam_id == 0 || $item->match_awayteam_id == 0) continue;
            
            if($item->match_hometeam_score == 8888 && $item->match_date < date('Y-m-d')) continue;
            $aliash = str_replace(" ","-",$item->match_hometeam_name );
            $aliash = str_replace("(", "", $aliash);
            $aliash = str_replace('.', '', $aliash);
            $aliash = str_replace(")", "", $aliash);
            $aliash = strtolower($aliash);
            $aliasa = str_replace(" ","-",$item->match_awayteam_name );
            $aliasa = str_replace("(", "", $aliasa);
            $aliasa = str_replace('.', '', $aliasa);
            $aliasa = str_replace(")", "", $aliasa);
            $aliasa = strtolower($aliasa);
            $alias = 'matches/'.$aliash.'-'.$aliasa.'-'.date("d-m-Y", strtotime($item->match_date)).'-'.$item->match_id;
            ?>
            <tr>
                
                <td class="text-center nomob qwerty"><a href="/<?= $alias ?>"><?= date("d.m.Y", strtotime($item->match_date)) ?></a></td>
                <td class="text-center"><a href="/<?= $alias ?>"><?= $item->match_hometeam_name ?></a></td>
                <td class="text-center"><a href="/<?= $alias ?>"><?= $score ?></a></td>           
                <td class="text-center"><a href="/<?= $alias ?>"><?= $item->match_awayteam_name ?></a></td>

            </tr>

            <?php endforeach; ?>
                    
        </table>
                     
                 </div>
             </div>
             
         </div>
      
 
      </div><!--/post -->
 
            
 
        
 

      
        
 
  </div><!--/content -->
        
    

<script>
var fired = 0;
 
window.addEventListener('scroll', () => {
    if (fired === 0) {
        fired = 1;
        
    GetBar(<?php echo json_encode($s->GetBarForm($match_hometeam_name, $match_awayteam_name, 50, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'bar_div5', 'Исходы - 50 игр');
    GetBar(<?php echo json_encode($s->GetBarForm($match_hometeam_name, $match_awayteam_name, 10, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'bar_div6', 'Исходы - 10 игр');
    GetLine(<?php echo json_encode($s->GetFormTeam($match_hometeam_id, 50, $match_date, $match_hometeam_name)); ?>, 'chart_div', 'Физическая форма <?php echo $match_hometeam_name ?>', -50, +50);
    GetLine(<?php echo json_encode($s->GetFormTeam($match_awayteam_id, 50, $match_date, $match_awayteam_name)); ?>, 'chart_div2', 'Физическая форма <?php echo $match_awayteam_name ?>', -50, +50,'#03a762');
    GetLine(<?php echo json_encode($s->GetLineGoals($match_hometeam_id, 50, $match_date)); ?>, 'chart_divg', '<?php echo $match_hometeam_name ?> - График забитых и пропущенных голов', 0, 10);
    GetLine(<?php echo json_encode($s->GetLineGoals($match_awayteam_id, 50, $match_date)); ?>, 'chart_divg2', '<?php echo $match_awayteam_name ?> - График забитых и пропущенных голов', 0, 10,'#03a762');
    GetLine(<?php echo json_encode($s->GetTotals($match_hometeam_id, 50, $match_date, $match_hometeam_name)); ?>, 'chart_divt', '<?php echo $match_hometeam_name ?> - График по тоталам', 0, 12);
    GetLine(<?php echo json_encode($s->GetTotals($match_awayteam_id, 50, $match_date, $match_awayteam_name)); ?>, 'chart_divt2', '<?php echo $match_awayteam_name ?> - График по тоталам', 0, 12,'#03a762');
    GetBar(<?php echo json_encode($s->GetBarGoals($match_hometeam_name, $match_awayteam_name, 50, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'bar_goal', 'Статистика голов – 50 игр');
    GetBar(<?php echo json_encode($s->GetBarGoals($match_hometeam_name, $match_awayteam_name, 10, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'bar_goal2', 'Статистика голов – 10 игр ');
    GetBar(<?php echo json_encode($s->GetBarGoalsPerMatch($match_hometeam_name, $match_awayteam_name, 50, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'bar_goal_per', 'Среднее количество голов за игру – 50 игр');
    GetBar(<?php echo json_encode($s->GetBarGoalsPerMatch($match_hometeam_name, $match_awayteam_name, 10, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'bar_goal_per2', 'Среднее количество голов за игру – 10 игр');
    GetBar(<?php echo json_encode($s->GetBarTotalsOver($match_hometeam_name, $match_awayteam_name, 50, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'bar_total_over', 'Тотал – 50 игр');
    GetBar(<?php echo json_encode($s->GetBarTotalsOver($match_hometeam_name, $match_awayteam_name, 10, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'bar_total1_over', 'Тотал – 10 игр');
    GetBar(<?php echo json_encode($s->GetBarTotalsInd($match_hometeam_name, $match_awayteam_name, 50, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'bar_total_ind', 'Индивидуальный тотал - 50 игр');
    GetBar(<?php echo json_encode($s->GetBarTotalsInd($match_hometeam_name, $match_awayteam_name, 10, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'bar_total1_ind', 'Индивидуальный тотал - 10 игр');
    GetBar(<?php echo json_encode($s->GetBarCard($match_hometeam_name, $match_awayteam_name, 50, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'bar_ycard', 'Статистика нарушений - 50 игр');
    GetBar(<?php echo json_encode($s->GetBarCard($match_hometeam_name, $match_awayteam_name, 10, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'bar_ycard1', 'Статистика нарушений - 10 игр');
    GetBar(<?php echo json_encode($s->GetBarCardPerGame($match_hometeam_name, $match_awayteam_name, 50, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'bar_ycard_per', 'Среднее количество нарушений за игру - 50 игр');
    GetBar(<?php echo json_encode($s->GetBarCardPerGame($match_hometeam_name, $match_awayteam_name, 10, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'bar_ycard_per1', 'Среднее количество нарушений за игру - 10 игр');
    GetLine1(<?php echo json_encode($s->GetLineCards($match_hometeam_id, 50, $match_date)); ?>, 'chart_divcard', '<?php echo $match_hometeam_name ?> - График нарушений за 50 игр', 0, 10);
    GetLine1(<?php echo json_encode($s->GetLineCards($match_awayteam_id, 50, $match_date)); ?>, 'chart_divcard1', '<?php echo $match_awayteam_name ?> - График нарушений за 50 игр', 0, 10);
    }
});
    

</script>
<?php if($s->GetDirectCount($match_hometeam_name, $match_awayteam_name, $match_date, $match_hometeam_id, $match_awayteam_id) != 0) {?>
<script>
window.addEventListener('scroll', () => {
    if (fired === 0 || fired === 1) {
        fired = 2;
    GetPie(<?php echo json_encode($s->GetDirectWin($match_hometeam_name, $match_awayteam_name, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'pie_win', 'Победы');
    GetPie(<?php echo json_encode($s->GetDirectUnderOver($match_hometeam_name, $match_awayteam_name, 2, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'pie_uo15', 'Тотал больше/меньше 1.5');
    GetPie(<?php echo json_encode($s->GetDirectUnderOver($match_hometeam_name, $match_awayteam_name, 3, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'pie_uo25', 'Тотал больше/меньше 2.5');
    GetPie(<?php echo json_encode($s->GetDirectScored($match_hometeam_name, $match_awayteam_name, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'pie_scored', 'Голы забивает'); 
    GetPie(<?php echo json_encode($s->GetDirectConceded($match_hometeam_name, $match_awayteam_name, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'pie_conceded', 'Голы пропускает');
    GetPie(<?php echo json_encode($s->GetDirectPerMatch($match_hometeam_name, $match_awayteam_name, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'pie_gpm', 'Среднее голов за игру');
    GetLine(<?php echo json_encode($s->GetDirectTotalLine($match_hometeam_name, $match_awayteam_name, $match_date, $match_hometeam_id, $match_awayteam_id)); ?>, 'direct_totals', 'Очные встречи. График по тоталам', 0, 10)
    }
});
</script>
<?php } ?>
        
<?php } ?>

         
         
         
      
        

<?php
 function getLpos($id, $m_id){
     $APIkey='6606d9026887e46c60f6a258e15db316861de83d4e973302fecfaad8d797b923';

    $curl_options = array(
      CURLOPT_URL => "https://apiv2.apifootball.com/?action=get_standings&league_id=$id&APIkey=$APIkey",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HEADER => false,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_CONNECTTIMEOUT => 5
    );

    $curl = curl_init();
    curl_setopt_array( $curl, $curl_options );
    $result = curl_exec( $curl );

    $result = (array) json_decode($result);
    $ret = 'нет данных';
    if(isset($result['error'])){
        $ret = 'нет данных';
    }else{
      foreach ($result as $item){
        
        if($item->team_id == $m_id){
        $ret = $item->overall_league_position;
    }
    }  
    }
    
    
    
    return $ret;
 }
?>


