<?php

namespace app\controllers;

use app\models\Activity;
use Yii;
use yii\caching\DbDependency;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\PageCache;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ActivityController extends Controller
{
	/**
	 * Настройка поведений контроллера (ACF доступы)
	 * @return array
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'allow' => true,
						'actions' => ['index', 'view', 'update', 'delete', 'submit'],
						'roles' => ['@'],
					],
				],
			],

			// кеширование страницы index
			[
				'class' => PageCache::class,
				'only' => ['index'],
				'duration' => 120,
				'dependency' => [
					'class' => DbDependency::class,
					'sql' => 'SELECT COUNT(*) FROM activity',
				],
			],
		];
	}

	/**
	 * Просмотр всех событий
	 * @return string
	 */
	public function actionIndex()
	{
		$query = Activity::find();

		// добавим условие на выборку по пользователю, если это не менеджер
		if (!Yii::$app->user->can('manager')) {
			$query->andWhere(['user_id' => Yii::$app->user->id]);
		}

		$provider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'validatePage' => false,
			],
		]);

		return $this->render('index', [
			'provider' => $provider,
		]);
	}

	/**
	 * Просмотр выбранного события
	 *
	 * @param int $id
	 *
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionView(int $id)
	{
		// ключ для записи в кеш
		$cacheKey = "activity_{$id}"; // activity_1

		// проверка на наличие в кеше
		if (Yii::$app->cache->exists($cacheKey)) {
			$item = Yii::$app->cache->get($cacheKey);
		} else {
			// получение из бд с сохранением в кеш
			$item = Activity::findOne($id);

			Yii::$app->cache->set($cacheKey, $item);
		}

		// просматривать записи может только создатель или менеджер
		if (Yii::$app->user->can('manager') || $item->user_id == Yii::$app->user->id) {
			return $this->render('view', [
				'model' => $item,
			]);
		} else {
			throw new NotFoundHttpException();
		}
	}

	/**
	 * Создание нового события
	 *
	 * @param int|null $id
	 *
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionUpdate(int $id = null)
	{
		$item = $id ? Activity::findOne($id) : new Activity([
			'user_id' => Yii::$app->user->id,
		]);

		// обновлять записи может только создатель или менеджер
		if (Yii::$app->user->can('manager') || $item->user_id == Yii::$app->user->id) {
			if ($item->load(Yii::$app->request->post()) && $item->validate()) {
				if ($item->save()) {
					return $this->redirect(['activity/view', 'id' => $item->id]);
				}
			}

			return $this->render('edit', [
				'model' => $item,
			]);
		} else {
			throw new NotFoundHttpException();
		}
	}

	/**
	 * Удаление выбранного события
	 *
	 * @param int $id
	 *
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionDelete(int $id)
	{
		$item = Activity::findOne($id);

		// удалять записи может только создатель или менеджер
		if ($item->user_id == Yii::$app->user->id || Yii::$app->user->can('manager')) {
			$item->delete();

			return $this->redirect(['activity/index']);
		}

		throw new NotFoundHttpException();
	}
}
