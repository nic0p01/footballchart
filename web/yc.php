<?php

try {
  $pdo = new PDO('mysql:host=localhost;dbname=fcharts', 'fcharts', 'as5312977');
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage();
  die();
}

if(isset($_GET['da'])){
  $da = $_GET['da'];
}else{
  $da = date('Y-m-d');
}


$stmt = $pdo->prepare("SELECT `match_hometeam_name`, `match_awayteam_name`, `t1yc`, `t2yc`, `match_hometeam_id`, `match_awayteam_id` FROM tutorials WHERE `match_date` = ?");
$stmt->execute([$da]);

foreach ($stmt as $item){
    
  $stmt1 = $pdo->prepare("SELECT `match_date`, `match_hometeam_id`, `match_awayteam_id`, `match_hometeam_score`, `match_awayteam_score`, `t1yc`, `t2yc` FROM tutorials WHERE (`match_hometeam_id` = ? OR `match_awayteam_id` = ?) AND `match_date` < ? AND `match_hometeam_score` != 8888 ORDER BY `match_date` DESC LIMIT 10");
  $stmt1->bindParam(1, $item['match_hometeam_id']);
  $stmt1->bindParam(2, $item['match_hometeam_id']);
  $stmt1->bindParam(3, $da);
  $stmt1->execute();
  $ret1 = 0;
  $count = 0;
  // echo '<pre>';
  // print_r($stmt1->errorInfo());
  // echo '</pre>';
  
  foreach ( $stmt1 as $item1 ){
    
    if($item1['match_hometeam_id'] == $item['match_hometeam_id']){
      $ret1 += (int)$item1['t1yc'];
      $count += 1;
    }else{
      $ret1 += (int)$item1['t2yc'];
      $count += 1;
    }
  }
  if($count == 0 || $count < 10) continue;
  $ret11 = $ret1 / $count;
  

  $stmt2 = $pdo->prepare("SELECT `match_date`, `match_hometeam_id`, `match_awayteam_id`, `match_hometeam_score`, `match_awayteam_score`, `t1yc`, `t2yc` FROM tutorials WHERE (`match_hometeam_id` = ? OR `match_awayteam_id` = ?) AND `match_date` < ? AND `match_hometeam_score` != 8888 ORDER BY `match_date` DESC LIMIT 10");
  $stmt2->bindParam(1, $item['match_awayteam_id']);
  $stmt2->bindParam(2, $item['match_awayteam_id']);
  $stmt2->bindParam(3, $da);
  $stmt2->execute();

  $ret2 = 0;
  $count2 = 0;
  foreach ( $stmt2 as $item2 ){
    if($item2['match_hometeam_id'] == $item['match_awayteam_id']){
      $ret2 += (int)$item2['t1yc'];
      $count2 += 1;
    }else{
      $ret2 += (int)$item2['t2yc'];
      $count2 += 1;
    }
  }
  if($count2 == 0 || $count2 < 10) continue;
  $ret22 = $ret2 / $count2;
  if($ret11 == 0 && $ret22 == 0) continue;
  echo $item['match_hometeam_name'].' - '.$item['match_awayteam_name'].'('.$item['t1yc'].' - '.$item['t2yc'].'),'.number_format($ret11, 2).','.number_format($ret22, 2);
  echo '<br>';
}