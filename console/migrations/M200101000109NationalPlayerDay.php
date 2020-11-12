<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000109NationalPlayerDay
 * @package console\migrations
 */
class M200101000109NationalPlayerDay extends Migration
{
    private const TABLE = '{{%national_player_day}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'day' => $this->integer(3)->defaultValue(0),
                'national_id' => $this->integer(3)->notNull(),
                'player_id' => $this->integer(11)->notNull(),
                'season_id' => $this->integer(3)->notNull(),
                'team_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('national_player_day_national_id', self::TABLE, 'national_id', '{{%national}}', 'id');
        $this->addForeignKey('national_player_day_player_id', self::TABLE, 'player_id', '{{%player}}', 'id');
        $this->addForeignKey('national_player_day_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('national_player_day_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

        $this->createIndex(
            'player_national_season',
            self::TABLE,
            ['player_id', 'national_id', 'team_id', 'season_id'],
            true
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
