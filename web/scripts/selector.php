<?php
use app\models\Tutorials;
class Selector{
    
    function __($item){
        echo '<pre>';
        print_r($item);
        echo '<pre>';
    }

    function LoadData($name, $limit, $where = '', $date = ''){
        if($date != '') $data = " and match_date < '$date'";
        if($where == '') $where = sprintf("(match_hometeam_id = '%s' OR match_awayteam_id = '%s')", $name, $name);
//        $ret = Tutorials::findBySql($sql);
        $q = sprintf("SELECT match_date, match_hometeam_score, match_awayteam_score, match_hometeam_id, match_hometeam_name, match_awayteam_name, match_awayteam_id, t1yc, t2yc, t1rc, t2rc FROM tutorials WHERE %s%s and (match_hometeam_score != 8888 or match_awayteam_score != 8888) ORDER BY match_date DESC LIMIT %d", $where, $data, $limit);
//        echo $q;
//        echo '<br>';
        $ret = Tutorials::findBySql($q)->asArray()->all();
//        var_dump($ret);
//       echo '<br>';
//       echo '<br>';
//       echo '<br>';
       
	return $ret;      
    }
    
    function GetWins($name, $limit, $date){
        $data = Selector::LoadData($name, $limit, '', $date);
        $ret = 0;
        foreach ($data as $item){
            if($item["match_hometeam_id"] == $name){
                if($item["match_hometeam_score"]>$item["match_awayteam_score"]){
                    $ret++;
                }
            }
            if($item["match_awayteam_id"] == $name){
                if($item["match_awayteam_score"]>$item["match_hometeam_score"]){
                    $ret++;
                }
            }
            }
            return $ret;
        }
        function GetLosses($name, $limit, $date){
        $data = Selector::LoadData($name, $limit, '', $date);
        $ret = 0;
        foreach ($data as $item){
            if($item["match_hometeam_id"] == $name){
                if($item["match_hometeam_score"]<$item["match_awayteam_score"]){
                    $ret++;
                }
            }
            if($item["match_awayteam_id"] == $name){
                if($item["match_awayteam_score"]<$item["match_hometeam_score"]){
                    $ret++;
                }
            }
            }
            return $ret;
        }
        function GetDraws($name, $limit, $date){
        $data = Selector::LoadData($name, $limit, '', $date);
        $ret = 0;
        foreach ($data as $item){
            if($item["match_hometeam_id"] == $name){
                if($item["match_hometeam_score"]==$item["match_awayteam_score"]){
                    $ret++;
                }
            }
            if($item["match_awayteam_id"] == $name){
                if($item["match_awayteam_score"]==$item["match_hometeam_score"]){
                    $ret++;
                }
            }
            }
            return $ret;
        }
        function GetIndTotal($name, $limit, $total, $date){
        $data = Selector::LoadData($name, $limit, '', $date);
        $ret = 0;
        foreach ($data as $item){
            if($item["match_hometeam_id"] == $name){
                if($item["match_hometeam_score"]>=$total){
                    $ret++;
                }
            }
            if($item["match_awayteam_id"] == $name){
                if($item["match_awayteam_score"]>=$total){
                    $ret++;
                }
            }
            }
            return $ret;
        }
        function GetOverTotal($name, $limit, $total, $date){
        $data = Selector::LoadData($name, $limit, '', $date);
        $ret = 0;
        foreach ($data as $item){
            if($item["match_hometeam_id"] == $name){
                $sum = $item["match_hometeam_score"]+$item["match_awayteam_score"];
                if($sum>=$total){
                    $ret++;
                }
            }
            if($item["match_awayteam_id"] == $name){
                $sum = $item["match_hometeam_score"]+$item["match_awayteam_score"];
                if($sum>=$total){
                    $ret++;
                }
            }
            }
            return $ret;
        }
        function GetGoalScored($name, $limit, $date){
        $data = Selector::LoadData($name, $limit, '', $date);
        $ret = 0;
        foreach ($data as $item){
            if($item["match_hometeam_id"] == $name){
                $ret += $item["match_hometeam_score"]; 
            }
            if($item["match_awayteam_id"] == $name){
                $ret += $item["match_awayteam_score"];
                
            }
            }
            return $ret;
        }
        function GetGoalScored1($name, $limit, $date){
        $data = Selector::LoadData($name, $limit, '', $date);
        $res['count'] = count($data);
        $ret = 0;
        foreach ($data as $item){
            if($item["match_hometeam_id"] == $name){
                $ret += $item["match_hometeam_score"]; 
            }
            if($item["match_awayteam_id"] == $name){
                $ret += $item["match_awayteam_score"];
                
            }
            }
            $res['score'] = $ret;
            return $res;
        }
        function GetGoalConceded($name, $limit, $date){
        $data = Selector::LoadData($name, $limit, '', $date);
        $ret = 0;
        foreach ($data as $item){
            if($item["match_hometeam_id"] == $name){
                $ret += $item["match_awayteam_score"]; 
            }
            if($item["match_awayteam_id"] == $name){
                $ret += $item["match_hometeam_score"];
                
            }
            }
            return $ret;
        }
        function GetGoalConceded1($name, $limit, $date){
        $data = Selector::LoadData($name, $limit, '', $date);
        $res['count'] = count($data);
        $ret = 0;
        foreach ($data as $item){
            if($item["match_hometeam_id"] == $name){
                $ret += $item["match_awayteam_score"]; 
            }
            if($item["match_awayteam_id"] == $name){
                $ret += $item["match_hometeam_score"];
                
            }
            }
            $res['score'] = $ret;
            return $res;
        }
        function GetBothScored($name, $limit, $date){
        $data = Selector::LoadData($name, $limit, '', $date);
        $ret = 0;
        foreach ($data as $item){
            if($item["match_hometeam_score"] > 0 && $item["match_awayteam_score"] > 0){
                $ret++; 
            }
            }
            return $ret;
        }
        function GetGoalsPerMatchScored($name, $limit, $data){
            $goals = Selector::GetGoalScored1($name, $limit, $data);
            if($limit == $goals['count']){
                $ret = $goals['score']/$limit;
            }else{
                if($goals['count'] == 0)
                {
                   $ret = 0; 
                }else{
                    $ret = $goals['score']/$goals['count'];
                }
                
            }
            
            return $ret;
        }
        function GetGoalsPerMatchConceded($name, $limit, $data){
            $goals = Selector::GetGoalConceded1($name, $limit, $data);
            if($limit == $goals['count']){
                $ret = $goals['score']/$limit;
            }else{
                if($goals['count'] == 0)
                {
                   $ret = 0; 
                }else{
                    $ret = $goals['score']/$goals['count'];
                }
            }
            return $ret;
        }
        function GetLineGoals($name, $limit = 70, $date){
            $data = Selector::LoadData($name, $limit, '', $date);
            $reversed = array_reverse($data);
            $ret[] = ["date","Забито","Пропущено"];
        
            foreach ($reversed as $val){
                if($val["match_hometeam_id"] == $name){
                    $ret[] = array($val["match_date"], (int)$val["match_hometeam_score"], (int)$val["match_awayteam_score"]);
                }else{
                    $ret[] = array($val["match_date"], (int)$val["match_awayteam_score"], (int)$val["match_hometeam_score"]);
                }
            }
            return $ret;
        }
        
