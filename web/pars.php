<?php

try {
  $pdo = new PDO('mysql:host=localhost;dbname=stat', 'root', '');
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage();
  die();
}



set_time_limit(0); 
        $key = '89ff73ac8b5d5a3c8471a4d848e7ca5db36398e05e3cb89d3e74240336e997c3';
        $start = new DateTime('2017-01-01'); 
        $interval = new DateInterval('P1D'); 
        $end = new DateTime('2019-11-29'); 
        $period = new DatePeriod($start, $interval, $end); 
//        $d = '2019-11-07';
        foreach ($period as $dt) 
        { 
            $d = $dt->format('Y-m-d'); 
            $key = '89ff73ac8b5d5a3c8471a4d848e7ca5db36398e05e3cb89d3e74240336e997c3';
            echo $d;
            echo '<br>';
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
        $exept = [10034, 10035];
        
        foreach ($result as $items)
        {
//            echo '<pre>';
//            print_r($items);
//            echo '</pre>';
            
            $match_id = $items->match_id;
//            var_dump($match_id);
//            echo '<br>';
//            echo '<br>';
            $stmt = $pdo->prepare("SELECT `match_id` FROM tutorials WHERE `match_id` = ?");
            $stmt->execute([$match_id]);
            $name = $stmt->fetchColumn();
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
//            echo $team1.' - '. $team2;
//            echo '<br>';
//            echo $score1.' - '.$score2;
//            echo '<br>';
//            echo '<br>';
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
            
                
                
//                $stmt = $pdo->prepare("INSERT INTO `tutorials` (`match_id`, `country_id`, `country_n`, `league_id`, `league_name`, `match_date`, `match_time`, `match_status`, `match_hometeam_name`, `match_hometeam_score`, `match_awayteam_name`, `match_awayteam_score`, `t1yc`, `t2yc`, `t1rc`, `t1rc`, `match_hometeam_id`, `match_awayteam_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
//                $stmt->bindParam(1, $match_id);
//                $stmt->bindParam(2, $country_id);
//                $stmt->bindParam(3, $items->country_n);
//                $stmt->bindParam(4, $ligue_id);
//                $stmt->bindParam(5, $items->league_name);
//                $stmt->bindParam(6, $match_date);
//                $stmt->bindParam(7, $match_time);
//                $stmt->bindParam(8, $status);
//                $stmt->bindParam(9, $team1);
//                $stmt->bindParam(10, $score1);
//                $stmt->bindParam(11, $team2);
//                $stmt->bindParam(12, $score2);
//                $stmt->bindParam(13, $t1yc);
//                $stmt->bindParam(14, $t2yc);
//                $stmt->bindParam(15, $t1rc);
//                $stmt->bindParam(16, $t2rc);
//                $stmt->bindParam(17, $team1_id);
//                $stmt->bindParam(18, $team2_id);
//                
//                
//                $stmt->execute();
//                var_dump();
//                echo '<br>';
//                echo '<br>';
            if($name == false)
            {
                
                continue;
            }
            else
            {
                $query = "UPDATE `tutorials` SET `t1yc` = ?, `t2yc` = ?, `t1rc` = ?, `t2rc` = ?, `match_hometeam_score` = ?, `match_awayteam_score` = ? WHERE `match_id` = ?";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(1, $t1yc);
                $stmt->bindParam(2, $t2yc);
                $stmt->bindParam(3, $t1rc);
                $stmt->bindParam(4, $t2rc);
                $stmt->bindParam(5, $score1);
                $stmt->bindParam(6, $score2);
                $stmt->bindParam(7, $name);
                
                if($stmt->execute()){
                    echo 'Обновлено';
                    echo '<br>';
                } else {
                    echo 'Ошибка';
                    echo '<br>';
                }
            }
        }
        }



