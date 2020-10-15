<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class TournamentType
 * @package common\models\db
 *
 * @property int $id
 * @property int $day_type_id
 * @property string $name
 * @property int $visitor
 *
 * @property-read DayType $dayType
 */
class TournamentType extends AbstractActiveRecord
{
    public const NATIONAL = 1;
    public const LEAGUE = 2;
    public const CHAMPIONSHIP = 3;
    public const CONFERENCE = 4;
    public const OFF_SEASON = 5;
    public const FRIENDLY = 6;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%tournament_type}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['day_type_id', 'name', 'visitor'], 'required'],
            [['day_type_id'], 'integer', 'min' => 1, 'max' => 9],
            [['visitor'], 'integer', 'min' => 1, 'max' => 999],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 20],
            [['day_type_id'], 'exist', 'targetRelation' => 'dayType'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getDayType(): ActiveQuery
    {
        return $this->hasOne(DayType::class, ['id' => 'day_type_id']);
    }
}
