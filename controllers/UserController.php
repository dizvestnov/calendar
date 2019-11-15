<?php

namespace app\controllers;

use app\models\Activity;
use app\models\forms\SignupForm;
use app\models\forms\UpdateUserForm;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Контроллер для управления пользователями
 * @package app\controllers
 */
class UserController extends Controller
{
	/**
	 * Набор поведений
	 * @return array
	 */
	public function behaviors()
	{
		return [
			'access' => [
				// доступ только для админов
				'class' => AccessControl::class,
				'rules' => [
					[
						'allow' => true,
						'roles' => ['admin'],
					],
				],
			],
		];
	}

	/**
	 * Просмотр списка пользователей
	 * @return string
	 */
	public function actionIndex()
	{
		$dataProvider = new ActiveDataProvider([
			'query' => User::find(),
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Просмотр информации по выбранному пользователю
	 * @param $id
	 *
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	public function actionActivities($id)
	{
		$user = User::findIdentity($id);

		return $user->username;
		//var_dump($user->activities);
	}

	/**
	 * Вывод формы создания нового пользователя
	 * @return string|\yii\web\Response
	 * @throws \yii\base\Exception
	 */
	public function actionCreate()
	{
		$model = new SignupForm();

		if ($model->load(Yii::$app->request->post()) && $model->register()) {
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Удаление пользователя
	 * @param $id
	 *
	 * @return \yii\web\Response
	 * @throws NotFoundHttpException
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	public function actionProfile()
	{
		/** @var User $user */
		$user = Yii::$app->user->identity;

		$provider = new ActiveDataProvider([
			'query' => Activity::find()->where(['user_id' => $user->id]),
			'pagination' => [
				'validatePage' => false,
			],
		]);

		$model = new UpdateUserForm(
			$user->toArray(['username'])
		);

		if ($model->load(Yii::$app->request->post()) && $model->update($user)) {
			Yii::$app->session->setFlash('success', 'Изменения успешно сохранены');
		}

		return $this->render('profile', compact('model', 'provider'));
	}

	/**
	 * Метод помощник для поиска пользователя с генерацией 404 ошибки
	 * @param $id
	 *
	 * @return User|null
	 * @throws NotFoundHttpException
	 */
	protected function findModel($id)
	{
		if (($model = User::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
