<?php

// TODO refactor

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
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'field' => $this->string(255)->notNull(),
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
            ['field', 'name', 'order', 'rating_chapter_id'],
            [
                ['power_vs_place', 'Сила состава', 0, 1],
                ['age_place', 'Средний возраст', 1, 1],
                ['stadium_place', 'Стадионы', 2, 1],
                ['visitor_place', 'Посещаемость', 3, 1],
                ['base_place', 'Базы и строения', 4, 1],
                ['price_base_place', 'Стоимость баз', 5, 1],
                ['price_stadium_place', 'Стоимость стадионов', 6, 1],
                ['player_place', 'Игроки', 7, 1],
                ['price_total_place', 'Общая стоимость', 8, 1],
                ['rating_place', 'Рейтинг', 9, 2],
                ['stadium_place', 'Стадионы', 10, 3],
                ['auto_place', 'Автосоставы', 11, 3],
                ['league_place', 'Лига Чемпионов', 12, 3],
                ['salary_place', 'Зарплата игроков', 13, 1],
                ['finance_place', 'Денег в кассе', 14, 1],
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