        function GetFormTeam($name, $limit = 70, $date, $n){
            $data = Selector::LoadData($name, $limit, '', $date);
            $reversed = array_reverse($data);
//            debug($reversed);
            $ret[] = ["date",$n];
            $r = 0;
            foreach ($reversed as $valr1){
                if($valr1["match_hometeam_id"] == $name){
                    if((int)$valr1["match_hometeam_score"]>(int)$valr1["match_awayteam_score"]){
                        $r++;
                    }else if((int)$valr1["match_hometeam_score"]==(int)$valr1["match_awayteam_score"]){
                        $r = $r;
                    }else{
                        $r--;
                    }
                }else{
                    if((int)$valr1["match_awayteam_score"]>(int)$valr1["match_hometeam_score"]){
                        $r++;
                    }else if((int)$valr1["match_awayteam_score"]==(int)$valr1["match_hometeam_score"]){
                        $r = $r;
                    }else{
                        $r--;
                    }
                }   
            $ret[] = array($valr1["match_date"], $r);
            }
            return $ret;
        }
        function GetTotals($name, $limit = 70, $date, $n){
            $data = Selector::LoadData($name, $limit, '', $date);
            $reversed = array_reverse($data);
            $ret[] = ["date",$n];
            foreach ($reversed as $valt1){
                $t = $valt1["match_hometeam_score"]+$valt1["match_awayteam_score"];
                $ret[] = array($valt1["match_date"], $t);
            }
            return $ret;
        }
        function GetBarForm($team1, $team2, $limit, $date, $id1, $id2){
            $ret[] = ["Values", $team1, $team2];
            $ret[] = ["Победы", Selector::GetWins($id1, $limit, $date), Selector::GetWins($id2, $limit, $date)];
            $ret[] = ["Поражения", Selector::GetLosses($id1, $limit, $date), Selector::GetLosses($id2, $limit, $date)];
            $ret[] = ["Ничьи", Selector::GetDraws($id1, $limit, $date), Selector::GetDraws($id2, $limit, $date)];
            return $ret;
        }
        function GetBarGoals($team1, $team2, $limit, $date, $id1, $id2){
            $ret[] = ["Values", $team1, $team2];
            $ret[] = ["Забивает", Selector::GetGoalScored($id1, $limit, $date), Selector::GetGoalScored($id2, $limit, $date)];
            $ret[] = ["Пропускает", Selector::GetGoalConceded($id1, $limit, $date), Selector::GetGoalConceded($id2, $limit, $date)];
            $ret[] = ["Обе забивают", Selector::GetBothScored($id1, $limit, $date), Selector::GetBothScored($id2, $limit, $date)];
            return $ret;
        }
        function GetBarGoalsPerMatch($team1, $team2, $limit, $date, $id1, $id2){
            $ret[] = ["Values", $team1, $team2];
            $ret[] = ["Забивает", round(Selector::GetGoalsPerMatchScored($id1, $limit, $date),2), round(Selector::GetGoalsPerMatchScored($id2, $limit, $date),2)];
            $ret[] = ["Пропускает", round(Selector::GetGoalsPerMatchConceded($id1, $limit, $date),2), round(Selector::GetGoalsPerMatchConceded($id2, $limit, $date),2)];
            return $ret;
        }
        function GetBarTotalsOver($team1, $team2, $limit, $date, $id1, $id2){
            $ret[] = ["Values", $team1, $team2];
            $ret[] = ["Больше 1.5", Selector::GetOverTotal($id1, $limit, 2, $date), Selector::GetOverTotal($id2, $limit, 2, $date)];
            $ret[] = ["Больше 2.5", Selector::GetOverTotal($id1, $limit, 3, $date), Selector::GetOverTotal($id2, $limit, 3, $date)];
            $ret[] = ["Больше 3.5", Selector::GetOverTotal($id1, $limit, 4, $date), Selector::GetOverTotal($id2, $limit, 4, $date)];
            
            return $ret;
        }
        function GetBarTotalsInd($team1, $team2, $limit, $date, $id1, $id2){
            $ret[] = ["Values", $team1, $team2];
            $ret[] = ["Больше 0.5", Selector::GetIndTotal($id1, $limit, 1, $date), Selector::GetIndTotal($id2, $limit, 1, $date)];
            $ret[] = ["Больше 1.5", Selector::GetIndTotal($id1, $limit, 2, $date), Selector::GetIndTotal($id2, $limit, 2, $date)];
            $ret[] = ["Больше 2.5", Selector::GetIndTotal($id1, $limit, 3, $date), Selector::GetIndTotal($id2, $limit, 3, $date)];
            
            return $ret;
        }
        function GetDirectWin($name1, $name2, $date, $id1, $id2){
            $data = Selector::LoadData($id1, 1000, "((match_awayteam_id = '$id1' and match_hometeam_id = '$id2') or (match_awayteam_id = '$id2' and match_hometeam_id = '$id1'))", $date);
//            var_dump($data);
//            echo '<br>';
//            echo '<br>';
//            echo '<br>';
//            echo '<br>';
            $win1 = 0;
            $win2 = 0;
            $draw = 0;
            foreach ($data as $item){
                if($item["match_hometeam_id"] == $id1){
                    if($item["match_hometeam_score"] > $item["match_awayteam_score"]){
                        $win1++;
                    }elseif($item["match_hometeam_score"] < $item["match_awayteam_score"]){
                        $win2++;
                    }else{
                        $draw++;
                    }
                }else{
                    if($item["match_hometeam_score"]>$item["match_awayteam_score"]){
                        $win2++;
                    }elseif($item["match_hometeam_score"]<$item["match_awayteam_score"]){
                        $win1++;
                    }else{
                        $draw++;
                    }
                }
            }
            $ret[] = ['Team', 'Matches'];
            $ret[] = [$name1, $win1];
            $ret[] = [$name2, $win2];
            $ret[] = ['Draws', $draw];
            return $ret;
        }
        function GetDirectUnderOver($name1, $name2, $total, $date, $id1, $id2){
            $data = Selector::LoadData($id1, 1000, "((match_awayteam_id = '$id1' and match_hometeam_id = '$id2') or (match_awayteam_id = '$id2' and match_hometeam_id = '$id1'))", $date);
            $under = 0;
            $over = 0;
            foreach ($data as $item){
                if($item["match_hometeam_score"]+$item["match_awayteam_score"]<$total){
                    $under++;
                }else{
                    $over++;
                }
            }
            $ret[] = ['Under/Over', 'Value'];
            $ret[] = ['Больше', $over];
            $ret[] = ['Меньше', $under];
            return $ret;
        }
        function GetDirectScored($name1, $name2, $date, $id1, $id2){
            $data = Selector::LoadData($id1, 1000, "((match_awayteam_id = '$id1' and match_hometeam_id = '$id2') or (match_awayteam_id = '$id2' and match_hometeam_id = '$id1'))", $date);
            $scored1 = 0;
            $scored2 = 0;
            foreach ($data as $item){
                if($item["match_hometeam_id"] == $id1){
                    $scored1 += $item["match_hometeam_score"];
                    $scored2 += $item["match_awayteam_score"];
                } else {
                    $scored2 += $item["match_hometeam_score"];
                    $scored1 += $item["match_awayteam_score"];
                }
            }
            $ret[] = ['Goals scored','values'];
            $ret[] = [$name1, $scored1];
            $ret[] = [$name2, $scored2];
            return $ret;
        }
        function GetDirectConceded($name1, $name2, $date, $id1, $id2){
            $data = Selector::LoadData($id1, 1000, "((match_awayteam_id = '$id1' and match_hometeam_id = '$id2') or (match_awayteam_id = '$id2' and match_hometeam_id = '$id1'))", $date);
            $conceded1 = 0;
            $conceded2 = 0;
            foreach ($data as $item){
                if($item["match_hometeam_id"] == $id1){
                    $conceded2 += $item["match_hometeam_score"];
                    $conceded1 += $item["match_awayteam_score"];
                } else {
                    $conceded1 += $item["match_hometeam_score"];
                    $conceded2 += $item["match_awayteam_score"];
                }
            }
            $ret[] = ['Goals scored','values'];
            $ret[] = [$name1, $conceded1];
            $ret[] = [$name2, $conceded2];
            return $ret;
        }
        function GetDirectPerMatch($name1, $name2, $date, $id1, $id2){
            $data = Selector::LoadData($id1, 1000, "((match_awayteam_id = '$id1' and match_hometeam_id = '$id2') or (match_awayteam_id = '$id2' and match_hometeam_id = '$id1'))", $date);
            $count = count($data);
            $scored1 = 0;
            $scored2 = 0;
            foreach ($data as $item){
                if($item["match_hometeam_id"] == $id1){
                    $scored1 += $item["match_hometeam_score"];
                    $scored2 += $item["match_awayteam_score"];
                } else {
                    $scored2 += $item["match_hometeam_score"];
                    $scored1 += $item["match_awayteam_score"];
                }
            }
            
            $gpm1 = $scored1/$count;
            $gpm2 = $scored2/$count;
            $ret[] = ['Goals scored','values'];
            $ret[] = [$name1, $gpm1];
            $ret[] = [$name2, $gpm2];
            return $ret;
        }
        function GetDirectTotalLine($name1, $name2, $date, $id1, $id2){
            $data = Selector::LoadData($id1, 1000, "((match_awayteam_id = '$id1' and match_hometeam_id = '$id2') or (match_awayteam_id = '$id2' and match_hometeam_id = '$id1'))", $date);
            $count = count($data);
            $reversed = array_reverse($data);
            foreach ($reversed as $item){
                $total[] = $item["match_hometeam_score"]+$item["match_awayteam_score"];
                $match_date[] = $item["match_date"];
            }
            $ret[] = ["date","total"];
            for ($i=0; $i<$count; $i++){
                $ret[$i+1] = array(array_shift($match_date), array_shift($total));
            }
            return $ret;
        }
        function GetDirectCount($name1, $name2, $date, $id1, $id2){
            $data = Selector::LoadData($id1, 1000, "((match_awayteam_id = '$id1' and match_hometeam_id = '$id2') or (match_awayteam_id = '$id2' and match_hometeam_id = '$id1'))", $date);
            return count($data);
        }
        function GetLastScore($id, $date){
            $data = Tutorials::find()->select(['match_hometeam_score', 'match_awayteam_score', 'match_hometeam_id', 'match_awayteam_id'])->where(['match_hometeam_id' => $id])->orWhere(['match_awayteam_id' => $id])->andWhere(['<', 'match_date', $date])->andWhere(['!=', 'match_hometeam_score', 8888])->andWhere(['!=', 'match_awayteam_score', 8888])->one();
//            var_dump($data);
//            echo '<br>';
//            echo '<br>';
            if($data == NULL){
                $ret['scor'] = 'none';
                $ret['cons'] = 'none';
            }else{
                if($data->match_hometeam_id == $id){
                    $ret['scor'] = $data->match_hometeam_score;
                    $ret['cons'] = $data->match_awayteam_score;
                }else{
                    $ret['cons'] = $data->match_hometeam_score;
                    $ret['scor'] = $data->match_awayteam_score;
                }
            }
            return $ret;  
        }
        
