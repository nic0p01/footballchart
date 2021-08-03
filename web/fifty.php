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
    
    $stmt1 = $pdo->prepare("SELECT `match_date`, `match_hometeam_id`, `match_awayteam_id`, `match_hometeam_score`, `match_awayteam_score` FROM tutorials WHERE (`match_hometeam_id` = ? OR `match_awayteam_id` = ?) AND `match_date` < ? AND `match_hometeam_score` != 8888 ORDER BY `match_date` DESC LIMIT 50");
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
    ksort($ret1);
    $score1 = [];
    $score2 = [];
    $cons1 = [];
    $cons2 = [];
    foreach ($ret1 as $t1){
        $score1[] = $t1['score'];
        $cons1[] = $t1['cons'];
    }
    if(count($score1) < 30) continue;
    if(count($cons1) < 30) continue;
    $ends1 = end($score1);
    $keyssc1 = array_keys($score1, $ends1);
    $sums1 = 0;
    $sumc1 = 0;
    foreach ($keyssc1 as $ksc1){
        $sums1 += $score1[$ksc1+1];
    }
    $endc1 = end($cons1);
    $keysc1 = array_keys($cons1, $endc1);
    foreach ($keysc1 as $kc1){
        $sumc1 += $cons1[$kc1+1];
    }
    
    $av_s1 = $sums1/(count($keyssc1));
    $av_c1 = $sumc1/(count($keysc1));
    
    
    
    
    $stmt2 = $pdo->prepare("SELECT `match_date`, `match_hometeam_id`, `match_awayteam_id`, `match_hometeam_score`, `match_awayteam_score` FROM tutorials WHERE (`match_hometeam_id` = ? OR `match_awayteam_id` = ?) AND `match_date` < ? AND `match_hometeam_score` != 8888 ORDER BY `match_date` DESC LIMIT 50");
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
    ksort($ret2);
    
    foreach ($ret2 as $t2){
        $score2[] = $t2['score'];
        $cons2[] = $t2['cons'];
    }
    if(count($score2) < 30) continue;
    if(count($cons2) < 30) continue;
    $ends2 = end($score2);
    $keyssc2 = array_keys($score2, $ends2);
    $sums2 = 0;
    $sumc2 = 0;
    foreach ($keyssc2 as $ksc2){
        $sums2 += $score2[$ksc2+1];
    }
    $endc2 = end($cons2);
    $keysc2 = array_keys($cons2, $endc2);
    foreach ($keysc2 as $kc2){
        $sumc2 += $cons2[$kc2+1];
    }
    $score3 = 0;
    $cons3 = 0;
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
    
    $av_s2 = $sums2/(count($keyssc2));
    $av_c2 = $sumc2/(count($keysc2));
    $razn1 = ($av_s1 - $av_c2)/2;
    $razn2 = ($av_s2 - $av_c1)/2;
    
    
    echo $item['match_hometeam_name'].'('.$item['match_hometeam_score'].') - '.$item['match_awayteam_name'].'('.$item['match_awayteam_score'].'), '.$av_s1.', '.$av_c1.', '.$av_s2.', '.$av_c2.', '.count($keyssc1).', '.count($keysc1).', '.count($keyssc2).', '.count($keysc2).', '.$score3.', '.$cons3;
    echo '<br>';
    
    
    
}

