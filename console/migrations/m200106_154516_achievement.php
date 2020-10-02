<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200106_154516_achievement
 * @package console\migrations
 */
class m200106_154516_achievement extends Migration
{
    private const TABLE = '{{%achievement}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(11),
            'country_id' => $this->integer(3),
            'division_id' => $this->integer(5),
            'national_id' => $this->integer(5),
            'place' => $this->integer(2),
            'season_id' => $this->integer(3)->notNull(),
            'stage_id' => $this->integer(2),
            'team_id' => $this->integer(5),
            'tournament_type_id' => $this->integer(1)->notNull(),
            'user_id' => $this->integer(11),
        ]);

        $this->addForeignKey('achievement_country_id', self::TABLE, 'country_id', '{{%country}}', 'id');
        $this->addForeignKey('achievement_division_id', self::TABLE, 'division_id', '{{%division}}', 'id');
        $this->addForeignKey('achievement_national_id', self::TABLE, 'national_id', '{{%national}}', 'id');
        $this->addForeignKey('achievement_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('achievement_stage_id', self::TABLE, 'stage_id', '{{%stage}}', 'id');
        $this->addForeignKey('achievement_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');
        $this->addForeignKey(
            'achievement_tournament_type_id',
            self::TABLE,
            'tournament_type_id',
            '{{%tournament_type}}',
            'id'
        );
        $this->addForeignKey('achievement_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
