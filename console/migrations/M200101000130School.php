<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000130School
 * @package console\migrations
 */
class M200101000130School extends Migration
{
    private const TABLE = '{{%school}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'day' => $this->integer(2)->defaultValue(0),
                'is_with_special' => $this->boolean()->defaultValue(false),
                'is_with_special_request' => $this->boolean()->defaultValue(false),
                'is_with_style' => $this->boolean()->defaultValue(false),
                'is_with_style_request' => $this->boolean()->defaultValue(false),
                'position_id' => $this->integer(2)->notNull(),
                'ready' => $this->integer(11),
                'season_id' => $this->integer(3)->notNull(),
                'special_id' => $this->integer(2),
                'style_id' => $this->integer(1),
                'team_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('school_position_id', self::TABLE, 'position_id', '{{%position}}', 'id');
        $this->addForeignKey('school_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('school_special_id', self::TABLE, 'special_id', '{{%special}}', 'id');
        $this->addForeignKey('school_style_id', self::TABLE, 'style_id', '{{%style}}', 'id');
        $this->addForeignKey('school_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

        $this->createIndex('ready', self::TABLE, 'ready');

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
