<?php

namespace app\modules\user\controllers;

use app\modules\user\models\forms\EmailConfirmForm;
use app\modules\user\models\forms\LoginForm;
use app\modules\user\models\forms\PasswordResetForm;
use app\modules\user\models\forms\PasswordResetRequestForm;
use app\modules\user\models\forms\SignupForm;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class DefaultController extends Controller
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [ 'logout', 'signup' ],
                'rules' => [
                    [
                        'actions' => [ 'signup' ],
                        'allow' => true,
                        'roles' => [ '?' ],
                    ],
                    [
                        'actions' => [ 'logout' ],
                        'allow' => true,
                        'roles' => [ '@' ],
                    ],
                ],
            ],
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
            /*'notification' => [
                'class' => 'app\components\Log',
            ],*/
        ];
    }
    
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    
    
    public function actionIndex()
    {
        return $this->redirect([ 'profile/index' ], 301);
    }
    
    
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
    
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
    
            //todo: add mailer
            //            $this->trigger('AFTERUSERLOGIN', new Event([ 'sender' => $model ]));
            
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionLogout()
    {
        Yii::$app->user->logout();
        
        return $this->goHome();
    }
    
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                //                Yii::$app->getSession()->setFlash('success', 'Подтвердите регистрацию');
                
                return $this->goHome();
            }
        }
        
        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    
    public function actionEmailConfirm($token)
    {
        try {
            $model = new EmailConfirmForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        
        if ($model->confirmEmail()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', '_EMAIL_CONFIRM_SUCCESS'));
        } else {
            Yii::$app->getSession()->setFlash('error', Yii::t('app', '_EMAIL_CONFIRM_ERROR'));
        }
        
        return $this->goHome();
    }
    
    public function actionPasswordResetRequest()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', '_PASSWORD_RESET_SEND'));
                
                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t('app', '_PASSWORD_RESET_ERROR'));
            }
        }
        
        return $this->render('passwordResetRequest', [
            'model' => $model,
        ]);
    }
    
    public function actionPasswordReset($token)
    {
        try {
            $model = new PasswordResetForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', '_PASSWORD_RESET_SUCCESS'));
            
            return $this->goHome();
        }
        
        return $this->render('passwordReset', [
            'model' => $model,
        ]);
    }
}