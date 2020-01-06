<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use Exception;
use yii\db\ActiveQuery;

/**
 * Class Finance
 * @package common\models\db
 *
 * @property int $finance_id
 * @property int $finance_building_id
 * @property int $finance_capacity
 * @property string $finance_comment
 * @property int $finance_country_id
 * @property int $finance_date
 * @property int $finance_finance_text_id
 * @property int $finance_level
 * @property int $finance_loan_id
 * @property int $finance_national_id
 * @property int $finance_player_id
 * @property int $finance_season_id
 * @property int $finance_team_id
 * @property int $finance_transfer_id
 * @property int $finance_user_id
 * @property int $finance_value
 * @property int $finance_value_after
 * @property int $finance_value_before
 *
 * @property Building $building
 * @property Country $country
 * @property FinanceText $financeText
 * @property Loan $loan
 * @property National $national
 * @property Player $player
 * @property Team $team
 * @property Transfer $transfer
 * @property User $user
 */
class Finance extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%finance}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [
                [
                    'finance_id',
                    'finance_building_id',
                    'finance_capacity',
                    'finance_country_id',
                    'finance_finance_text_id',
                    'finance_loan_id',
                    'finance_national_id',
                    'finance_player_id',
                    'finance_team_id',
                    'finance_transfer_id',
                    'finance_user_id',
                    'finance_value',
                    'finance_value_after',
                    'finance_value_before'
                ],
                'integer'
            ],
            [['finance_level'], 'integer', 'min' => 0, 'max' => 10],
            [['finance_finance_text_id', 'finance_value', 'finance_value_after', 'finance_value_before'], 'required'],
            [['finance_comment'], 'string', 'max' => 255],
            [
                ['finance_building_id'],
                'exist',
                'targetRelation' => 'building',
            ],
            [
                ['finance_country_id'],
                'exist',
                'targetRelation' => 'country',
            ],
            [
                ['finance_finance_text_id'],
                'exist',
                'targetRelation' => 'financeText',
            ],
            [
                ['finance_loan_id'],
                'exist',
                'targetRelation' => 'loan',
            ],
            [
                ['finance_national_id'],
                'exist',
                'targetRelation' => 'national',
            ],
            [
                ['finance_player_id'],
                'exist',
                'targetRelation' => 'player',
            ],
            [
                ['finance_team_id'],
                'exist',
                'targetRelation' => 'team',
            ],
            [
                ['finance_transfer_id'],
                'exist',
                'targetRelation' => 'transfer',
            ],
            [
                ['finance_user_id'],
                'exist',
                'targetRelation' => 'user',
            ],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($this->isNewRecord) {
            if (!$this->finance_season_id) {
                $this->finance_season_id = Season::getCurrentSeason();
            }
            $this->finance_date = time();
        }
        return true;
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public static function log(array $data): bool
    {
        $history = new self();
        $history->setAttributes($data);
        return $history->save();
    }

    /**
     * @return string
     */
    public function text(): string
    {
        $text = $this->financeText->finance_text_text;
        if (false !== strpos($text, '{player}')) {
            $text = str_replace(
                '{player}',
                $this->player->playerLink(),
                $text
            );
        }
        if (false !== strpos($text, '{team}')) {
            $text = str_replace(
                '{team}',
                $this->team->teamLink(),
                $text
            );
        }
        if (false !== strpos($text, '{user}')) {
            $text = str_replace(
                '{user}',
                $this->user->userLink(),
                $text
            );
        }
        if (false !== strpos($text, '{building}')) {
            $building = '';
            if (Building::BASE === $this->finance_building_id) {
                $building = 'база';
            } elseif (Building::MEDICAL === $this->finance_building_id) {
                $building = 'медцентр';
            } elseif (Building::PHYSICAL === $this->finance_building_id) {
                $building = 'центр физподготовки';
            } elseif (Building::SCHOOL === $this->finance_building_id) {
                $building = 'спортшкола';
            } elseif (Building::SCOUT === $this->finance_building_id) {
                $building = 'скаут-центр';
            } elseif (Building::TRAINING === $this->finance_building_id) {
                $building = 'тренировочный центр';
            }
            $text = str_replace(
                '{building}',
                $building,
                $text
            );
        }
        $text = str_replace(
            ['{capacity}', '{level}'],
            [$this->finance_capacity, $this->finance_level],
            $text
        );

        if ($this->finance_comment) {
            $text .= ' (' . $this->finance_comment . ')';
        }

        return $text;
    }

    /**
     * @return ActiveQuery
     */
    public function getBuilding(): ActiveQuery
    {
        return $this->hasOne(Building::class, ['building_id' => 'finance_building_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['country_id' => 'finance_country_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getFinanceText(): ActiveQuery
    {
        return $this->hasOne(FinanceText::class, ['finance_text_id' => 'finance_finance_text_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getLoan(): ActiveQuery
    {
        return $this->hasOne(Loan::class, ['loan_id' => 'finance_loan_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getNational(): ActiveQuery
    {
        return $this->hasOne(Loan::class, ['national_id' => 'finance_national_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayer(): ActiveQuery
    {
        return $this->hasOne(Player::class, ['player_id' => 'finance_player_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['team_id' => 'finance_team_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getTransfer(): ActiveQuery
    {
        return $this->hasOne(Transfer::class, ['transfer_id' => 'finance_transfer_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'finance_user_id'])->cache();
    }
}
