<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Tutorials;
use app\models\User;
use app\models\SignupForm;
use app\models\PasswordResetRequestForm;
use app\models\Odds;
use app\components\Robokassa;
use app\models\ResetPasswordForm;
use DateTime;
use DateInterval;
use DatePeriod;
use app\models\Sitemap;
use app\models\Utms;
use app\models\Invoice;
use app\modules\admin\models\Blog;
use app\models\TutorialsSearch;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    
    public $utm_source;
    public $utm_medium;
    public $utm_campaign;
    public $utm_term;
    public $utm_content;
    public $referer;
                
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    
    public function beforeAction($action) {
    if (!\Yii::$app->user->isGuest) {
        if(\Yii::$app->user->identity->role == 5){
            $invoice = Invoice::find()->where(['user_id' => \Yii::$app->user->identity->id])->one();
            if($invoice != NULL){
                $utm = Utms::find()->where(['invoice_id' => $invoice->id])->one();
                if($utm == NULL){
                    if(isset($_COOKIE['utm_source']) || isset($_COOKIE['referer'])){
                        $utm = new Utms();
                        $utm->invoice_id = $invoice->id;
                        if(isset($_COOKIE['utm_source'])){
                            $utm->utm_source = $_COOKIE['utm_source'];
                        }
                        if(isset($_COOKIE['utm_medium'])){
                            $utm->utm_medium = $_COOKIE['utm_medium'];
                        }
                        if(isset($_COOKIE['utm_campaign'])){
                            $utm->utm_campaign = $_COOKIE['utm_campaign'];
                        }
                        if(isset($_COOKIE['utm_term'])){
                            $utm->utm_term = $_COOKIE['utm_term'];
                        }
                        if(isset($_COOKIE['utm_content'])){
                            $utm->utm_content = $_COOKIE['utm_content'];
                        }
                        if(isset($_COOKIE['referer'])){
                            $utm->referer = $_COOKIE['referer'];
                        }
                        $utm->save(false);
                    }
                }
            }
        }
    }
    return parent::beforeAction($action);
}

    /**
     * {@inheritdoc}
     */
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        $session->open();
        $date = date("Y-m-d");
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

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        $this->layout = false;
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    
    public function actionContract()
    {
        return $this->render('contract');
    }
    
    public function actionPolicy()
    {
        return $this->render('policy');
    }
    
    public function actionFaq()
    {
        $content = Blog::find()->where(['alias' => 'kak-rabotat-s-sajtom-i-grafikami'])->asArray()->one();
        return $this->render('faq', [
            'content' => $content,
        ]);
    }
    
    /**
     * Displays about page.
     *
     * @return string
     */
