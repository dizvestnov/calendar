<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace app\commands;

use app\models\Activity;
use app\models\User;
use Exception;
use Yii;
use yii\console\Controller;

/**
 * Консольный контроллер для инициализации начальных данных приложения
 * @package app\commands
 */
class AppController extends Controller
{
    /**
     * Создание начальных пользователей (admin, manager, user)
     *
     * php yii app/users
     *
     */
    public function actionUsers()
    {
        $users = [
            'admin',
            'manager',
            'user',
        ];

        foreach ($users as $login) {
            $user = new User([
                'username' => $login,
                'access_token' => "{$login}-token",
                'created_at' => time(),
                'updated_at' => time(),
            ]);

            $user->generateAuthKey();
            $user->password = '123123123';

            $user->save();
        }
    }

    /**
     * Создание начальных событий в календаре
     *
     * php yii app/activities
     *
     * @throws Exception
     */
    public function actionActivities()
    {
        $titles = [
            'Первое событие',
            'Второе событие',
            'Третье событие',
            'Unknown событие',
        ];

        $day = 1;
        $today = time();

        foreach ($titles as $title) {
            $activityDate = date('Y-m-d', strtotime("+ {$day} days", $today));

            $activity = new Activity([
                'title' => $title,
                'description' => chunk_split(Yii::$app->security->generateRandomString(64), random_int(10, 20), ' '),
                'user_id' => random_int(1, 3),
                'date_start' => $activityDate,
                'date_end' => $activityDate,
                'blocked' => random_int(0, 1),
                'repeat' => false,
            ]);

            $activity->save();

            $day++;
        }
    }
}