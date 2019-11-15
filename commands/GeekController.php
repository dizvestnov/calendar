<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace app\commands;

use yii\console\Controller;

/**
 * Тестовый контроллер для работы с колнсольными процессами
 * @package app\commands
 */
class GeekController extends Controller
{
    // пример для использования options
    public $user = 'John';
    // пример для использования options совместно с aliases
    public $needToShowUserName = false;

    // ключ принудительного использования без проверки на время (cron)
    public $force = false;

    // разрешенные часы для выполнения команды
    private $hours = [
        '18', '20', '21', '22',
    ];

    /**
     * Массив параметров контроллера
     *
     * @param string $actionID
     *
     * @return array|string[]
     */
    public function options($actionID)
    {
        return ['user', 'needToShowUserName', 'force'];
    }

    /**
     * Массив псевдонимов для параметров контроллера
     * @return array
     */
    public function optionAliases()
    {
        return [
            'v' => 'needToShowUserName', // verbose
            'f' => 'force',
        ];
    }

    /**
     * Проверка на разрешения выполнения процесса
     * @return bool
     */
    private function canRun()
    {
        // текщее время в часах
        $currentTime = date('H');

        //var_dump($currentTime);

        // проверка на наличие совпадений в параметрах контроллера
        if (in_array($currentTime, $this->hours)) {
            return true;
        }

        return false;
    }

    /**
     * Основной метод выполнения процесса
     *
     * @param int $times
     */
    public function actionIndex(int $times = 1, $user = false)
    {
        //if ($user) {
        //    //
        //
        //    if (is_numeric($user)) {
        //        // id
        //        //$id = (int)$user;
        //    } else {
        //        // email
        //    }
        //}

        if (!$this->force && !$this->canRun()) {
            return;
        }

        for ($i = 0; $i < $times; $i++) {
            if ($this->needToShowUserName) {
                echo "Hello {$this->user} - {$i} \n";
            } else {
                echo "Hello {$i} \n";
            }
        }
    }
}