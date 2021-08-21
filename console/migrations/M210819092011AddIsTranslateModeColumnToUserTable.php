<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M210819092011AddIsTranslateModeColumnToUserTable
 * @package console\migrations
 */
class M210819092011AddIsTranslateModeColumnToUserTable extends Migration
{
    private const TABLE = '{{%user}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->addColumn(
            self::TABLE,
            'is_translation_mode',
            $this->boolean()->defaultValue(false)->after('is_referrer_done')
        );

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown(): bool
    {
        $this->dropColumn(self::TABLE, 'is_translation_mode');

        return true;
    }
}
