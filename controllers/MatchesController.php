<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;
use Selector;
use app\models\Tutorials;
use app\models\Odds;
use app\models\Calendar;
use yii\web\Controller;
use yii\web\JsExpression;
use Yii;
use app\models\TutorialsSearch;
/**
 * Description of MatchesController
 *
 * @author nic0p
 */
class MatchesController extends Controller{
    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            
        ];
    }
    
    public function actionIndex($date_g = '')
    {
        $session = Yii::$app->session;
        $session->open();
        if($date_g == ''){
            $date = date("Y-m-d");
        }else{
            $date = $date_g;
        }
        $session->set('selected', $date);
        $searchModel = new TutorialsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $date);
        $dataProvider->pagination = false;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'n_date' => $date,
        ]);
    }
    
    public function actionView($id)
    {
        $session = Yii::$app->session;
        $session->open();
        $id_e = explode('-', $id);
        $id_end = end($id_e);

	$model = Tutorials::find()->where(['match_id' => (int)$id_end])->one();
//        var_dump($model);
//        if(is_object($model)){
//            $odds = Odds::find()->where(['match_id' => $model->match_id])->all();
//        }else{
//            $odds = Odds::find()->where(['match_id' => $model['match_id']])->all();
//        }
        $session->set('selected', $model->match_date);
	return $this-> render('view', [
            'model' => $model,
        ]);
    }
    
    public function actionTxt($dt)
    {
        set_time_limit(0); 
        $filename = 'file.txt';
        $handler = fopen($filename, "w+");
        $data = Tutorials::find()->select(['match_hometeam_score', 'match_awayteam_score', 'match_hometeam_name', 'match_awayteam_name', 'match_hometeam_id', 'match_awayteam_id', 'match_date'])->where(['match_date' => $dt])->orderBy(['match_date' => SORT_DESC])->asArray()->all();
        $text = '';
        foreach ($data as $item)
        {
           
            
            if($item['match_hometeam_score'] == '8888')
            {
                $score1 = '';
            }
            else
            {
                $score1 = $item['match_hometeam_score'];
            }
            if($item['match_awayteam_score'] == '8888')
            {
                $score2 = '';
            }
            else
            {
                $score2 = $item['match_awayteam_score'];
            }
            
            $sdata = $this->get20($item['match_hometeam_id'], $item['match_date']);
            $ldata = $this->get20($item['match_awayteam_id'], $item['match_date']);
            $text .= $item['match_hometeam_name'].'('.$score1.') - '.$item['match_awayteam_name'].'('.$score2.')'.$sdata.$ldata."\n";
        }
//         debug($text);
        fwrite($handler, $text); // Записываем во временный файл
        fseek($handler, 0); // Устанавливаем указатель файла
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filename));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        // читаем файл и отправляем его пользователю
        readfile($filename);
        
        unlink($filename);
        exit;
        
    }
    
    function get20($id, $date)
    {
        $ldata = Tutorials::find()->select(['match_hometeam_score', 'match_awayteam_score', 'match_hometeam_id', 'match_awayteam_id'])->where(['match_hometeam_id' => $id])->orWhere(['match_awayteam_id' => $id])->andWhere(['<', 'match_date', $date])->andWhere(['!=', 'match_hometeam_score', '8888'])->orderBy(['match_date' => SORT_DESC])->limit(20)->asArray()->all();
        $ltext = '';
        foreach ($ldata as $litem)
            {
               
                if($litem['match_hometeam_id'] == $id)
                {
                    $scor1 = $litem['match_hometeam_score'];
                    $lose1 = $litem['match_awayteam_score'];
                }else
                {
                    $lose1 = $litem['match_hometeam_score'];
                    $scor1 = $litem['match_awayteam_score'];
                }
                $ltext .= ', '.$scor1.' , '.$lose1.' '; 
            }
        return $ltext;
    }
    public function actionCal()
    {
         
        $date = Yii::$app->request->get('date');
        $c = new Calendar();
        $ret =  $c->getCalendar($date);
        
        
        return $ret;
    } 
    
}
