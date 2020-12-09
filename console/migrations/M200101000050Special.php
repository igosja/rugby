<?php

// TODO refactor

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
                'name' => $this->string(2)->notNull()->unique(),
                'text' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name', 'text'],
            [
                ['Сл', 'Сила'],
                ['Пс', 'Пас'],
                ['Км', 'Комбинирование'],
                ['Сх', 'Сватка'],
                ['Ск', 'Скорость'],
                ['Зх', 'Захват'],
                ['Рк', 'Рак'],
                ['Мл', 'Мол'],
                ['Лд', 'Лидер'],
                ['Ат', 'Атлетизм'],
                ['К', 'Кумир'],
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
