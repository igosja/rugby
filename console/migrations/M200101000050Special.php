<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000050Special
 * @package console\migrations
 */
class M200101000050Special extends Migration
{
    private const TABLE = '{{%special}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'name' => $this->string(2),
                'text' => $this->string(255)->notNull(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name', 'text'],
            [
                ['Ск', 'Скорость'],
                ['Сб', 'Силовая борьба'],
                ['Т', 'Техника'],
                ['Л', 'Лидер'],
                ['Ат', 'Атлетизм'],
                ['Р', 'Реакция'],
                ['От', 'Отбор'],
                ['Бр', 'Бросок'],
                ['К', 'Кумир'],
                ['Кл', 'Игра клюшкой'],
                ['П', 'Выбор позиции'],
            ]
        );

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown(): bool
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
