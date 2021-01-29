<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200101000129Rule
 * @package console\migrations
 */
class M200101000129Rule extends Migration
{
    private const TABLE = '{{%rule}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'date' => $this->integer(11)->notNull(),
                'order' => $this->integer(2)->notNull()->unique(),
                'text' => $this->text()->notNull(),
                'title' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['date', 'order', 'text', 'title'],
            [
                [time(), 1, Yii::t('rule1', 'text'), Yii::t('rule1', 'title')],
                [time(), 2, Yii::t('rule2', 'text'), Yii::t('rule2', 'title')],
                [time(), 3, Yii::t('rule3', 'text'), Yii::t('rule3', 'title')],
                [time(), 4, Yii::t('rule4', 'text'), Yii::t('rule4', 'title')],
                [time(), 5, Yii::t('rule5', 'text'), Yii::t('rule5', 'title')],
                [time(), 6, Yii::t('rule6', 'text'), Yii::t('rule6', 'title')],
                [time(), 7, Yii::t('rule7', 'text'), Yii::t('rule7', 'title')],
                [time(), 8, Yii::t('rule8', 'text'), Yii::t('rule8', 'title')],
                [time(), 9, Yii::t('rule9', 'text'), Yii::t('rule9', 'title')],
                [time(), 10, Yii::t('rule10', 'text'), Yii::t('rule10', 'title')],
                [time(), 11, Yii::t('rule11', 'text'), Yii::t('rule11', 'title')],
                [time(), 12, Yii::t('rule12', 'text'), Yii::t('rule12', 'title')],
                [time(), 13, Yii::t('rule13', 'text'), Yii::t('rule13', 'title')],
                [time(), 14, Yii::t('rule14', 'text'), Yii::t('rule14', 'title')],
                [time(), 15, Yii::t('rule15', 'text'), Yii::t('rule15', 'title')],
                [time(), 16, Yii::t('rule16', 'text'), Yii::t('rule16', 'title')],
                [time(), 17, Yii::t('rule17', 'text'), Yii::t('rule17', 'title')],
                [time(), 18, Yii::t('rule18', 'text'), Yii::t('rule18', 'title')],
                [time(), 19, Yii::t('rule19', 'text'), Yii::t('rule19', 'title')],
                [time(), 20, Yii::t('rule20', 'text'), Yii::t('rule20', 'title')],
                [time(), 21, Yii::t('rule21', 'text'), Yii::t('rule21', 'title')],
                [time(), 22, Yii::t('rule22', 'text'), Yii::t('rule22', 'title')],
                [time(), 23, Yii::t('rule23', 'text'), Yii::t('rule23', 'title')],
                [time(), 24, Yii::t('rule24', 'text'), Yii::t('rule24', 'title')],
                [time(), 25, Yii::t('rule25', 'text'), Yii::t('rule25', 'title')],
                [time(), 26, Yii::t('rule26', 'text'), Yii::t('rule26', 'title')],
                [time(), 27, Yii::t('rule27', 'text'), Yii::t('rule27', 'title')],
                [time(), 30, Yii::t('rule30', 'text'), Yii::t('rule30', 'title')],
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
