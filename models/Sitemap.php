<?php
namespace app\models;
use Yii;
use yii\base\Model;
use app\models\Tutorials;
use DateTime;
use DateInterval;
use DatePeriod;
class Sitemap extends Model{
    public function getUrl(){
            $urls = array();    
            //Получаем массив URL из таблицы Sef
            $start = new DateTime('2017-01-01'); 
            $interval = new DateInterval('P1D'); 
            $end = new DateTime(date("Y-m-d", strtotime("+2 days"))); 
            $period = new DatePeriod($start, $interval, $end); 
            foreach ($period as $dt){ 
            $d = $dt->format('Y-m-d'); 
            $url_rules = Tutorials::find()->select(['match_id', 'match_date', 'match_hometeam_name', 'match_awayteam_name'])->where(['match_date' => $d])->asArray()->all();
            $urls[] = array('matches/date/'.$d,'daily');
                foreach ($url_rules as $url_rule){
                    $aliash = str_replace(" ","-",$url_rule['match_hometeam_name'] );
                    $aliash = str_replace("(", "", $aliash);
                    $aliash = str_replace('.', '', $aliash);
                    $aliash = str_replace(")", "", $aliash);
                    $aliash = strtolower($aliash);
                    $aliasa = str_replace(" ","-",$url_rule['match_awayteam_name'] );
                    $aliasa = str_replace("(", "", $aliasa);
                    $aliasa = str_replace('.', '', $aliasa);
                    $aliasa = str_replace(")", "", $aliasa);
                    $aliasa = strtolower($aliasa);
                    $alias = 'matches/'.$aliash.'-'.$aliasa.'-'.date("d-m-Y", strtotime($url_rule['match_date'])).'-'.$url_rule['match_id'];
    //                debug($url_rule);
                    $urls[] = array($alias,'daily');
                }
            }
            var_dump($urls);
            return $urls;
    }
    public function getXml($urls){
        $host = Yii::$app->request->hostInfo; // домен сайта    
        ob_start();  
        echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
        <url>
        <loc><?= $host ?></loc>
        <changefreq>daily</changefreq>
        <priority>1</priority>
        </url>
        <?php foreach($urls as $url): ?>
        <url>
        <loc><?= $host.$url[0] ?></loc>
        <changefreq><?= $url[1] ?></changefreq>
        </url>
        <?php endforeach; ?>
        </urlset>
        <?php return ob_get_clean();
    }
    public function showXml($xml_sitemap){
        // устанавливаем формат отдачи контента        
        Yii::$app->response->format = \yii\web\Response::FORMAT_XML;  
        //повторно т.к. может не сработать
        header("Content-type: text/xml");
        echo $xml_sitemap;
        Yii::$app->end(); 
    }    
}
