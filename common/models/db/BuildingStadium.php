<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use common\components\helpers\FormatHelper;
use DateInterval;
use DateTime;
use Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class BuildingStadium
 * @package common\models\db
 *
 * @property int $id
 * @property int $capacity
 * @property int $construction_type_id
 * @property int $date
 * @property int $day
 * @property int $ready
 * @property int $team_id
 *
 * @property-read ConstructionType $constructionType
 * @property-read Team $team
 */
class BuildingStadium extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%building_stadium}}';
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
            [['capacity', 'construction_type_id', 'day', 'team_id'], 'required'],
            [['construction_type_id'], 'integer', 'min' => 0, 'max' => 9],
            [['capacity'], 'integer', 'min' => 0, 'max' => 99999],
            [['day'], 'integer', 'min' => 0, 'max' => 99],
            [['date', 'ready', 'team_id'], 'integer', 'min' => 0],
            [['construction_type_id'], 'exist', 'targetRelation' => 'constructionType'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function endDate(): string
    {
        $day = $this->day;

        $today = (new DateTime())->setTime(9, 0)->getTimestamp();

        if ($today > time()) {
            $day--;
        }

        $interval = new DateInterval('P' . $day . 'D');
        $end = (new DateTime())->add($interval)->getTimestamp();

        return FormatHelper::asDate($end);
    }

    /**
     * @return ActiveQuery
     */
    public function getConstructionType(): ActiveQuery
    {
        return $this->hasOne(ConstructionType::class, ['id' => 'construction_type_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