//    public function actionAbout()
//    {
//        return $this->render('about');
//    }
    
    public function actionSignup()
    {
        if(!\Yii::$app->user->isGuest){
            $this->goHome();
        }
        $model = new SignupForm();
 
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    
                    $uid = $user->id;
                    $mrh_login = "footballchartsru";
                    $mrh_pass1 = "fUSaXkP3S2KF0YQ5AB5K";
                    $inv_id = 0;
                    $inv_desc = "Оплата премиум доступа";
                    $out_summ = 1190;
                    $tax = '{"sno":"usn_income","items":[{"name":"доступ к сайту","quantity":1,"sum":1190,"payment_method":"full_payment","payment_object":"service","tax":"none"}]}';
                    
                    $crc = md5("$mrh_login:$out_summ:$inv_id:$tax:$mrh_pass1:Shp_uid=$uid");
                    $url = "https://auth.robokassa.ru/Merchant/Index.aspx?MerchantLogin=$mrh_login&OutSum=$out_summ&InvoiceID=$inv_id&Description=$inv_desc&Receipt=$tax&SignatureValue=$crc&Shp_uid=$uid";
                    return $this->redirect($url);
                }
            }
        }
 
        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    
    public function actionSignupPartner()
    {
        $model = new SignupForm();
 
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                $random = Yii::$app->security->generateRandomString(10);
                $user->referer_link = strtoupper($random);
                $user->role = 8;
                $user->save(false);
                $utms = Utms::find()->where(['referer' => $user->referer_link])->all();
                if (Yii::$app->getUser()->login($user)) {
                    return $this->render('cabinet', [
                        'model' => $utms,
                    ]);
                }
                
            }
        }
 
        return $this->render('signupPartner', [
            'model' => $model,
        ]);
    }
    
    public function actionPay()
    {
        $uid = Yii::$app->user->identity->id;
        $mrh_login = "footballchartsru";
                    $mrh_pass1 = "fUSaXkP3S2KF0YQ5AB5K";
                    $inv_id = 0;
                    $inv_desc = "Оплата премиум доступа";
                    $out_summ = 1190;
                    $tax = '{"sno":"usn_income","items":[{"name":"доступ к сайту","quantity":1,"sum":1190,"payment_method":"full_payment","payment_object":"service","tax":"none"}]}';
                    
                    $crc = md5("$mrh_login:$out_summ:$inv_id:$tax:$mrh_pass1:Shp_uid=$uid");
                    $url = "https://auth.robokassa.ru/Merchant/Index.aspx?MerchantLogin=$mrh_login&OutSum=$out_summ&InvoiceID=$inv_id&Description=$inv_desc&Receipt=$tax&SignatureValue=$crc&Shp_uid=$uid";
                    return $this->redirect($url);
    }

        public function actionResultPay()
    {
        $mrh_pass2 = "JP52qP2d0FhAydbzf9hH";

        //установка текущего времени
        //current date
        $tm=getdate(time()+9*3600);
        $date="$tm[year]-$tm[mon]-$tm[mday] $tm[hours]:$tm[minutes]:$tm[seconds]";

        // чтение параметров
        // read parameters
        $shp = Yii::$app->request->get('Shp_uid');
        $out_summ = Yii::$app->request->get('OutSum');
        $inv_id = Yii::$app->request->get('InvId');
        $crc = Yii::$app->request->get('SignatureValue');

        $crc = strtoupper($crc);

        $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_uid=$shp"));

        // проверка корректности подписи
        // check signature
        $f=@fopen("order.txt","a+") or
                  die("error");
        
        if ($my_crc !=$crc)
        {
            
            fputs($f,"bad sign\n$out_summ\n$inv_id\n$crc\n$shp\n");
            echo "bad sign\n";
            fclose($f);
            exit();
        }

        $user = User::findOne($shp);
            if($user->role == 1){
                $user->role = 5;
                $user->date_start = date("Y-m-d H:i:s");
                $user->date_end = date("Y-m-d H:i:s", strtotime("+1 month"));
            }else{
                $start = date("Y-m-d H:i:s", strtotime($user->date_start));
                $end = date("Y-m-d H:i:s", strtotime('+1 MONTH', strtotime($start)));
                $user->date_end = $end;
            }
            
        $user->save(false);
            $invoice = new Invoice();
            $invoice->inv_id = $inv_id;
            $invoice->user_id = $user->id;
            $invoice->date = date("Y-m-d H:i:s");
            $invoice->save(false);
           
        // признак успешно проведенной операции
        // success
        echo "OK$inv_id\n";
        
        // запись в файл информации о проведенной операции
        // save order info to file
        
        fputs($f,"order_num :$inv_id;Summ :$out_summ;Date :$date;User $shp\n");
        fclose($f);
    }

    public function actionCabinet()
    {
        $user = User::find()->where(['id' => Yii::$app->user->id])->one();
//        debug($user);
        $utms = Utms::find()->where(['referer' => $user->referer_link])->all();
        return $this->render('cabinet', [
            'model' => $utms,
        ]);
    }

    public function actionSuccessPay()
    {
        $mrh_pass1 = "fUSaXkP3S2KF0YQ5AB5K";

        // чтение параметров
        // read parameters
        $shp = Yii::$app->request->get('Shp_uid');
        $out_summ = Yii::$app->request->get('OutSum');
        $inv_id = Yii::$app->request->get('InvId');
        $crc = Yii::$app->request->get('SignatureValue');

        $crc = strtoupper($crc);

        $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:Shp_uid=$shp"));

        // проверка корректности подписи
        // check signature
        $user = User::findOne($shp);
        $invoice = Invoice::find()->where(['inv_id' => $inv_id])->one();
        $utm_temp = Utms::find()->where(['invoice_id' => $invoice->id])->one();
        if($utm_temp == NULL) {
            if(isset($_COOKIE['utm_source']) || isset($_COOKIE['referer'])){
                $utm = new Utms();
                $utm->invoice_id = $invoice->id;
                if(isset($_COOKIE['utm_source'])){
                    $utm->utm_source = $_COOKIE['utm_source'];
                }
                if(isset($_COOKIE['utm_medium'])){
                    $utm->utm_medium = $_COOKIE['utm_medium'];
                }
                if(isset($_COOKIE['utm_campaign'])){
                    $utm->utm_campaign = $_COOKIE['utm_campaign'];
                }
                if(isset($_COOKIE['utm_term'])){
                    $utm->utm_term = $_COOKIE['utm_term'];
                }
                if(isset($_COOKIE['utm_content'])){
                    $utm->utm_content = $_COOKIE['utm_content'];
                }
                if(isset($_COOKIE['referer'])){
                    $utm->referer = $_COOKIE['referer'];
                }
                $utm->save(false);
            }
        }
        
        if ($my_crc != $crc)
        {
          echo "bad sign";
          echo '<br>';
          echo "Что то пошло не так!";
          echo '<br>';
          echo "Или с сервисом оплаты какие то проблемы, то вам нужно с нами связаться по e-mail: info@footballcharts.ru";
          echo '<br>';
          echo "Или вы мошенник, и пытались подменить данные";
          echo '<br>';
//          return $this->render('success');
        }
        
        return $this->render('success', [
            'utm' => $this->utm_campaign,
        ]);
        
    }
    
    public function actionFailPay(){
        return $this->render('fail');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
 
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте почту для дальнейших инсрукций.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'К сожалению, мы не можем сбросить пароль для предоставленной электронной почты.');
            }
        }
        return $this->render('passwordResetRequestForm', [
            'model' => $model,
        ]);
    }
 
    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
 
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль сохранен.');
            return $this->goHome();
        }
 
        return $this->render('resetPasswordForm', [
            'model' => $model,
      ]);
 
    }
    
