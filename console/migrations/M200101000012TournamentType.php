<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200101000012TournamentType
 * @package console\migrations
 */
class M200101000012TournamentType extends Migration
{
    private const TABLE = '{{%tournament_type}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'day_type_id' => $this->integer(1)->notNull(),
                'name' => $this->string(20)->notNull()->unique(),
                'visitor' => $this->integer(3)->notNull(),
            ]
        );

        $this->addForeignKey('tournament_type_day_type_id', self::TABLE, 'day_type_id', '{{%day_type}}', 'id');

        $this->batchInsert(
            self::TABLE,
            ['day_type_id', 'name', 'visitor'],
            [
                [3, Yii::t('console', 'migrations.tournament-type.world-cup'), 200],
                [3, Yii::t('console', 'migrations.tournament-type.league'), 150],
                [2, Yii::t('console', 'migrations.tournament-type.championship'), 100],
                [2, Yii::t('console', 'migrations.tournament-type.conference'), 90],
                [2, Yii::t('console', 'migrations.tournament-type.off-season'), 90],
                [1, Yii::t('console', 'migrations.tournament-type.friendly'), 80],
            ]
        );

        return true;
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
