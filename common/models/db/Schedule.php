<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Schedule
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $season_id
 * @property int $stage_id
 * @property int $tournament_type_id
 *
 * @property-read Season $season
 * @property-read Stage $stage
 * @property-read TournamentType $tournamentType
 */
class Schedule extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%schedule}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['season_id', 'stage_id', 'tournament_type_id'], 'required'],
            [['tournament_type_id'], 'min' => 1, 'max' => 9],
            [['stage_id'], 'min' => 1, 'max' => 99],
            [['season_id'], 'min' => 1, 'max' => 999],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['stage_id'], 'exist', 'targetRelation' => 'stage'],
            [['tournament_type_id'], 'exist', 'targetRelation' => 'tournamentType'],
        ];
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
    public function getStage(): ActiveQuery
    {
        return $this->hasOne(Stage::class, ['id' => 'stage_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTournamentType(): ActiveQuery
    {
        return $this->hasOne(TournamentType::class, ['id' => 'tournament_type_id']);
    }
}
