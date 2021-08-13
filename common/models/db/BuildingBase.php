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
 * Class BuildingBase
 * @package common\models\db
 *
 * @property int $id
 * @property int $building_id
 * @property int $construction_type_id
 * @property int $date
 * @property int $day
 * @property int $ready
 * @property int $team_id
 *
 * @property-read Building $building
 * @property-read ConstructionType $constructionType
 * @property-read Team $team
 */
class BuildingBase extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%building_base}}';
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
            [['building_id', 'construction_type_id', 'day', 'team_id'], 'required'],
            [['building_id', 'construction_type_id'], 'integer', 'min' => 0, 'max' => 9],
            [['day'], 'integer', 'min' => 0, 'max' => 99],
            [['date', 'ready', 'team_id'], 'integer', 'min' => 0],
            [['building_id'], 'exist', 'targetRelation' => 'building'],
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
    public function getBuilding(): ActiveQuery
    {
        return $this->hasOne(Building::class, ['id' => 'building_id']);
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
