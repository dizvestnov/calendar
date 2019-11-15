<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use app\models\forms\SignupForm;
use Yii;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

class SiteController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::class,
				'only' => ['logout'],
				'rules' => [
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
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
			'error' => ['class' => ErrorAction::class],
			'captcha' => ['class' => CaptchaAction::class, 'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null],
		];
	}

	/**
	 * Показать главную страницу
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}

	/**
	 * Страница с формой входа на сайт
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

		$model->password = '';

		return $this->render('login', [
			'model' => $model,
		]);
	}

	/**
	 * Страница с формой регистрации
	 *
	 * @return Response|string
	 * @throws \yii\base\Exception
	 */
	public function actionSignup()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new SignupForm();

		if ($model->load(Yii::$app->request->post()) && $model->register()) {
			return $this->goHome();
		}

		return $this->render('signup', [
			'model' => $model,
		]);
	}

	/**
	 * Выход из пользовательской сессии
	 *
	 * @return Response
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}
}
