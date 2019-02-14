<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
//use app\models\Articles;
use yii\helpers\Url;
//use yii\data\Pagination;



class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
//    public $enableCsrfValidation = false;

    /**
     * Все акшины блога перенесены в BlogController
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
//        стартуем сессию
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }


        if (!(Yii::$app->request->getUrl()==$_SERVER['HTTP_REFERER']) && (!isset($session['HTTP_REFERER'])) ) {
            $session->set('HTTP_REFERER', $_SERVER['HTTP_REFERER']);
        }

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

//            $url_refferer = isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : null;
            $url_refferer = isset($session['HTTP_REFERER']) ? $session['HTTP_REFERER'] : null;

            $session->remove('HTTP_REFERER');
            $session->remove('__returnUrl');

            return $this->goBack($url_refferer);
        }

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
        // стартуем сессию
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }

        if (!(Yii::$app->request->getUrl()==$_SERVER['HTTP_REFERER']) && (!isset($session['HTTP_REFERER1'])) ) {
            $session->set('HTTP_REFERER1', $_SERVER['HTTP_REFERER']);
        }

        $url_refferer = isset($session['HTTP_REFERER1']) ? $session['HTTP_REFERER1'] : null;

//        $artcle_url= isset($_SESSION['HTTP_ARTICLE_REFERER']) ? $_SESSION['HTTP_ARTICLE_REFERER'] : Yii::$app->request->referrer;

        $session->remove('HTTP_REFERER1');
//        $session->remove('__returnUrl');

        Yii::$app->user->logout(false);

//        $session->set('HTTP_ARTICLE_REFERER', $artcle_url);

        return $this->goBack($url_refferer);
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

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
