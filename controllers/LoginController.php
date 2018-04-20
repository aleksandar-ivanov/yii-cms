<?php


namespace app\controllers;


use app\modules\users\models\User;
use Yii;
use yii\web\Controller;

class LoginController extends Controller
{
    public $layout = 'login';

    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest)
            $this->redirect('/users');

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        $email = Yii::$app->request->post('email');
        $password = Yii::$app->request->post('password');

        if (!$user = User::findByEmail($email)) {
            return $this->loginError();
        }

        if (!password_verify($password, $user->password)) {
            return $this->loginError();
        }

        Yii::$app->user->login($user);

        return $this->redirect('/users');
    }

    protected function loginError()
    {
        Yii::$app->session->setFlash('error', 'Wrong username or password');
        return $this->redirect('/login');
    }
}