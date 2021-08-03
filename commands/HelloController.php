<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Tutorials;
use app\models\Odds;
use app\models\Predictions;
use DateTime;
use DateInterval;
use DatePeriod;


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world!')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }
    public $key = '6606d9026887e46c60f6a258e15db316861de83d4e973302fecfaad8d797b923';
    
    public function actionEvents()
    {
//         $today = '2019-11-15';
        //$too = '2019-08-04';
        $today = date("Y-m-d", strtotime("-1 days"));
        $too = date("Y-m-d", strtotime("+2 days"));

        $from = $today;
        $to = $too;
        set_time_limit(0); 
        $APIkey = '6606d9026887e46c60f6a258e15db316861de83d4e973302fecfaad8d797b923';
//         echo $from;
        $curl_options = array(
            CURLOPT_URL => "https://allsportsapi.com/api/football/?met=Fixtures&APIkey=$APIkey&from=$from&to=$to",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 5
          );

        $curl = curl_init();
        curl_setopt_array( $curl, $curl_options );
        $result = curl_exec( $curl );

        $result = (array) json_decode($result);
        $exept = [10034, 10035, 10036, 10061, 9998, 10009, 10013, 10023, 10039, 10040, 10044, 10049, 10050, 10053, 10054, 10055, 8634, 54, 55, 56, 10004, 10005, 132, 133, 134, 135, 136, 168, 169, 170, 179, 180, 181, 182, 8711, 8712, 8713, 8714, 8715, 10015, 10016, 10017, 10018, 10019, 10020, 10021, 10022, 213, 214, 216, 217, 218, 264, 265, 266, 268, 269, 270, 8718, 271, 8719, 8720, 8721, 8722, 8738, 8739, 8740, 8731, 8732, 8733, 8734, 8735, 8749, 8750, 8751, 8752, 384, 385, 386, 387, 470, 471, 472, 473, 9693, 9694, 9695, 9696, 9697, 9698, 9699, 9700, 9701, 9702, 9703, 9704, 9705, 9706, 9707, 9708, 9709, 9710, 494, 495, 496];
        
        foreach ($result['result'] as $items)
        {
            
            $match_id = $items->event_key;
            $tutorials = Tutorials::find()->where(['match_id' => $match_id])->one();
//            $country_id = $items->country_id;
//            if($country_id == null)                continue;
            $ligue_id = $items->league_key;
            if(in_array($items->league_key, $exept))                continue;
            $team1_id = $items->home_team_key;
            $team2_id = $items->away_team_key;
            
            $status = $items->event_status;
            if($status == 'Cancelled' || $status == 'FRO' || $status == 'Awarded' || $status == 'Abandoned' || $status == 'Walkover' || $status == 'To finish')
            {
                continue;
            }
            if($status == ''){
                $status = 'not gaming';
            }
            if(stripos($status, 'After') !==false){
                
                if($items->event_ft_result == ''){
                    $regexp = '/([^\)]+)\((.*)\)/';
                    $res = preg_match($regexp, $items->event_final_result, $match);
                    $event_final_result = str_replace(' ',  '', $match[2]);
                }else{
                    $event_final_result = str_replace(' ',  '', $items->event_ft_result);
                }
            }else{
                $event_final_result = str_replace(' ',  '', $items->event_final_result);
                
            }
//            var_dump($event_final_result);
            if($event_final_result == ''){
                $score1 = 8888;
                $new_string1 = 8888;
                $score2 = 8888;
                $new_string2 = 8888;
            }else{
                $score = explode('-', $event_final_result);
                if($score[0] == '' || $score[0] == '?'){
                    $score1 = 8888;
                    $new_string1 = 8888;
                }else{
                    $score1 = str_replace(' ',  '', $score[0]);
                    $new_string1 = preg_replace("/[^0-9]/", "", $score1);
                }

                if($score[1] == '' || $score[1] == '?'){
                    $score2 = 8888;
                    $new_string2 = 8888;
                }else{
                    $score2 = str_replace(' ',  '', $score[1]);
                    $new_string2 = preg_replace("/[^0-9]/", "", $score2);
                }
            }
            
//            var_dump($score);
            if($items->event_home_team == ''){
                continue;
            }else{
                $team1 = $items->event_home_team;
            }
            $team2 = $items->event_away_team;
            
            $country = $items->country_name;
            $ligue = $items->league_name;
            if(stripos($ligue, 'Friend') !==false){  
                continue;
            }
            $match_date = $items->event_date;
            $match_time = $items->event_time;
            $match_time = str_replace(':', '-', $match_time);
//            $status = $items->match_status;
//            if($status == 'Cancelled' || $status == 'FRO')
//            {
//                continue;
//            }
            if(count($items->cards) == 0)
            {
                $t1yc = 0;
                $t2yc = 0;
                $t1rc = 0;
                $t2rc = 0;
            }
            else
            {
                $cards1 = array();
                $cards2 = array();
                foreach ($items->cards as $c)
                {
                    if($c->home_fault == '' && $c->away_fault == '')
                    {
                        
                    }
                    else
                    {
                        if($c->home_fault == '')
                        {
                            if($c->card == 'yellow card')
                            {
                                $cards2['yc'][$c->time] = $c->away_fault;
                            }
                            else
                            {
                                $cards2['rc'][$c->time] = $c->away_fault;
                            }
                            
                        }
                        else
                        {
                            if($c->card == 'yellow card')
                            {
                                $cards1['yc'][$c->time] = $c->home_fault;
                            }
                            else
                            {
                                $cards1['rc'][$c->time]  = $c->home_fault;
                            }
                        }
                    }
                  
                }
                if(isset($cards2['rc']))
                {
                    $t2rc = count($cards2['rc']);
                }
                else
                {
                    $t2rc = 0;
                }
                if(isset($cards1['rc']))
                {
                    $t1rc = count($cards1['rc']);
                }
                else
                {
                    $t1rc = 0;
                }
                if(isset($cards1['yc']))
                {
                    $t1yc = count($cards1['yc']);
                }
                else
                {
                    $t1yc = 0;
                }
                if(isset($cards2['yc']))
                {
                    $t2yc = count($cards2['yc']);
                }
                else
                {
                    $t2yc = 0;
                }
                
                
            }
            $country_id = 0;
            
            if($tutorials == NULL)
            {
                $tut = new Tutorials();
                $tut->match_id = $match_id;
                $tut->country_id = $country_id;
                $tut->country_n = $country;
                $tut->league_id = $ligue_id;
                $tut->league_name = $ligue;
                $tut->match_date = $match_date;
                $tut->match_time =$match_time;
                $tut->match_status = $status;
                $tut->match_hometeam_name = $team1;
                $tut->match_hometeam_score = $new_string1;
                $tut->match_awayteam_name = $team2;
                $tut->match_awayteam_score = $new_string2;
                $tut->t1yc = $t1yc;
                $tut->t2yc = $t2yc;
                $tut->t1rc = $t1rc;
                $tut->t2rc = $t2rc;
                $tut->match_hometeam_id = $team1_id;
                $tut->match_awayteam_id = $team2_id;
                $tut->save(false);
                echo 'Добавлено событие ' . $tut->match_id . "\n";
            }else
            {
                if($tutorials->match_hometeam_score != $new_string1 || $tutorials->match_awayteam_score != $new_string2)
                {
                    $tutorials->match_hometeam_score = $new_string1;
                    $tutorials->match_awayteam_score = $new_string2;
                    $tutorials->match_status = $status;
                    $tutorials->t1yc = $t1yc;
                    $tutorials->t2yc = $t2yc;
                    $tutorials->t1rc = $t1rc;
                    $tutorials->t2rc = $t2rc;
                    $tutorials->save(false);
                    echo 'Обновлено событие ' . $tutorials->match_id . "\n";
                }
            }
//            debug($items);
//            var_dump($items);
        }
        return ExitCode::OK;    
    }
 
    public function actionOdds()
    {
        $key = '6606d9026887e46c60f6a258e15db316861de83d4e973302fecfaad8d797b923';
        
        $today = date("Y-m-d");
        $too = date("Y-m-d");

        $from = $today;
        $to = $too;
        
        $curl_options = array(
          CURLOPT_URL => "https://apiv2.apifootball.com/?action=get_odds&from=$from&to=$to&APIkey=$key",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HEADER => false,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_CONNECTTIMEOUT => 5
        );

        $curl = curl_init();
        curl_setopt_array( $curl, $curl_options );
        $result = curl_exec( $curl );
        
        $result = (array) json_decode($result);
        
        
        foreach ($result as $item)
        {
            $odds = Odds::find()->where(['match_id' => $item->match_id])->andWhere(['odd_bookmakers' => $item->odd_bookmakers])->one();
            
            $oddd = (array)$item;
//            var_dump($odds);
            if($odds == NULL)
            {
//                var_dump($oddd);
                    
                    $odd = new Odds();
                    $odd->match_id = $oddd['match_id'];
                    $odd->odd_bookmakers = $oddd['odd_bookmakers'];
                    $odd->odd_1 = $oddd['odd_1'];
                    $odd->odd_x = $oddd['odd_x'];
                    $odd->odd_2 = $oddd['odd_2'];
                    $odd->bts_no = $oddd['bts_no'];
                    $odd->bts_yes = $oddd['bts_yes'];
                    $odd->odd_date = date('Y-m-d', strtotime($oddd['odd_date']));
                    $odd->o25 = $oddd['o+2.5'];
                    $odd->u25 = $oddd['u+2.5'];
                    $odd->save(false);
                    echo 'Добавлен букмекер '. $odd->odd_bookmakers .' для события событие ' . $odd->match_id . "\n";
                
            }
            
        }
    }
    
    public function actionPredictions()
    {
        $key = '6606d9026887e46c60f6a258e15db316861de83d4e973302fecfaad8d797b923';
        $today = date("Y-m-d", strtotime("-1 days"));
        $too = date("Y-m-d", strtotime("+2 days"));

        $from = $today;
        $to = $too;

        $curl_options = array(
          CURLOPT_URL => "https://apiv2.apifootball.com/?action=get_predictions&from=$from&to=$to&APIkey=$key",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HEADER => false,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_CONNECTTIMEOUT => 5
        );

        $curl = curl_init();
        curl_setopt_array( $curl, $curl_options );
        $result = curl_exec( $curl );

        $result = (array) json_decode($result);

        foreach ($result as $items)
        {
            $pred = Predictions::find()->where(['match_id' => $items->match_id])->one();
            if($pred == null)
            {
                $predict = new Predictions();
                $predict->match_id = $items->match_id;
                $predict->prob_HW = $items->prob_HW;
                $predict->prob_D = $items->prob_D;
                $predict->prob_AW = $items->prob_AW;
                $predict->prob_O = $items->prob_O;
                $predict->prob_U = $items->prob_U;
                $predict->prob_bts = $items->prob_bts;
                $predict->prob_ots = $items->prob_ots;
                $predict->save(false);
                echo 'Добавлен прогноз для события ' . $predict->match_id . "\n";
                        
            }
        }
    }
    public function actionCards()
    {
        $today = '2018-11-11';
        $too = '2019-11-11';
////        $today = date("Y-m-d", strtotime("-1 days"));
////        $too = date("Y-m-d", strtotime("+2 days"));
//
        $from = $today;
        $to = $too;
        set_time_limit(0); 
        $key = '6606d9026887e46c60f6a258e15db316861de83d4e973302fecfaad8d797b923';
        $start = new DateTime('2017-01-01'); 
        $interval = new DateInterval('P1D'); 
        $end = new DateTime('2019-11-25'); 
        $period = new DatePeriod($start, $interval, $end); 
        foreach ($period as $dt) 
        { 
            $d = $dt->format('Y-m-d'); 
            
        $curl_options = array(
          CURLOPT_URL => "https://apiv2.apifootball.com/?action=get_events&from=$d&to=$d&APIkey=$key",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HEADER => false,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_CONNECTTIMEOUT => 5
        );

        $curl = curl_init();
        curl_setopt_array( $curl, $curl_options );
        $result = curl_exec( $curl );

        $result = (array) json_decode($result);
        $exept = [10034, 10035, 10036, 10061, 9998, 10009, 10013, 10023, 10039, 10040, 10044, 10049, 10050, 10053, 10054, 10055, 8634];
        
        foreach ($result as $items)
        {
            $match_id = $items->match_id;
            $tutorials = Tutorials::find()->where(['match_id' => $match_id])->one();
            $country_id = $items->country_id;
            if($country_id == null)                continue;
            $ligue_id = $items->league_id;
            if(in_array($items->league_id, $exept))                continue;
            $team1_id = $items->match_hometeam_id;
            $team2_id = $items->match_awayteam_id;
            if($items->match_hometeam_name == ''){
                continue;
            }else{
                $team1 = $items->match_hometeam_name;
            }

            if($items->match_hometeam_score == '' || $items->match_hometeam_score == '?'){
                $score1 = 8888;
            }else{
                $score1 = $items->match_hometeam_score;
            }
            $team2 = $items->match_awayteam_name;
            if($items->match_awayteam_score == '' || $items->match_awayteam_score == '?'){
                $score2 = 8888;
            }else{
                $score2 = $items->match_awayteam_score;
            }
            $country = $items->country_name;
            $ligue = $items->league_name;
            if(stripos($ligue, 'Friend') !==false){  
                continue;
            }
            $match_date = $items->match_date;
            $match_time = $items->match_time;
            $match_time = str_replace(':', '-', $match_time);
            $status = $items->match_status;
            if($status == 'Cancelled' || $status == 'FRO')
            {
                continue;
            }
            if(count($items->cards) == 0)
            {
                $t1yc = 0;
                $t2yc = 0;
                $t1rc = 0;
                $t2rc = 0;
            }
            else
            {
                $cards1 = array();
                $cards2 = array();
                foreach ($items->cards as $c)
                {
                    if($c->home_fault == '' && $c->away_fault == '')
                    {
                        
                    }
                    else
                    {
                        if($c->home_fault == '')
                        {
                            if($c->card == 'yellow card')
                            {
                                $cards2['yc'][$c->time] = $c->away_fault;
                            }
                            else
                            {
                                $cards2['rc'][$c->time] = $c->away_fault;
                            }
                            
                        }
                        else
                        {
                            if($c->card == 'yellow card')
                            {
                                $cards1['yc'][$c->time] = $c->home_fault;
                            }
                            else
                            {
                                $cards1['rc'][$c->time]  = $c->home_fault;
                            }
                        }
                    }
                  
                }
                if(isset($cards2['rc']))
                {
                    $t2rc = count($cards2['rc']);
                }
                else
                {
                    $t2rc = 0;
                }
                if(isset($cards1['rc']))
                {
                    $t1rc = count($cards1['rc']);
                }
                else
                {
                    $t1rc = 0;
                }
                if(isset($cards1['yc']))
                {
                    $t1yc = count($cards1['yc']);
                }
                else
                {
                    $t1yc = 0;
                }
                if(isset($cards2['yc']))
                {
                    $t2yc = count($cards2['yc']);
                }
                else
                {
                    $t2yc = 0;
                }
                
                
            }
//            var_dump($tutorials);
            if($tutorials == null) continue;
            
                    $tutorials->match_hometeam_score = $score1;
                    $tutorials->match_awayteam_score = $score2;
                    $tutorials->match_status = $status;
                    $tutorials->t1yc = $t1yc;
                    $tutorials->t2yc = $t2yc;
                    $tutorials->t1rc = $t1rc;
                    $tutorials->t2rc = $t2rc;
                    $tutorials->save(false);
                    echo 'Обновлено событие ' . $tutorials->match_id . "\n";
                
        }
        }
        return ExitCode::OK;    
    }
    
    public function actionSitemap()
    {
        
    }
}
