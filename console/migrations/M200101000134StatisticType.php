<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000134StatisticType
 * @package console\migrations
 */
class M200101000134StatisticType extends Migration
{
    private const TABLE = '{{%statistic_type}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'name' => $this->string(255)->notNull(),
                'order' => $this->integer(2)->notNull(),
                'select_field' => $this->string(255)->notNull(),
                'statistic_chapter_id' => $this->integer(1)->notNull(),
            ]
        );

        $this->addForeignKey(
            'statistic_type_statistic_chapter_id',
            self::TABLE,
            'statistic_chapter_id',
            '{{%statistic_chapter}}',
            'id'
        );

        $this->batchInsert(
            self::TABLE,
            [
                'name',
                'select_field',
                'order',
                'statistic_chapter_id',
            ],
            [
                ['Игры без пропущенных шайб', 'statistic_team_game_no_pass', SORT_DESC, 1],
                ['Игры без заброшенных шайб', 'statistic_team_game_no_score', SORT_ASC, 1],
                ['Поражения', 'statistic_team_loose', SORT_ASC, 1],
                ['Поражения по буллитам', 'statistic_team_loose_shootout', SORT_ASC, 1],
                ['Поражения в дополнительное время', 'statistic_team_loose_over', SORT_ASC, 1],
                ['Пропущенные шайбы', 'statistic_team_pass', SORT_ASC, 1],
                ['Заброшенные шайбы', 'statistic_team_score', SORT_DESC, 1],
                ['Штрафные минуты', 'statistic_team_penalty_minute', SORT_ASC, 1],
                ['Штрафные минуты соперника', 'statistic_team_penalty_minute_opponent', SORT_DESC, 1],
                ['Победы', 'statistic_team_win', SORT_DESC, 1],
                ['Победы по буллитам', 'statistic_team_win_shootout', SORT_DESC, 1],
                ['Победы в дополнительное время', 'statistic_team_win_over', SORT_DESC, 1],
                ['Процент побед', 'statistic_team_win_percent', SORT_DESC, 1],
                ['Результативные передачи', 'statistic_player_assist', SORT_DESC, 2],
                ['Результативные передачи в большинстве', 'statistic_player_assist_power', SORT_DESC, 2],
                ['Результативные передачи в меньшинстве', 'statistic_player_assist_short', SORT_DESC, 2],
                ['Победные буллиты', 'statistic_player_shootout_win', SORT_DESC, 2],
                ['Вбрасывания', 'statistic_player_face_off', SORT_DESC, 2],
                ['Процент выигранных вбрасываний', 'statistic_player_face_off_percent', SORT_DESC, 2],
                ['Выигранные вбрасывания', 'statistic_player_face_off_win', SORT_DESC, 2],
                ['Игры', 'statistic_player_game', SORT_DESC, 2],
                ['Поражения', 'statistic_player_loose', SORT_ASC, 2],
                ['Пропущенные шайбы', 'statistic_player_pass', SORT_ASC, 2],
                ['Пропущенные шайбы за игру', 'statistic_player_pass_per_game', SORT_ASC, 2],
                ['Штрафные минуты', 'statistic_player_penalty', SORT_ASC, 2],
                ['Плюс/минус', 'statistic_player_plus_minus', SORT_DESC, 2],
                ['Очки (шайбы+голевые передачи)', 'statistic_player_point', SORT_DESC, 2],
                ['Отраженные броски', 'statistic_player_save', SORT_DESC, 2],
                ['Процент отраженных бросков', 'statistic_player_save_percent', SORT_DESC, 2],
                ['Заброшенные шайбы', 'statistic_player_score', SORT_DESC, 2],
                ['Заброшенные шайбы, которые сравняли счет в матче', 'statistic_player_score_draw', SORT_DESC, 2],
                ['Заброшенные шайбы в большинстве', 'statistic_player_score_power', SORT_DESC, 2],
                ['Заброшенные шайбы в меньшинстве', 'statistic_player_score_short', SORT_DESC, 2],
                [
                    'Процент заброшенных шайб к количеству бросков по воротам',
                    'statistic_player_score_shot_percent',
                    SORT_DESC,
                    2
                ],
                ['Победные шайбы', 'statistic_player_score_win', SORT_DESC, 2],
                ['Броски по воротам', 'statistic_player_shot', SORT_DESC, 2],
                ['Броски (вратари)', 'statistic_player_shot_gk', SORT_DESC, 2],
                ['Броски за игру', 'statistic_player_shot_per_game', SORT_DESC, 2],
                ['Игры на ноль', 'statistic_player_shutout', SORT_DESC, 2],
                ['Победы', 'statistic_player_win', SORT_DESC, 2],
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
