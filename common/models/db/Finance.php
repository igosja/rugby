<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * Class Finance
 * @package common\models\db
 *
 * @property int $id
 * @property int $building_id
 * @property int $capacity
 * @property string $comment
 * @property int $federation_id
 * @property int $date
 * @property int $finance_text_id
 * @property int $level
 * @property int $loan_id
 * @property int $national_id
 * @property int $player_id
 * @property int $season_id
 * @property int $team_id
 * @property int $transfer_id
 * @property int $user_id
 * @property int $value
 * @property int $value_after
 * @property int $value_before
 *
 * @property-read Building $building
 * @property-read Federation $federation
 * @property-read FinanceText $financeText
 * @property-read Loan $loan
 * @property-read National $national
 * @property-read Player $player
 * @property-read Season $season
 * @property-read Team $team
 * @property-read Transfer $transfer
 * @property-read User $user
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
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['season_id', 'value', 'value_after', 'value_before'], 'required'],
            [['building_id'], 'integer', 'min' => 0, 'max' => 9],
            [['finance_text_id', 'level'], 'integer', 'min' => 0, 'max' => 99],
            [['federation_id', 'national_id', 'season_id'], 'integer', 'min' => 0, 'max' => 999],
            [['capacity'], 'integer', 'min' => 0, 'max' => 99999],
            [['loan_id', 'player_id', 'transfer_id', 'user_id'], 'integer', 'min' => 0],
            [['building_id'], 'exist', 'targetRelation' => 'building'],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['finance_text_id'], 'exist', 'targetRelation' => 'financeText'],
            [['loan_id'], 'exist', 'targetRelation' => 'loan'],
            [['national_id'], 'exist', 'targetRelation' => 'national'],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
            [['transfer_id'], 'exist', 'targetRelation' => 'transfer'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        $text = $this->financeText->text;
        if (false !== strpos($text, '{player}')) {
            $text = str_replace(
                '{player}',
                $this->player->getPlayerLink(),
                $text
            );
        }
        if (false !== strpos($text, '{team}')) {
            $text = str_replace(
                '{team}',
                $this->team->getTeamLink(),
                $text
            );
        }
        if (false !== strpos($text, '{user}')) {
            $text = str_replace(
                '{user}',
                $this->user->getUserLink(),
                $text
            );
        }
        if (false !== strpos($text, '{building}')) {
            $building = '';
            if (Building::BASE === $this->building_id) {
                $building = 'база';
            } elseif (Building::MEDICAL === $this->building_id) {
                $building = 'медцентр';
            } elseif (Building::PHYSICAL === $this->building_id) {
                $building = 'центр физподготовки';
            } elseif (Building::SCHOOL === $this->building_id) {
                $building = 'спортшкола';
            } elseif (Building::SCOUT === $this->building_id) {
                $building = 'скаут-центр';
            } elseif (Building::TRAINING === $this->building_id) {
                $building = 'тренировочный центр';
            }
            $text = str_replace(
                '{building}',
                $building,
                $text
            );
        }
        $text = str_replace(['{capacity}', '{level}'], [$this->capacity, $this->level], $text);

        if ($this->comment) {
            $text .= ' (' . $this->comment . ')';
        }

        return $text;
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public static function log(array $data): bool
    {
        $finance = new self();
        $finance->setAttributes($data);
        $finance->season_id = Season::getCurrentSeason();
        return $finance->save();
    }

    /**
     * @return ActiveQuery
     */
    public function getBuilding(): ActiveQuery
    {
        return $this->hasOne(Building::class, ['id' => 'building_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFederation(): ActiveQuery
    {
        return $this->hasOne(Federation::class, ['id' => 'federation_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFinanceText(): ActiveQuery
    {
        return $this->hasOne(FinanceText::class, ['id' => 'finance_text_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLoan(): ActiveQuery
    {
        return $this->hasOne(Loan::class, ['id' => 'loan_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNational(): ActiveQuery
    {
        return $this->hasOne(National::class, ['id' => 'national_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayer(): ActiveQuery
    {
        return $this->hasOne(Player::class, ['id' => 'player_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSeason(): ActiveQuery
    {
        return $this->hasOne(Season::class, ['id' => 'season_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTransfer(): ActiveQuery
    {
        return $this->hasOne(Transfer::class, ['id' => 'transfer_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
