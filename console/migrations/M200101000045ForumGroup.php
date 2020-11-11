<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000045ForumGroup
 * @package console\migrations
 */
class M200101000045ForumGroup extends Migration
{
    private const TABLE = '{{%forum_group}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'federation_id' => $this->integer(3),
                'description' => $this->text()->notNull(),
                'forum_chapter_id' => $this->integer(1)->notNull(),
                'name' => $this->string(255)->notNull()->unique(),
                'order' => $this->integer(3)->notNull(),
            ]
        );

        $this->addForeignKey('forum_group_federation_id', self::TABLE, 'federation_id', '{{%federation}}', 'id');
        $this->addForeignKey(
            'forum_group_forum_chapter_id',
            self::TABLE,
            'forum_chapter_id',
            '{{%forum_chapter}}',
            'id'
        );

        $this->createIndex('forum_group_order', self::TABLE, ['forum_chapter_id', 'order'], true);

        $this->batchInsert(
            self::TABLE,
            [
                'federation_id',
                'name',
                'description',
                'forum_chapter_id',
                'order'
            ],
            [
                [
                    null,
                    'О Лиге',
                    'вопросы и комментарии о лиге глобального характера, творчество, что нравится/не нравится',
                    1,
                    1
                ],
                [
                    null,
                    'Скорая помощь',
                    'сверxсрочные проблемы, не терпящие отлагательств, а то будет поздно - остальное в баги или вопросы новичков',
                    1,
                    2
                ],
                [
                    null,
                    'Вопросы новичков',
                    'для обсуждения с опытными менеджерами возникающих у новичков самых простых вопросов',
                    1,
                    3
                ],
                [
                    null,
                    'Регистрация и команды',
                    '[пере]регистрация, выставление/раздача свободных команд, переходы из клуба в клуб, верните команду и т.д.',
                    1,
                    4
                ],
                [
                    null,
                    'Идеи и предложения',
                    'высказывание интересных мыслей и конкретных идей по поводу развития лиги',
                    1,
                    5
                ],
                [null, 'Правила', 'обсуждение, трактовка, серьезные вопросы по поводу действующих правил', 1, 6],
                [null, 'Баги', 'неспрочные ошибки, неправильная работа страниц', 1, 7],
                [null, 'Трансферы', 'если хотите договориться о купле/продаже игроков', 2, 1],
                [null, 'Товарищеские Матчи', 'поиск соперников на товы', 2, 2],
                [null, 'Аренда', 'если хотите договориться об аренде игроков', 2, 3],
                [null, 'Встречи', 'встречи менеджеров в реале - на стадион сходить, пивка попить', 3, 1],
                [null, 'Реальное регби', 'обсуждение реальных событий, регбийный оффтопик', 3, 2],
                [null, 'Оффтопик', 'обсуждение всего чего угодно за пределами Лиги', 3, 3],
                [8, 'Argentina', 'национальный форум', 4, 1],
                [10, 'Australia', 'национальный форум', 4, 2],
                [55, 'England', 'национальный форум', 4, 3],
                [62, 'France', 'национальный форум', 4, 4],
                [82, 'Ireland', 'национальный форум', 4, 5],
                [84, 'Italy', 'национальный форум', 4, 6],
                [125, 'New Zealand', 'национальный форум', 4, 7],
                [155, 'Scotland', 'национальный форум', 4, 8],
                [165, 'South Africa', 'национальный форум', 4, 9],
                [195, 'Wales', 'национальный форум', 4, 10],
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
