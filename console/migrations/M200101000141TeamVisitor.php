<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000141TeamVisitor
 * @package console\migrations
 */
class M200101000141TeamVisitor extends Migration
{
    private const TABLE = '{{%team_visitor}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'team_id' => $this->integer(11)->notNull(),
                'visitor' => $this->integer(3)->defaultValue(0),
            ]
        );

        $this->addForeignKey('team_visitor_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

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
