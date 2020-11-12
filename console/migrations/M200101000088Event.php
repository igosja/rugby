<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000088Event
 * @package console\migrations
 */
class M200101000088Event extends Migration
{
    private const TABLE = '{{%event}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'event_text_id' => $this->integer(1)->notNull(),
                'game_id' => $this->integer(11)->notNull(),
                'guest_points' => $this->integer(3)->defaultValue(0),
                'home_points' => $this->integer(3)->defaultValue(0),
                'minute' => $this->integer(2)->notNull(),
                'national_id' => $this->integer(5)->notNull(),
                'player_id' => $this->integer(11)->notNull(),
                'team_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey(
            'event_event_text_id',
            self::TABLE,
            'event_text_id',
            '{{%event_text}}',
            'id'
        );
        $this->addForeignKey('event_national_id', self::TABLE, 'national_id', '{{%national}}', 'id');
        $this->addForeignKey('event_player_id', self::TABLE, 'player_id', '{{%player}}', 'id');
        $this->addForeignKey('event_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

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
