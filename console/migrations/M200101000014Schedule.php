<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000014Schedule
 * @package console\migrations
 */
class M200101000014Schedule extends Migration
{
    private const TABLE = '{{%schedule}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'date' => $this->integer(11)->notNull(),
                'season_id' => $this->integer(3)->notNull(),
                'stage_id' => $this->integer(2)->notNull(),
                'tournament_type_id' => $this->integer(1)->notNull(),
            ]
        );

        $this->addForeignKey('schedule_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('schedule_stage_id', self::TABLE, 'stage_id', '{{%stage}}', 'id');
        $this->addForeignKey(
            'schedule_tournament_type_id',
            self::TABLE,
            'tournament_type_id',
            '{{%tournament_type}}',
            'id'
        );

        $date = strtotime('Mon') + 9 * 60 * 60;
        $this->batchInsert(
            self::TABLE,
            [
                'date',
                'season_id',
                'stage_id',
                'tournament_type_id',
            ],
            [
                [$date, 1, 2, 5],
                [$date + 86400 * 1, 1, 3, 5],
                [$date + 86400 * 2, 1, 4, 5],
                [$date + 86400 * 3, 1, 5, 5],
                [$date + 86400 * 4, 1, 6, 5],
                [$date + 86400 * 5, 1, 7, 5],
                [$date + 86400 * 6, 1, 1, 6],
                [$date + 86400 * 7, 1, 8, 5],
                [$date + 86400 * 8, 1, 9, 5],
                [$date + 86400 * 9, 1, 10, 5],
                [$date + 86400 * 10, 1, 11, 5],
                [$date + 86400 * 11, 1, 12, 5],
                [$date + 86400 * 12, 1, 13, 5],
                [$date + 86400 * 13, 1, 1, 6],
                [$date + 86400 * 14, 1, 2, 3],
                [$date + 86400 * 15, 1, 2, 4],
                [$date + 86400 * 16, 1, 3, 3],
                [$date + 86400 * 17, 1, 3, 4],
                [$date + 86400 * 18, 1, 4, 3],
                [$date + 86400 * 19, 1, 4, 4],
                [$date + 86400 * 20, 1, 5, 3],
                [$date + 86400 * 21, 1, 5, 4],
                [$date + 86400 * 22, 1, 6, 3],
                [$date + 86400 * 23, 1, 6, 4],
                [$date + 86400 * 24, 1, 7, 3],
                [$date + 86400 * 25, 1, 7, 4],
                [$date + 86400 * 26, 1, 1, 6],
                [$date + 86400 * 27, 1, 8, 3],
                [$date + 86400 * 28, 1, 8, 4],
                [$date + 86400 * 29, 1, 9, 3],
                [$date + 86400 * 30, 1, 9, 4],
                [$date + 86400 * 31, 1, 10, 3],
                [$date + 86400 * 32, 1, 10, 4],
                [$date + 86400 * 33, 1, 11, 3],
                [$date + 86400 * 34, 1, 11, 4],
                [$date + 86400 * 35, 1, 12, 3],
                [$date + 86400 * 36, 1, 12, 4],
                [$date + 86400 * 37, 1, 13, 3],
                [$date + 86400 * 38, 1, 13, 4],
                [$date + 86400 * 39, 1, 1, 6],
                [$date + 86400 * 40, 1, 14, 3],
                [$date + 86400 * 41, 1, 14, 4],
                [$date + 86400 * 42, 1, 15, 3],
                [$date + 86400 * 43, 1, 15, 4],
                [$date + 86400 * 44, 1, 16, 3],
                [$date + 86400 * 45, 1, 16, 4],
                [$date + 86400 * 46, 1, 17, 3],
                [$date + 86400 * 47, 1, 17, 4],
                [$date + 86400 * 48, 1, 18, 3],
                [$date + 86400 * 49, 1, 18, 4],
                [$date + 86400 * 50, 1, 19, 3],
                [$date + 86400 * 51, 1, 19, 4],
                [$date + 86400 * 52, 1, 1, 6],
                [$date + 86400 * 53, 1, 20, 3],
                [$date + 86400 * 54, 1, 20, 4],
                [$date + 86400 * 55, 1, 21, 3],
                [$date + 86400 * 56, 1, 21, 4],
                [$date + 86400 * 57, 1, 22, 3],
                [$date + 86400 * 58, 1, 22, 4],
                [$date + 86400 * 59, 1, 23, 3],
                [$date + 86400 * 60, 1, 23, 4],
                [$date + 86400 * 61, 1, 24, 3],
                [$date + 86400 * 62, 1, 24, 4],
                [$date + 86400 * 63, 1, 25, 3],
                [$date + 86400 * 64, 1, 25, 4],
                [$date + 86400 * 65, 1, 1, 6],
                [$date + 86400 * 66, 1, 26, 3],
                [$date + 86400 * 67, 1, 26, 4],
                [$date + 86400 * 68, 1, 27, 3],
                [$date + 86400 * 69, 1, 27, 4],
                [$date + 86400 * 70, 1, 28, 3],
                [$date + 86400 * 71, 1, 28, 4],
                [$date + 86400 * 72, 1, 29, 3],
                [$date + 86400 * 73, 1, 29, 4],
                [$date + 86400 * 74, 1, 30, 3],
                [$date + 86400 * 75, 1, 30, 4],
                [$date + 86400 * 76, 1, 31, 3],
                [$date + 86400 * 77, 1, 31, 4],
                [$date + 86400 * 78, 1, 1, 6],
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
