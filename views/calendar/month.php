<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

use yii\web\View;

/**
 * @var View $this
 * @var array $events
 */

?>

<?= edofre\fullcalendar\Fullcalendar::widget([
    'options' => [
        'id' => 'calendar',
        'language' => 'ru',
    ],
    'clientOptions' => [
        'weekNumbers' => true,
        'selectable' => true,
        'defaultView' => 'month',
    ],
    'events' => $events,
]);
?>
