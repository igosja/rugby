<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000124RatingType
 * @package console\migrations
 */
class M200101000124RatingType extends Migration
{
    private const TABLE = '{{%rating_type}}';

    /**
     * @return bool
     */
    public function safeUp()
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'name' => $this->string(255)->notNull(),
                'order' => $this->integer(2)->notNull(),
                'rating_chapter_id' => $this->integer(1)->notNull(),
            ]
        );

        $this->addForeignKey(
            'rating_type_rating_chapter_id',
            self::TABLE,
            'rating_chapter_id',
            '{{%rating_chapter}}',
            'id'
        );

        $this->batchInsert(
            self::TABLE,
            ['name', 'order', 'rating_chapter_id'],
            [
                ['Сила состава', 0, 1],
                ['Средний возраст', 1, 1],
                ['Стадионы', 2, 1],
                ['Посещаемость', 3, 1],
                ['Базы и строения', 4, 1],
                ['Стоимость баз', 5, 1],
                ['Стоимость стадионов', 6, 1],
                ['Игроки', 7, 1],
                ['Общая стоимость', 8, 1],
                ['Рейтинг', 9, 2],
                ['Стадионы', 10, 3],
                ['Автосоставы', 11, 3],
                ['Лига Чемпионов', 12, 3],
                ['Зарплата игроков', 13, 1],
                ['Денег в кассе', 14, 1],
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