        function GetYcard($id, $limit, $date){
            $data = Selector::LoadData($id, $limit, '', $date);
            $ret['count'] = count($data);
            $ret['yc'] = 0;
            foreach ($data as $item){
                if($item['match_hometeam_id'] == $id){
                    $ret['yc'] += $item['t1yc'];
                }else{
                    $ret['yc'] += $item['t2yc'];
                }
            }
            
            return $ret;
        }
        
        function GetRcard($id, $limit, $date){
            $data = Selector::LoadData($id, $limit, '', $date);
            $ret['count'] = count($data);
            $ret['rc'] = 0;
            foreach ($data as $item){
                if($item['match_hometeam_id'] == $id){
                    $ret['rc'] += $item['t1rc'];
                }else{
                    $ret['rc'] += $item['t2rc'];
                }
            }
            return $ret;
        }
        
        function GetYperGame($id, $limit, $date)
        {
            $data = Selector::GetYcard($id, $limit, $date);
            if($data['count'] == 0)
            {
                $ret = 0;
            }
            else
            {
                if($data['count'] == $limit)
                {
                    $ret = $data['yc']/$limit;
                }
                else
                {
                    $ret = $data['yc']/$data['count'];
                }
            }
            return $ret;
        }
        
        function GetRperGame($id, $limit, $date)
        {
            $data = Selector::GetRcard($id, $limit, $date);
            if($data['count'] == 0)
            {
                $ret = 0;
            }
            else
            {
                if($data['count'] == $limit)
                {
                    $ret = $data['rc']/$limit;
                }
                else
                {
                    $ret = $data['rc']/$data['count'];
                }
            }
            return $ret;
        }
        
