<?php

try {
  $pdo = new PDO('mysql:host=localhost;dbname=fcharts', 'fcharts', 'as5312977');
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage();
  die();
}

set_time_limit(0);


if(isset($_GET['da'])){
    $da = $_GET['da'];
}else{
    $da = date('Y-m-d');
}

$stmt = $pdo->prepare("SELECT `match_hometeam_name`, `match_hometeam_score`, `match_awayteam_name`, `match_awayteam_score`, `match_hometeam_id`, `match_awayteam_id` FROM tutorials WHERE `match_date` = ?");
$stmt->execute([$da]);
//$filename = 'new.txt';
//$handler = fopen($filename, "w+");
foreach ($stmt as $item){
    
    $stmt1 = $pdo->prepare("SELECT `match_date`, `match_hometeam_id`, `match_awayteam_id`, `match_hometeam_score`, `match_awayteam_score` FROM tutorials WHERE (`match_hometeam_id` = ? OR `match_awayteam_id` = ?) AND `match_date` < ? AND `match_hometeam_score` != 8888 ORDER BY `match_date` DESC LIMIT 20");
    $stmt1->bindParam(1, $item['match_hometeam_id']);
    $stmt1->bindParam(2, $item['match_hometeam_id']);
    $stmt1->bindParam(3, $da);
    $stmt1->execute();

    $ret1 = [];
    foreach ($stmt1 as $item1){
        if($item1['match_hometeam_id'] == $item['match_hometeam_id']){
            $ret1[$item1['match_date']]['score'] = (int)$item1['match_hometeam_score'];
            $ret1[$item1['match_date']]['cons'] = (int)$item1['match_awayteam_score'];
        }else{
            $ret1[$item1['match_date']]['score'] = (int)$item1['match_awayteam_score'];
            $ret1[$item1['match_date']]['cons'] = (int)$item1['match_hometeam_score'];
        }
        
    }
    $stmt2 = $pdo->prepare("SELECT `match_date`, `match_hometeam_id`, `match_awayteam_id`, `match_hometeam_score`, `match_awayteam_score` FROM tutorials WHERE (`match_hometeam_id` = ? OR `match_awayteam_id` = ?) AND `match_date` < ? AND `match_hometeam_score` != 8888 ORDER BY `match_date` DESC LIMIT 20");
    $stmt2->bindParam(1, $item['match_awayteam_id']);
    $stmt2->bindParam(2, $item['match_awayteam_id']);
    $stmt2->bindParam(3, $da);
    $stmt2->execute();

    $ret2 = [];
    foreach ($stmt2 as $item2){
        if($item2['match_hometeam_id'] == $item['match_awayteam_id']){
            $ret2[$item2['match_date']]['score'] = (int)$item2['match_hometeam_score'];
            $ret2[$item2['match_date']]['cons'] = (int)$item2['match_awayteam_score'];
        }else{
            $ret2[$item2['match_date']]['score'] = (int)$item2['match_awayteam_score'];
            $ret2[$item2['match_date']]['cons'] = (int)$item2['match_hometeam_score'];
        }
        
    }
    if(count($ret1) < 20) continue;
    if(count($ret2) < 20) continue;
    
//    var_dump($ret2_10);
//    echo '<br>';
    $points1 = 0;
    $score1 = 0;
    $cons1 = 0;
    $points2 = 0;
    $score2 = 0;
    $cons2 = 0;
    $score3 = 0;
    $cons3 = 0;
    foreach ($ret1 as $m1){
        if($m1['score'] > $m1['cons']){
            $points1 += 3;
        }elseif($m1['score'] == $m1['cons']){
            $points1 += 1;
        }else{}
        $score1 += $m1['score'];
        $cons1 += $m1['cons'];
        
    }
    foreach ($ret2 as $m2){
        if($m2['score'] > $m2['cons']){
            $points2 += 3;
        }elseif($m2['score'] == $m2['cons']){
            $points2 += 1;
        }else{}
        $score2 += $m2['score'];
        $cons2 += $m2['cons'];
        
    }
    $av_points1 = $points1/20;
    $av_score1 = $score1/20;
    $av_cons1 = $cons1/20;
    $av_points2 = $points2/20;
    $av_score2 = $score2/20;
    $av_cons2 = $cons2/20;
    $dif_p = $av_points1 - $av_points2;
    $dif_s = $av_score1 - $av_score2;
    $dif_c = $av_cons1 - $av_cons2;
    
    $stmt3 = $pdo->prepare("SELECT `match_date`, `match_hometeam_id`, `match_awayteam_id`, `match_hometeam_score`, `match_awayteam_score` FROM tutorials WHERE ((`match_hometeam_id` = ? AND `match_awayteam_id` = ?) OR (`match_hometeam_id` = ? AND `match_awayteam_id` = ?)) AND `match_date` < ? AND `match_hometeam_score` != 8888");
    $stmt3->bindParam(1, $item['match_hometeam_id']);
    $stmt3->bindParam(2, $item['match_awayteam_id']);
    $stmt3->bindParam(3, $item['match_awayteam_id']);
    $stmt3->bindParam(4, $item['match_hometeam_id']);
    $stmt3->bindParam(5, $da);
    $stmt3->execute();
    foreach ($stmt3 as $i){
        if($i['match_hometeam_id'] == $item['match_hometeam_id']){
            $score3 += (int)$i['match_hometeam_score'];
            $cons3 += (int)$i['match_awayteam_score'];
        }else{
            $score3 += (int)$i['match_awayteam_score'];
            $cons3 += (int)$i['match_hometeam_score'];
        }
    }
    $stmt4 = $pdo->prepare("SELECT `match_date`, `match_hometeam_id`, `match_awayteam_id`, `match_hometeam_score`, `match_awayteam_score` FROM tutorials WHERE `match_hometeam_id` = ?  AND `match_date` < ? AND `match_hometeam_score` != 8888 ORDER BY `match_date` DESC LIMIT 20");
    $stmt4->bindParam(1, $item['match_hometeam_id']);
    $stmt4->bindParam(2, $da);
    $stmt4->execute();
    $home_win = 0;
    $home_draw = 0;
    $home_score = 0;
    $home_cons = 0;
    $away_win = 0;
    $away_draw = 0;
    $away_score = 0;
    $away_cons = 0;
    
    foreach ($stmt4 as $i4){
       if($i4['match_hometeam_score'] > $i4['match_awayteam_score']){
           $home_win += 3;
       }elseif($i4['match_hometeam_score'] == $i4['match_awayteam_score']){
           $home_win +=1;
       }
       $home_draw++;
       $home_score += (int)$i4['match_hometeam_score'];
       $home_cons += (int)$i4['match_awayteam_score'];
    }
    
    $stmt5 = $pdo->prepare("SELECT `match_date`, `match_hometeam_id`, `match_awayteam_id`, `match_hometeam_score`, `match_awayteam_score` FROM tutorials WHERE `match_awayteam_id` = ?  AND `match_date` < ? AND `match_hometeam_score` != 8888 ORDER BY `match_date` DESC LIMIT 20");
    $stmt5->bindParam(1, $item['match_awayteam_id']);
    $stmt5->bindParam(2, $da);
    $stmt5->execute();
    
    foreach ($stmt5 as $i5){
        if($i5['match_awayteam_score'] > $i5['match_hometeam_score']){
           $away_win += 3;
       }elseif($i5['match_hometeam_score'] == $i5['match_awayteam_score']){
           $away_win +=1;
       }
       $away_draw++;
       $away_score += (int)$i5['match_awayteam_score'];
       $away_cons += (int)$i5['match_hometeam_score'];
    }
    
    $ret1_10 =array_slice($ret1,0,10);
    $ret2_10 =array_slice($ret2,0,10);
    $point1_10 = 0;
    $point2_10 = 0;
    $score1_10 = 0;
    $score2_10 = 0;
    $cons1_10 = 0;
    $cons2_10 = 0;
    
    foreach ($ret1_10 as $item1_10){
        if($item1_10['score'] > $item1_10['cons']){
            $point1_10 += 3;
        }elseif($item1_10['score'] == $item1_10['cons']){
            $point1_10 += 1;
        }else{}
        $score1_10 += $item1_10['score'];
        $cons1_10 += $item1_10['cons'];
    }
    
    foreach ($ret2_10 as $item2_10){
        if($item2_10['score'] > $item2_10['cons']){
            $point2_10 += 3;
        }elseif($item2_10['score'] == $item2_10['cons']){
            $point2_10 += 1;
        }else{}
        $score2_10 += $item1_10['score'];
        $cons2_10 += $item1_10['cons'];
    }
    
    
//    echo $points1.', '.$score1.', '.$cons1.' - '.$points2.', '.$score2.', '.$cons2;
//    echo '<br>';
//    echo $item['match_hometeam_score'];
//    echo '<br>';
    echo $item['match_hometeam_name'].'('.$item['match_hometeam_score'].') - '.$item['match_awayteam_name'].'('.$item['match_awayteam_score'].'), '.$points1.', '.$score1.', '.$cons1.', '.$points2.', '.$score2.', '.$cons2.', '.$score3.', '.$cons3.', '.$home_win.', '.$home_draw.', '.$home_score.', '.$home_cons.', '.$away_win.', '.$away_draw.', '.$away_score.', '.$away_cons.', '.$point1_10.', '.$score1_10.', '.$cons1_10.', '.$point2_10.', '.$score2_10.', '.$cons2_10;
    echo '<br>';
}

//fwrite($handler, $text); // Записываем во временный файл
//        fseek($handler, 0); // Устанавливаем указатель файла
//        header('Content-Description: File Transfer');
//        header('Content-Type: application/octet-stream');
//        header('Content-Disposition: attachment; filename=' . basename($filename));
//        header('Content-Transfer-Encoding: binary');
//        header('Expires: 0');
//        header('Cache-Control: must-revalidate');
//        header('Pragma: public');
//        header('Content-Length: ' . filesize($filename));
//        // читаем файл и отправляем его пользователю
//        readfile($filename);
//        
//        unlink($filename);
//        exit;