//    public function actionEvents()
//    {
//        $today = '2018-11-11';
//        $too = '2019-11-11';
////        $today = date("Y-m-d", strtotime("-1 days"));
////        $too = date("Y-m-d", strtotime("+2 days"));
//
//        $from = $today;
//        $to = $too;
//        set_time_limit(0); 
//        $key = '89ff73ac8b5d5a3c8471a4d848e7ca5db36398e05e3cb89d3e74240336e997c3';
//        $start = new DateTime('2017-01-01'); 
//        $interval = new DateInterval('P1D'); 
//        $end = new DateTime('2019-11-25'); 
//        $period = new DatePeriod($start, $interval, $end); 
//        foreach ($period as $dt) 
//        { 
//            $d = $dt->format('Y-m-d'); 
//            $key = '89ff73ac8b5d5a3c8471a4d848e7ca5db36398e05e3cb89d3e74240336e997c3';
//
//        $curl_options = array(
//          CURLOPT_URL => "https://apiv2.apifootball.com/?action=get_events&from=$d&to=$d&APIkey=$key",
//          CURLOPT_RETURNTRANSFER => true,
//          CURLOPT_HEADER => false,
//          CURLOPT_TIMEOUT => 30,
//          CURLOPT_CONNECTTIMEOUT => 5
//        );
//
//        $curl = curl_init();
//        curl_setopt_array( $curl, $curl_options );
//        $result = curl_exec( $curl );
//
//        $result = (array) json_decode($result);
//        $exept = [10034, 10035, 10036, 10061, 9998, 10009, 10013, 10023, 10039, 10040, 10044, 10049, 10050, 10053, 10054, 10055, 8634];
//        
//        foreach ($result as $items)
//        {
//            $match_id = $items->match_id;
//            $tutorials = Tutorials::find()->where(['match_id' => $match_id])->one();
//            $country_id = $items->country_id;
//            if($country_id == null)                continue;
//            $ligue_id = $items->league_id;
//            if(in_array($items->league_id, $exept))                continue;
//            $team1_id = $items->match_hometeam_id;
//            $team2_id = $items->match_awayteam_id;
//            if($items->match_hometeam_name == ''){
//                continue;
//            }else{
//                $team1 = $items->match_hometeam_name;
//            }
//
//            if($items->match_hometeam_score == '' || $items->match_hometeam_score == '?'){
//                $score1 = 8888;
//            }else{
//                $score1 = $items->match_hometeam_score;
//            }
//            $team2 = $items->match_awayteam_name;
//            if($items->match_awayteam_score == '' || $items->match_awayteam_score == '?'){
//                $score2 = 8888;
//            }else{
//                $score2 = $items->match_awayteam_score;
//            }
//            $country = $items->country_name;
//            $ligue = $items->league_name;
//            if(stripos($ligue, 'Friend') !==false){  
//                continue;
//            }
//            $match_date = $items->match_date;
//            $match_time = $items->match_time;
//            $match_time = str_replace(':', '-', $match_time);
//            $status = $items->match_status;
//            if($status == 'Cancelled' || $status == 'FRO')
//            {
//                continue;
//            }
//            if(count($items->cards) == 0)
//            {
//                $t1yc = 0;
//                $t2yc = 0;
//                $t1rc = 0;
//                $t2rc = 0;
//            }
//            else
//            {
//                $cards1 = array();
//                $cards2 = array();
//                foreach ($items->cards as $c)
//                {
//                    if($c->home_fault == '' && $c->away_fault == '')
//                    {
//                        
//                    }
//                    else
//                    {
//                        if($c->home_fault == '')
//                        {
//                            if($c->card == 'yellow card')
//                            {
//                                $cards2['yc'][$c->time] = $c->away_fault;
//                            }
//                            else
//                            {
//                                $cards2['rc'][$c->time] = $c->away_fault;
//                            }
//                            
//                        }
//                        else
//                        {
//                            if($c->card == 'yellow card')
//                            {
//                                $cards1['yc'][$c->time] = $c->home_fault;
//                            }
//                            else
//                            {
//                                $cards1['rc'][$c->time]  = $c->home_fault;
//                            }
//                        }
//                    }
//                  
//                }
//                if(isset($cards2['rc']))
//                {
//                    $t2rc = count($cards2['rc']);
//                }
//                else
//                {
//                    $t2rc = 0;
//                }
//                if(isset($cards1['rc']))
//                {
//                    $t1rc = count($cards1['rc']);
//                }
//                else
//                {
//                    $t1rc = 0;
//                }
//                if(isset($cards1['yc']))
//                {
//                    $t1yc = count($cards1['yc']);
//                }
//                else
//                {
//                    $t1yc = 0;
//                }
//                if(isset($cards2['yc']))
//                {
//                    $t2yc = count($cards2['yc']);
//                }
//                else
//                {
//                    $t2yc = 0;
//                }
//                
//                
//            }
////            var_dump($tutorials);
//            if($tutorials == null) continue;
//            
//                    $tutorials->match_hometeam_score = $score1;
//                    $tutorials->match_awayteam_score = $score2;
//                    $tutorials->match_status = $status;
//                    $tutorials->t1yc = $t1yc;
//                    $tutorials->t2yc = $t2yc;
//                    $tutorials->t1rc = $t1rc;
//                    $tutorials->t2rc = $t2rc;
//                    $tutorials->save(false);
//                    echo 'Обновлено событие ' . $tutorials->match_id . "\n";
//                
//        }
//        }
//        return ExitCode::OK;    
//    }
//    public function actionSitemap(){
//    $sitemap = new Sitemap();
//    //Если в кэше нет карты сайта        
//    if (!$xml_sitemap = Yii::$app->cache->get('sitemap')) {
//        //Получаем мыссив всех ссылок
//        $urls = $sitemap->getUrl();
//        //Формируем XML файл
////        $xml_sitemap = $sitemap->getXml($urls);
//        // кэшируем результат
////        Yii::$app->cache->set('sitemap', $xml_sitemap, 3600*12); 
//    } 
//    //Выводим карту сайта
////    $sitemap->showXml($xml_sitemap);
//}
    
}
