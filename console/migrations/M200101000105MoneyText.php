<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000105MoneyText
 * @package console\migrations
 */
class M200101000105MoneyText extends Migration
{
    private const TABLE = '{{%money_text}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'text' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['text'],
            [
                ['Пополнение счёта'],
                ['Бонус партнёрской программе'],
                ['Покупка балла силы'],
                ['Пополнение счёта своей команды'],
                ['Покупка совмещения'],
                ['Покупка спецвозможности'],
                ['Продление VIP-клуба'],
                ['Перевод средств от другого менеджера'],
                ['Перевод средств другому менеджеру'],
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
