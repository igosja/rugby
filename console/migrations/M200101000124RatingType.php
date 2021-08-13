<?php

// TODO refactor

namespace console\migrations;

use Yii;
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
                ['power_vs_place', Yii::t('console', 'migrations.rating-type.vs'), 0, 1],
                ['age_place', Yii::t('console', 'migrations.rating-type.age'), 1, 1],
                ['stadium_place', Yii::t('console', 'migrations.rating-type.stadium'), 2, 1],
                ['visitor_place', Yii::t('console', 'migrations.rating-type.visitor'), 3, 1],
                ['base_place', Yii::t('console', 'migrations.rating-type.base'), 4, 1],
                ['price_base_place', Yii::t('console', 'migrations.rating-type.price.base'), 5, 1],
                ['price_stadium_place', Yii::t('console', 'migrations.rating-type.price.stadium'), 6, 1],
                ['player_place', Yii::t('console', 'migrations.rating-type.player'), 7, 1],
                ['price_total_place', Yii::t('console', 'migrations.rating-type.price.total'), 8, 1],
                ['rating_place', Yii::t('console', 'migrations.rating-type.rating'), 9, 2],
                ['stadium_place', Yii::t('console', 'migrations.rating-type.stadium'), 10, 3],
                ['auto_place', Yii::t('console', 'migrations.rating-type.auto'), 11, 3],
                ['league_place', Yii::t('console', 'migrations.rating-type.league'), 12, 3],
                ['salary_place', Yii::t('console', 'migrations.rating-type.salary'), 13, 1],
                ['finance_place', Yii::t('console', 'migrations.rating-type.finance'), 14, 1],
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
