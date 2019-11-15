<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace app\commands;

use Yii;
use yii\base\InvalidRouteException;
use yii\console\Controller;
use yii\console\Exception;

/**
 * Контроллер для настройки базовых RBAC ролей доступа
 *
 * Предварительно выполнить - yii migrate --migrationPath=@yii/rbac/migrations
 * @package app\commands
 */
class RbacController extends Controller
{
    /**
     * Инициализация RBAC миграций и ролей
     *
     * php yii rbac/init
     *
     * @throws InvalidRouteException
     * @throws Exception
     * @throws \Exception
     */
    public function actionInit()
    {
        // аналогично выоплнению в терминале 'php yii migrate --migrationPath=@yii/rbac/migrations'
        Yii::$app->runAction('migrate', ['migrationPath' => '@yii/rbac/migrations']);

        // компонент управления RBAC
        $auth = Yii::$app->authManager;

        /**
         *
         * Создание ролей пользователей
         *
         */
        // Пользователь
        $roleUser = $auth->createRole('user');
        $roleUser->description = 'Обычный пользователь сайта';

        $auth->add($roleUser);

        // Менеджер
        $roleManager = $auth->createRole('manager');
        $roleManager->description = 'Менеджер сайта';

        $auth->add($roleManager);
        $auth->addChild($roleManager, $roleUser); // Менеджер наследует права Пользователя

        // Администратор
        $roleAdmin = $auth->createRole('admin');
        $roleAdmin->description = 'Администратор сайта';

        $auth->add($roleAdmin);
        $auth->addChild($roleAdmin, $roleManager); // Администратор наследует Менеджера (т.е. и Пользователя)

        /**
         *
         * Установка ролей на пользователей
         *
         */
        $auth->assign($roleAdmin, 1);
        $auth->assign($roleManager, 2);
        $auth->assign($roleUser, 3);
    }
}