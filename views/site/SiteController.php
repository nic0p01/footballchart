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
use app\models\SignupForm;
use app\models\PasswordResetRequestForm;
use app\models\Odds;
use app\components\Robokassa;
use app\models\ResetPasswordForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
        return $this->render('index');
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
        $model = new SignupForm();
 
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    $mrh_login = "footballchartsru";
                    $mrh_pass1 = "A8jCrPPYI83PTkyQn57a";
                    $inv_id = 0;
                    $inv_desc = "Тестовая оплата";
                    $out_summ = "500.00";
                    $IsTest = 1;
                    $crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");
                    $url = "https://auth.robokassa.ru/Merchant/Index.aspx?MerchantLogin=$mrh_login&OutSum=$out_summ&InvoiceID=$inv_id&Description=$inv_desc&SignatureValue=$crc&IsTest=$IsTest";
                    return $this->redirect($url);
                }
            }
        }
 
        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    public function actionResulPay()
    {
        $mrh_pass2 = "kk3d76OZSlqdGM8fJ0XA";

        //установка текущего времени
        //current date
        $tm=getdate(time()+9*3600);
        $date="$tm[year]-$tm[mon]-$tm[mday] $tm[hours]:$tm[minutes]:$tm[seconds]";

        // чтение параметров
        // read parameters
        $dad = Yii::$app->request->post();
        $out_summ = $dad["OutSum"];
        $inv_id = $dad["InvId"];
        $crc = $dad["SignatureValue"];

        $crc = strtoupper($crc);

        $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2"));

        // проверка корректности подписи
        // check signature
        $f=@fopen("order.txt","a+") or
                  die("error");
        if ($my_crc !=$crc)
        {
          fputs($f,"bad sign\n");
          echo "bad sign\n";
          fclose($f);
          exit();
        }

        // признак успешно проведенной операции
        // success
        echo "OK$inv_id\n";

        // запись в файл информации о проведенной операции
        // save order info to file
        
        fputs($f,"order_num :$inv_id;Summ :$out_summ;Date :$date\n");
        fclose($f);
    }
    public function actionSuccessPay()
    {
        debug(Yii::$app->request->post());
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
    
    
}
