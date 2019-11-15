<?php

use yii\db\Migration;

/**
 * Handles the creation of table `activity`.
 */
class m190919_142335_create_activity_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('activity', [
            'id' => $this->primaryKey()->comment('Порядковый номер'),
            'title' => $this->string()->notNull()->comment('Название события'),
            'date_start' => $this->string()->comment('Дата начала'),
            'date_end' => $this->string()->comment('Дата окончания'),
            'user_id' => $this->integer()->comment('Создатель события'),
            'description' => $this->text()->comment('Описание события'),
            'repeat' => $this->boolean()->comment('Может ли повторяться'),
            'blocked' => $this->boolean()->comment('Блокирует ли даты'),
        ]);

        // создание реляционной связи на пользователей
        $this->addForeignKey(
            'fk_activity_user',
            'activity', 'user_id',
            'user', 'id',
            'cascade'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_activity_user', 'activity');
        $this->dropTable('activity');
    }
}
