<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000112ParticipantLeague
 * @package console\migrations
 */
class M200101000112ParticipantLeague extends Migration
{
    private const TABLE = '{{%participant_league}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'season_id' => $this->integer(3)->notNull(),
                'stage_1' => $this->integer(1),
                'stage_2' => $this->integer(1),
                'stage_4' => $this->integer(1),
                'stage_8' => $this->integer(1),
                'stage_id' => $this->integer(2),
                'stage_in' => $this->integer(2)->notNull(),
                'team_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('participant_league_stage_id', self::TABLE, 'stage_id', '{{%stage}}', 'id');
        $this->addForeignKey('participant_league_stage_in', self::TABLE, 'stage_in', '{{%stage}}', 'id');
        $this->addForeignKey('participant_league_team_id', self::TABLE, 'team_id', '{{%stage}}', 'id');

        $this->createIndex('team_season', self::TABLE, ['team_id', 'season_id'], true);

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
