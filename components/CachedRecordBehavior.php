<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace app\components;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Поведение для автоматического удаления / сохранения записи в кеше
 * @package app\components
 */
class CachedRecordBehavior extends Behavior
{
    /**
     * @var string Префикс для ключа кеша
     */
    public $prefix;

    /**
     * Массив событий для срабатыавания поведения
     * @return array
     */
    public function events()
    {
        return [
            // после изменения модели
            ActiveRecord::EVENT_AFTER_UPDATE => 'clearCache',

            // после получения модели (TODO: реализовать в ДЗ)
            ActiveRecord::EVENT_AFTER_FIND => 'cache',
        ];
    }

    /**
     * Построение уникального ключа
     * @return string
     */
    private function buildKey()
    {
        return "{$this->prefix}_{$this->owner->id}";
    }

    /**
     * Удаление записи из кеша
     * @return bool
     */
    public function clearCache()
    {
        return Yii::$app->cache->delete(
            $this->buildKey()
        );
    }

    /**
     * Метод получения / кеширования модели
     * @return bool
     */
    public function cache()
    {
        // TODO: реализовать сохранение в БД
        // так как этот метод вызывается при наступлении EVENT_AFTER_FIND,
        // нужно сохранять просто $this->owner
    }
}