        function GetBarCard($team1, $team2, $limit, $date, $id1, $id2){
            $ret[] = ["Values", $team1, $team2];
            $yc1 = Selector::GetYcard($id1, $limit, $date);
            $yc2 = Selector::GetYcard($id2, $limit, $date);
            $rc1 = Selector::GetRcard($id1, $limit, $date);
            $rc2 = Selector::GetRcard($id2, $limit, $date);
            $ret[] = ["Желтые карты", $yc1['yc'], $yc2['yc']];
            $ret[] = ["Красные карты", $rc1['rc'], $rc2['rc']];
            return $ret;
        }
        
        function GetBarCardPerGame($team1, $team2, $limit, $date, $id1, $id2){
            $ret[] = ["Values", $team1, $team2];
            $ret[] = ["Желтые карты", Selector::GetYperGame($id1, $limit, $date), Selector::GetYperGame($id2, $limit, $date)];
            $ret[] = ["Красные карты", Selector::GetRperGame($id1, $limit, $date), Selector::GetRperGame($id2, $limit, $date)];
            return $ret;
        }
        
        function GetLineCards($name, $limit = 70, $date){
            $data = Selector::LoadData($name, $limit, '', $date);
            $reversed = array_reverse($data);
            $ret[] = ["date","Желтые","Красные"];
        
            foreach ($reversed as $val){
                if($val["match_hometeam_id"] == $name){
                    $ret[] = array($val["match_date"], (int)$val["t1yc"], (int)$val["t1rc"]);
                }else{
                    $ret[] = array($val["match_date"], (int)$val["t2yc"], (int)$val["t2rc"]);
                }
            }
            return $ret;
        }
    }

