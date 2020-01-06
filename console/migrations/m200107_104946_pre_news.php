<?php

use yii\db\Migration;

/**
 * Class m200107_104946_pre_news
 */
class m200107_104946_pre_news extends Migration
{
    const TABLE = '{{%pre_news}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'pre_news_id' => $this->primaryKey(1),
            'pre_news_new' => $this->text(),
            'pre_news_error' => $this->text(),
        ]);

        $this->insert(self::TABLE, [
            'pre_news_id' => null
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
