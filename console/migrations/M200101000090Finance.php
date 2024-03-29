<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000090Finance
 * @package console\migrations
 */
class M200101000090Finance extends Migration
{
    private const TABLE = '{{%finance}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'building_id' => $this->integer(1),
                'capacity' => $this->integer(5),
                'comment' => $this->text(),
                'federation_id' => $this->integer(3),
                'date' => $this->integer(11)->notNull(),
                'finance_text_id' => $this->integer(2)->notNull(),
                'level' => $this->integer(2),
                'loan_id' => $this->integer(11),
                'national_id' => $this->integer(3),
                'player_id' => $this->integer(11),
                'season_id' => $this->integer(3)->notNull(),
                'team_id' => $this->integer(11),
                'transfer_id' => $this->integer(11),
                'user_id' => $this->integer(11),
                'value' => $this->integer(11)->notNull(),
                'value_after' => $this->integer(11)->notNull(),
                'value_before' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('finance_building_id', self::TABLE, 'building_id', '{{%building}}', 'id');
        $this->addForeignKey('finance_federation_id', self::TABLE, 'federation_id', '{{%federation}}', 'id');
        $this->addForeignKey('finance_finance_text_id', self::TABLE, 'finance_text_id', '{{%finance_text}}', 'id');
        $this->addForeignKey('finance_loan_id', self::TABLE, 'loan_id', '{{%loan}}', 'id');
        $this->addForeignKey('finance_national_id', self::TABLE, 'national_id', '{{%national}}', 'id');
        $this->addForeignKey('finance_player_id', self::TABLE, 'player_id', '{{%player}}', 'id');
        $this->addForeignKey('finance_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('finance_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');
        $this->addForeignKey('finance_transfer_id', self::TABLE, 'transfer_id', '{{%transfer}}', 'id');
        $this->addForeignKey('finance_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->batchInsert(
            self::TABLE,
            [
                'date',
                'finance_text_id',
                'season_id',
                'team_id',
                'value',
                'value_after',
                'value_before',
            ],
            [
                [time(), 24, 1, 1, 10000000, 10000000, 0],
                [time(), 24, 1, 2, 10000000, 10000000, 0],
                [time(), 24, 1, 3, 10000000, 10000000, 0],
                [time(), 24, 1, 4, 10000000, 10000000, 0],
                [time(), 24, 1, 5, 10000000, 10000000, 0],
                [time(), 24, 1, 6, 10000000, 10000000, 0],
                [time(), 24, 1, 7, 10000000, 10000000, 0],
                [time(), 24, 1, 8, 10000000, 10000000, 0],
                [time(), 24, 1, 9, 10000000, 10000000, 0],
                [time(), 24, 1, 10, 10000000, 10000000, 0],
                [time(), 24, 1, 11, 10000000, 10000000, 0],
                [time(), 24, 1, 12, 10000000, 10000000, 0],
                [time(), 24, 1, 13, 10000000, 10000000, 0],
                [time(), 24, 1, 14, 10000000, 10000000, 0],
                [time(), 24, 1, 15, 10000000, 10000000, 0],
                [time(), 24, 1, 16, 10000000, 10000000, 0],
                [time(), 24, 1, 17, 10000000, 10000000, 0],
                [time(), 24, 1, 18, 10000000, 10000000, 0],
                [time(), 24, 1, 19, 10000000, 10000000, 0],
                [time(), 24, 1, 20, 10000000, 10000000, 0],
                [time(), 24, 1, 21, 10000000, 10000000, 0],
                [time(), 24, 1, 22, 10000000, 10000000, 0],
                [time(), 24, 1, 23, 10000000, 10000000, 0],
                [time(), 24, 1, 24, 10000000, 10000000, 0],
                [time(), 24, 1, 25, 10000000, 10000000, 0],
                [time(), 24, 1, 26, 10000000, 10000000, 0],
                [time(), 24, 1, 27, 10000000, 10000000, 0],
                [time(), 24, 1, 28, 10000000, 10000000, 0],
                [time(), 24, 1, 29, 10000000, 10000000, 0],
                [time(), 24, 1, 30, 10000000, 10000000, 0],
                [time(), 24, 1, 31, 10000000, 10000000, 0],
                [time(), 24, 1, 32, 10000000, 10000000, 0],
                [time(), 24, 1, 33, 10000000, 10000000, 0],
                [time(), 24, 1, 34, 10000000, 10000000, 0],
                [time(), 24, 1, 35, 10000000, 10000000, 0],
                [time(), 24, 1, 36, 10000000, 10000000, 0],
                [time(), 24, 1, 37, 10000000, 10000000, 0],
                [time(), 24, 1, 38, 10000000, 10000000, 0],
                [time(), 24, 1, 39, 10000000, 10000000, 0],
                [time(), 24, 1, 40, 10000000, 10000000, 0],
                [time(), 24, 1, 41, 10000000, 10000000, 0],
                [time(), 24, 1, 42, 10000000, 10000000, 0],
                [time(), 24, 1, 43, 10000000, 10000000, 0],
                [time(), 24, 1, 44, 10000000, 10000000, 0],
                [time(), 24, 1, 45, 10000000, 10000000, 0],
                [time(), 24, 1, 46, 10000000, 10000000, 0],
                [time(), 24, 1, 47, 10000000, 10000000, 0],
                [time(), 24, 1, 48, 10000000, 10000000, 0],
                [time(), 24, 1, 49, 10000000, 10000000, 0],
                [time(), 24, 1, 50, 10000000, 10000000, 0],
                [time(), 24, 1, 51, 10000000, 10000000, 0],
                [time(), 24, 1, 52, 10000000, 10000000, 0],
                [time(), 24, 1, 53, 10000000, 10000000, 0],
                [time(), 24, 1, 54, 10000000, 10000000, 0],
                [time(), 24, 1, 55, 10000000, 10000000, 0],
                [time(), 24, 1, 56, 10000000, 10000000, 0],
                [time(), 24, 1, 57, 10000000, 10000000, 0],
                [time(), 24, 1, 58, 10000000, 10000000, 0],
                [time(), 24, 1, 59, 10000000, 10000000, 0],
                [time(), 24, 1, 60, 10000000, 10000000, 0],
                [time(), 24, 1, 61, 10000000, 10000000, 0],
                [time(), 24, 1, 62, 10000000, 10000000, 0],
                [time(), 24, 1, 63, 10000000, 10000000, 0],
                [time(), 24, 1, 64, 10000000, 10000000, 0],
                [time(), 24, 1, 65, 10000000, 10000000, 0],
                [time(), 24, 1, 66, 10000000, 10000000, 0],
                [time(), 24, 1, 67, 10000000, 10000000, 0],
                [time(), 24, 1, 68, 10000000, 10000000, 0],
                [time(), 24, 1, 69, 10000000, 10000000, 0],
                [time(), 24, 1, 70, 10000000, 10000000, 0],
                [time(), 24, 1, 71, 10000000, 10000000, 0],
                [time(), 24, 1, 72, 10000000, 10000000, 0],
                [time(), 24, 1, 73, 10000000, 10000000, 0],
                [time(), 24, 1, 74, 10000000, 10000000, 0],
                [time(), 24, 1, 75, 10000000, 10000000, 0],
                [time(), 24, 1, 76, 10000000, 10000000, 0],
                [time(), 24, 1, 77, 10000000, 10000000, 0],
                [time(), 24, 1, 78, 10000000, 10000000, 0],
                [time(), 24, 1, 79, 10000000, 10000000, 0],
                [time(), 24, 1, 80, 10000000, 10000000, 0],
                [time(), 24, 1, 81, 10000000, 10000000, 0],
                [time(), 24, 1, 82, 10000000, 10000000, 0],
                [time(), 24, 1, 83, 10000000, 10000000, 0],
                [time(), 24, 1, 84, 10000000, 10000000, 0],
                [time(), 24, 1, 85, 10000000, 10000000, 0],
                [time(), 24, 1, 86, 10000000, 10000000, 0],
                [time(), 24, 1, 87, 10000000, 10000000, 0],
                [time(), 24, 1, 88, 10000000, 10000000, 0],
                [time(), 24, 1, 89, 10000000, 10000000, 0],
                [time(), 24, 1, 90, 10000000, 10000000, 0],
                [time(), 24, 1, 91, 10000000, 10000000, 0],
                [time(), 24, 1, 92, 10000000, 10000000, 0],
                [time(), 24, 1, 93, 10000000, 10000000, 0],
                [time(), 24, 1, 94, 10000000, 10000000, 0],
                [time(), 24, 1, 95, 10000000, 10000000, 0],
                [time(), 24, 1, 96, 10000000, 10000000, 0],
                [time(), 24, 1, 97, 10000000, 10000000, 0],
                [time(), 24, 1, 98, 10000000, 10000000, 0],
                [time(), 24, 1, 99, 10000000, 10000000, 0],
                [time(), 24, 1, 100, 10000000, 10000000, 0],
                [time(), 24, 1, 101, 10000000, 10000000, 0],
                [time(), 24, 1, 102, 10000000, 10000000, 0],
                [time(), 24, 1, 103, 10000000, 10000000, 0],
                [time(), 24, 1, 104, 10000000, 10000000, 0],
                [time(), 24, 1, 105, 10000000, 10000000, 0],
                [time(), 24, 1, 106, 10000000, 10000000, 0],
                [time(), 24, 1, 107, 10000000, 10000000, 0],
                [time(), 24, 1, 108, 10000000, 10000000, 0],
                [time(), 24, 1, 109, 10000000, 10000000, 0],
                [time(), 24, 1, 110, 10000000, 10000000, 0],
                [time(), 24, 1, 111, 10000000, 10000000, 0],
                [time(), 24, 1, 112, 10000000, 10000000, 0],
                [time(), 24, 1, 113, 10000000, 10000000, 0],
                [time(), 24, 1, 114, 10000000, 10000000, 0],
                [time(), 24, 1, 115, 10000000, 10000000, 0],
                [time(), 24, 1, 116, 10000000, 10000000, 0],
                [time(), 24, 1, 117, 10000000, 10000000, 0],
                [time(), 24, 1, 118, 10000000, 10000000, 0],
                [time(), 24, 1, 119, 10000000, 10000000, 0],
                [time(), 24, 1, 120, 10000000, 10000000, 0],
                [time(), 24, 1, 121, 10000000, 10000000, 0],
                [time(), 24, 1, 122, 10000000, 10000000, 0],
                [time(), 24, 1, 123, 10000000, 10000000, 0],
                [time(), 24, 1, 124, 10000000, 10000000, 0],
                [time(), 24, 1, 125, 10000000, 10000000, 0],
                [time(), 24, 1, 126, 10000000, 10000000, 0],
                [time(), 24, 1, 127, 10000000, 10000000, 0],
                [time(), 24, 1, 128, 10000000, 10000000, 0],
                [time(), 24, 1, 129, 10000000, 10000000, 0],
                [time(), 24, 1, 130, 10000000, 10000000, 0],
                [time(), 24, 1, 131, 10000000, 10000000, 0],
                [time(), 24, 1, 132, 10000000, 10000000, 0],
                [time(), 24, 1, 133, 10000000, 10000000, 0],
                [time(), 24, 1, 134, 10000000, 10000000, 0],
                [time(), 24, 1, 135, 10000000, 10000000, 0],
                [time(), 24, 1, 136, 10000000, 10000000, 0],
                [time(), 24, 1, 137, 10000000, 10000000, 0],
                [time(), 24, 1, 138, 10000000, 10000000, 0],
                [time(), 24, 1, 139, 10000000, 10000000, 0],
                [time(), 24, 1, 140, 10000000, 10000000, 0],
                [time(), 24, 1, 141, 10000000, 10000000, 0],
                [time(), 24, 1, 142, 10000000, 10000000, 0],
                [time(), 24, 1, 143, 10000000, 10000000, 0],
                [time(), 24, 1, 144, 10000000, 10000000, 0],
                [time(), 24, 1, 145, 10000000, 10000000, 0],
                [time(), 24, 1, 146, 10000000, 10000000, 0],
                [time(), 24, 1, 147, 10000000, 10000000, 0],
                [time(), 24, 1, 148, 10000000, 10000000, 0],
                [time(), 24, 1, 149, 10000000, 10000000, 0],
                [time(), 24, 1, 150, 10000000, 10000000, 0],
                [time(), 24, 1, 151, 10000000, 10000000, 0],
                [time(), 24, 1, 152, 10000000, 10000000, 0],
                [time(), 24, 1, 153, 10000000, 10000000, 0],
                [time(), 24, 1, 154, 10000000, 10000000, 0],
                [time(), 24, 1, 155, 10000000, 10000000, 0],
                [time(), 24, 1, 156, 10000000, 10000000, 0],
                [time(), 24, 1, 157, 10000000, 10000000, 0],
                [time(), 24, 1, 158, 10000000, 10000000, 0],
                [time(), 24, 1, 159, 10000000, 10000000, 0],
                [time(), 24, 1, 160, 10000000, 10000000, 0],
                [time(), 24, 1, 161, 10000000, 10000000, 0],
                [time(), 24, 1, 162, 10000000, 10000000, 0],
                [time(), 24, 1, 163, 10000000, 10000000, 0],
                [time(), 24, 1, 164, 10000000, 10000000, 0],
                [time(), 24, 1, 165, 10000000, 10000000, 0],
                [time(), 24, 1, 166, 10000000, 10000000, 0],
                [time(), 24, 1, 167, 10000000, 10000000, 0],
                [time(), 24, 1, 168, 10000000, 10000000, 0],
                [time(), 24, 1, 169, 10000000, 10000000, 0],
                [time(), 24, 1, 170, 10000000, 10000000, 0],
                [time(), 24, 1, 171, 10000000, 10000000, 0],
                [time(), 24, 1, 172, 10000000, 10000000, 0],
                [time(), 24, 1, 173, 10000000, 10000000, 0],
                [time(), 24, 1, 174, 10000000, 10000000, 0],
                [time(), 24, 1, 175, 10000000, 10000000, 0],
                [time(), 24, 1, 176, 10000000, 10000000, 0],
                [time(), 24, 1, 177, 10000000, 10000000, 0],
                [time(), 24, 1, 178, 10000000, 10000000, 0],
                [time(), 24, 1, 179, 10000000, 10000000, 0],
                [time(), 24, 1, 180, 10000000, 10000000, 0],
                [time(), 24, 1, 181, 10000000, 10000000, 0],
                [time(), 24, 1, 182, 10000000, 10000000, 0],
                [time(), 24, 1, 183, 10000000, 10000000, 0],
                [time(), 24, 1, 184, 10000000, 10000000, 0],
                [time(), 24, 1, 185, 10000000, 10000000, 0],
                [time(), 24, 1, 186, 10000000, 10000000, 0],
                [time(), 24, 1, 187, 10000000, 10000000, 0],
                [time(), 24, 1, 188, 10000000, 10000000, 0],
                [time(), 24, 1, 189, 10000000, 10000000, 0],
                [time(), 24, 1, 190, 10000000, 10000000, 0],
                [time(), 24, 1, 191, 10000000, 10000000, 0],
                [time(), 24, 1, 192, 10000000, 10000000, 0],
                [time(), 24, 1, 193, 10000000, 10000000, 0],
                [time(), 24, 1, 194, 10000000, 10000000, 0],
                [time(), 24, 1, 195, 10000000, 10000000, 0],
                [time(), 24, 1, 196, 10000000, 10000000, 0],
                [time(), 24, 1, 197, 10000000, 10000000, 0],
                [time(), 24, 1, 198, 10000000, 10000000, 0],
                [time(), 24, 1, 199, 10000000, 10000000, 0],
                [time(), 24, 1, 200, 10000000, 10000000, 0],
                [time(), 24, 1, 201, 10000000, 10000000, 0],
                [time(), 24, 1, 202, 10000000, 10000000, 0],
                [time(), 24, 1, 203, 10000000, 10000000, 0],
                [time(), 24, 1, 204, 10000000, 10000000, 0],
                [time(), 24, 1, 205, 10000000, 10000000, 0],
                [time(), 24, 1, 206, 10000000, 10000000, 0],
                [time(), 24, 1, 207, 10000000, 10000000, 0],
                [time(), 24, 1, 208, 10000000, 10000000, 0],
                [time(), 24, 1, 209, 10000000, 10000000, 0],
                [time(), 24, 1, 210, 10000000, 10000000, 0],
                [time(), 24, 1, 211, 10000000, 10000000, 0],
                [time(), 24, 1, 212, 10000000, 10000000, 0],
                [time(), 24, 1, 213, 10000000, 10000000, 0],
                [time(), 24, 1, 214, 10000000, 10000000, 0],
                [time(), 24, 1, 215, 10000000, 10000000, 0],
                [time(), 24, 1, 216, 10000000, 10000000, 0],
                [time(), 24, 1, 217, 10000000, 10000000, 0],
                [time(), 24, 1, 218, 10000000, 10000000, 0],
                [time(), 24, 1, 219, 10000000, 10000000, 0],
                [time(), 24, 1, 220, 10000000, 10000000, 0],
                [time(), 24, 1, 221, 10000000, 10000000, 0],
                [time(), 24, 1, 222, 10000000, 10000000, 0],
                [time(), 24, 1, 223, 10000000, 10000000, 0],
                [time(), 24, 1, 224, 10000000, 10000000, 0],
                [time(), 24, 1, 225, 10000000, 10000000, 0],
                [time(), 24, 1, 226, 10000000, 10000000, 0],
                [time(), 24, 1, 227, 10000000, 10000000, 0],
                [time(), 24, 1, 228, 10000000, 10000000, 0],
                [time(), 24, 1, 229, 10000000, 10000000, 0],
                [time(), 24, 1, 230, 10000000, 10000000, 0],
                [time(), 24, 1, 231, 10000000, 10000000, 0],
                [time(), 24, 1, 232, 10000000, 10000000, 0],
                [time(), 24, 1, 233, 10000000, 10000000, 0],
                [time(), 24, 1, 234, 10000000, 10000000, 0],
                [time(), 24, 1, 235, 10000000, 10000000, 0],
                [time(), 24, 1, 236, 10000000, 10000000, 0],
                [time(), 24, 1, 237, 10000000, 10000000, 0],
                [time(), 24, 1, 238, 10000000, 10000000, 0],
                [time(), 24, 1, 239, 10000000, 10000000, 0],
                [time(), 24, 1, 240, 10000000, 10000000, 0],
                [time(), 24, 1, 241, 10000000, 10000000, 0],
                [time(), 24, 1, 242, 10000000, 10000000, 0],
                [time(), 24, 1, 243, 10000000, 10000000, 0],
                [time(), 24, 1, 244, 10000000, 10000000, 0],
                [time(), 24, 1, 245, 10000000, 10000000, 0],
                [time(), 24, 1, 246, 10000000, 10000000, 0],
                [time(), 24, 1, 247, 10000000, 10000000, 0],
                [time(), 24, 1, 248, 10000000, 10000000, 0],
                [time(), 24, 1, 249, 10000000, 10000000, 0],
                [time(), 24, 1, 250, 10000000, 10000000, 0],
                [time(), 24, 1, 251, 10000000, 10000000, 0],
                [time(), 24, 1, 252, 10000000, 10000000, 0],
                [time(), 24, 1, 253, 10000000, 10000000, 0],
                [time(), 24, 1, 254, 10000000, 10000000, 0],
                [time(), 24, 1, 255, 10000000, 10000000, 0],
                [time(), 24, 1, 256, 10000000, 10000000, 0],
                [time(), 24, 1, 257, 10000000, 10000000, 0],
                [time(), 24, 1, 258, 10000000, 10000000, 0],
                [time(), 24, 1, 259, 10000000, 10000000, 0],
                [time(), 24, 1, 260, 10000000, 10000000, 0],
                [time(), 24, 1, 261, 10000000, 10000000, 0],
                [time(), 24, 1, 262, 10000000, 10000000, 0],
                [time(), 24, 1, 263, 10000000, 10000000, 0],
                [time(), 24, 1, 264, 10000000, 10000000, 0],
                [time(), 24, 1, 265, 10000000, 10000000, 0],
                [time(), 24, 1, 266, 10000000, 10000000, 0],
                [time(), 24, 1, 267, 10000000, 10000000, 0],
                [time(), 24, 1, 268, 10000000, 10000000, 0],
                [time(), 24, 1, 269, 10000000, 10000000, 0],
                [time(), 24, 1, 270, 10000000, 10000000, 0],
                [time(), 24, 1, 271, 10000000, 10000000, 0],
                [time(), 24, 1, 272, 10000000, 10000000, 0],
                [time(), 24, 1, 273, 10000000, 10000000, 0],
                [time(), 24, 1, 274, 10000000, 10000000, 0],
                [time(), 24, 1, 275, 10000000, 10000000, 0],
                [time(), 24, 1, 276, 10000000, 10000000, 0],
                [time(), 24, 1, 277, 10000000, 10000000, 0],
                [time(), 24, 1, 278, 10000000, 10000000, 0],
                [time(), 24, 1, 279, 10000000, 10000000, 0],
                [time(), 24, 1, 280, 10000000, 10000000, 0],
                [time(), 24, 1, 281, 10000000, 10000000, 0],
                [time(), 24, 1, 282, 10000000, 10000000, 0],
                [time(), 24, 1, 283, 10000000, 10000000, 0],
                [time(), 24, 1, 284, 10000000, 10000000, 0],
                [time(), 24, 1, 285, 10000000, 10000000, 0],
                [time(), 24, 1, 286, 10000000, 10000000, 0],
                [time(), 24, 1, 287, 10000000, 10000000, 0],
                [time(), 24, 1, 288, 10000000, 10000000, 0],
                [time(), 24, 1, 289, 10000000, 10000000, 0],
                [time(), 24, 1, 290, 10000000, 10000000, 0],
                [time(), 24, 1, 291, 10000000, 10000000, 0],
                [time(), 24, 1, 292, 10000000, 10000000, 0],
                [time(), 24, 1, 293, 10000000, 10000000, 0],
                [time(), 24, 1, 294, 10000000, 10000000, 0],
                [time(), 24, 1, 295, 10000000, 10000000, 0],
                [time(), 24, 1, 296, 10000000, 10000000, 0],
                [time(), 24, 1, 297, 10000000, 10000000, 0],
                [time(), 24, 1, 298, 10000000, 10000000, 0],
                [time(), 24, 1, 299, 10000000, 10000000, 0],
                [time(), 24, 1, 300, 10000000, 10000000, 0],
                [time(), 24, 1, 301, 10000000, 10000000, 0],
                [time(), 24, 1, 302, 10000000, 10000000, 0],
                [time(), 24, 1, 303, 10000000, 10000000, 0],
                [time(), 24, 1, 304, 10000000, 10000000, 0],
                [time(), 24, 1, 305, 10000000, 10000000, 0],
                [time(), 24, 1, 306, 10000000, 10000000, 0],
                [time(), 24, 1, 307, 10000000, 10000000, 0],
                [time(), 24, 1, 308, 10000000, 10000000, 0],
                [time(), 24, 1, 309, 10000000, 10000000, 0],
                [time(), 24, 1, 310, 10000000, 10000000, 0],
                [time(), 24, 1, 311, 10000000, 10000000, 0],
                [time(), 24, 1, 312, 10000000, 10000000, 0],
                [time(), 24, 1, 313, 10000000, 10000000, 0],
                [time(), 24, 1, 314, 10000000, 10000000, 0],
                [time(), 24, 1, 315, 10000000, 10000000, 0],
                [time(), 24, 1, 316, 10000000, 10000000, 0],
                [time(), 24, 1, 317, 10000000, 10000000, 0],
                [time(), 24, 1, 318, 10000000, 10000000, 0],
                [time(), 24, 1, 319, 10000000, 10000000, 0],
                [time(), 24, 1, 320, 10000000, 10000000, 0],
                [time(), 24, 1, 321, 10000000, 10000000, 0],
                [time(), 24, 1, 322, 10000000, 10000000, 0],
                [time(), 24, 1, 323, 10000000, 10000000, 0],
                [time(), 24, 1, 324, 10000000, 10000000, 0],
                [time(), 24, 1, 325, 10000000, 10000000, 0],
                [time(), 24, 1, 326, 10000000, 10000000, 0],
                [time(), 24, 1, 327, 10000000, 10000000, 0],
                [time(), 24, 1, 328, 10000000, 10000000, 0],
                [time(), 24, 1, 329, 10000000, 10000000, 0],
                [time(), 24, 1, 330, 10000000, 10000000, 0],
                [time(), 24, 1, 331, 10000000, 10000000, 0],
                [time(), 24, 1, 332, 10000000, 10000000, 0],
                [time(), 24, 1, 333, 10000000, 10000000, 0],
                [time(), 24, 1, 334, 10000000, 10000000, 0],
                [time(), 24, 1, 335, 10000000, 10000000, 0],
                [time(), 24, 1, 336, 10000000, 10000000, 0],
                [time(), 24, 1, 337, 10000000, 10000000, 0],
                [time(), 24, 1, 338, 10000000, 10000000, 0],
                [time(), 24, 1, 339, 10000000, 10000000, 0],
                [time(), 24, 1, 340, 10000000, 10000000, 0],
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
