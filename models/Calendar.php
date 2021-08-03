<?php

namespace app\models;

use yii\db\ActiveRecord;

class Calendar extends ActiveRecord{
    
    public function getCalendar($date = '', $selected = '')
    {

        //Проверяем, пришел ли запрос на конкретную дату. Если нет, берем текущую дату.
        if ($date != '')
        {	
                //Проверяем, не пришло ли чего лишнего...
                $pattern = "/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/";
                if (preg_match($pattern, $date)) {
                        $date = $date;
                } else {
                        die('Неправильный параметр');
                }
        }
        else
        {
                $date=date("Y-m-d");
        }
        $sd = explode("-", $date);
                $year 	= $sd[0];
                $month = $sd[1];
                $day 	= $sd[2];
        $toda = date("Y-m-d");
        $tod = explode("-", $toda);
        $toom = $tod[1];
        $too = $tod[2];
        $seld = '';
        $selm = '';
        if($selected != '')
        {
            $sel = explode("-", $selected);
            $selm = $sel[1];
            $seld = $sel[2];
        }
        // Вычисляем число дней в текущем месяце
        $dayofmonth = date('t',
                              mktime(0, 0, 0, $month, 1, $year));
        //Готовим запрос к БД
        $todate = "$year-$month-$dayofmonth";
        $fromdate = "$year-$month-01";
        $res_db = Tutorials::find()->where(['<=', 'match_date', $todate])->andWhere(['>=', 'match_date', $fromdate])->asArray()->all();
        
        
        
        $d = array();$k=array();
            for($i = 1; $i<=$dayofmonth; $i++){
                    $k[$i] = $i;
            }
            $i=0;
            foreach ($res_db as $a) 
            {
//                debug($a);
                    //for($i = 1; $i<=$dayofmonth; $i++){
                    foreach ($k	as $i)
                    {	//Добавление 0 к дате
                            if($i<10) $cd = "$year-$month-0".$i; else $cd = "$year-$month-$i";
                            if ($cd >= $a['match_date'] && $cd <= $a['match_date'])
                            {
                                    $d[$i] = $cd;
                                    unset($k[$i]);
                            }
                    }
            }
        
        
        // Счётчик для дней месяца
        $day_count = 1;

        // 1. Первая неделя
        $num = 0;
        for($i = 0; $i < 7; $i++)
        {
          // Вычисляем номер дня недели для числа
          $dayofweek = date('w',
                            mktime(0, 0, 0, $month, $day_count, $year));
          // Приводим к числа к формату 1 - понедельник, ..., 6 - суббота
          $dayofweek = $dayofweek - 1;
          if($dayofweek == -1) $dayofweek = 6;

          if($dayofweek == $i)
          {
            // Если дни недели совпадают,
            // заполняем массив $week
            // числами месяца
            $week[$num][$i] = $day_count;
            $day_count++;
          }
          else
          {
            $week[$num][$i] = "";
          }
        }

        // 2. Последующие недели месяца
        while(true)
        {
          $num++;
          for($i = 0; $i < 7; $i++)
          {
            $week[$num][$i] = $day_count;
            $day_count++;
            // Если достигли конца месяца - выходим
            // из цикла
            if($day_count > $dayofmonth) break;
          }
          // Если достигли конца месяца - выходим
          // из цикла
          if($day_count > $dayofmonth) break;
        }

        // 3. Выводим содержимое массива $week
        // в виде календаря
        // Выводим таблицу
        $res =  '<table id="calendar">';
        //заголовок
        $rusdays = array('ПН','ВТ','СР','ЧТ','ПТ','СБ','ВС');
        $rusmonth = array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');
        $res .= '<thead><tr style="text-align: center;"><td style="cursor: pointer;" onclick="monthf(\'prev\','.$month.','.$year.');"><</td><td colspan="5">'.$rusmonth[$month-1].' '.$year.'</td><td style="cursor: pointer;" onclick="monthf(\'next\','.$month.','.$year.');">></td></tr>';
        $res .= '<tr>';
        foreach ($rusdays as $rusday){
              $res .= '<td>'.$rusday.'</td>';
        }
        $res .= '</tr>';
        $res .= '</thead>';
        //тело календаря
        for($i = 0; $i < count($week); $i++)
        {
          $res .= "<tr>";
          for($j = 0; $j < 7; $j++)
          {
            if(!empty($week[$i][$j]))
            {

                      // Если имеем дело с сегодняшней датой подсвечиваем ee
                      if($week[$i][$j]==$too && $month == $toom)
                      {
                            $res .= '<td class="today">';
                      }
                      elseif($week[$i][$j]==$seld && $month == $selm)
                      {
                            $res .= '<td class="selected">';
                      }
                      else
                      {
                              $res .= '<td>';
                      }

                      // Если запись в базе за текущую дату есть, делаем ссылку
//                      debug($d[$week[$i][$j]]);
                     
              if(isset($d[$week[$i][$j]]))
                      {
                              $res .= '<a href="/matches/date/'.$d[$week[$i][$j]].'">'.$week[$i][$j].'</a>';
                      }
                      else
                      {
//                          debug($week[$i][$j]);
                              $res .= $week[$i][$j];
                      }

              $res .= '</td>';
            }
            else $res .= "<td> </td>";
          }
          $res .= "</tr>";
        }
        $res .= '</table>';
        
        return $res;
        
        
    }
     
    
    
    
}
