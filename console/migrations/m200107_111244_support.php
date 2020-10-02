<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_111244_support
 * @package console\migrations
 */
class m200107_111244_support extends Migration
{
    private const TABLE = '{{%support}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'support_id' => $this->primaryKey(11),
            'support_admin_id' => $this->integer(11)->defaultValue(0),
            'support_country_id' => $this->integer(3)->defaultValue(0),
            'support_date' => $this->integer(11)->defaultValue(0),
            'support_inside' => $this->integer(1)->defaultValue(0),
            'support_president_id' => $this->integer(11)->defaultValue(1),
            'support_question' => $this->integer(1)->defaultValue(1),
            'support_read' => $this->integer(11)->defaultValue(0),
            'support_text' => $this->text(),
            'support_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('support_read', self::TABLE, 'support_read');
        $this->createIndex('support_user_id', self::TABLE, 'support_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
