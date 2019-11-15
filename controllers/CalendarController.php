<?php

/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace app\controllers;

use app\models\Activity;
use Yii;
use yii\web\Controller;

class CalendarController extends Controller
{
	public function actionIndex()
	{
		$events = Activity::findAll(['user_id' => Yii::$app->user->id]);

		$events = array_map(function (Activity $event) {
			return $event->toEvent();
		}, $events);

		return $this->render('month', compact('events'));
	}
}
