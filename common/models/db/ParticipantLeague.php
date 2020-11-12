<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ParticipantLeague
 * @package common\models\db
 *
 * @property int $id
 * @property int $season_id
 * @property int $stage_1
 * @property int $stage_2
 * @property int $stage_4
 * @property int $stage_8
 * @property int $stage_in_id
 * @property int $stage_out_id
 * @property int $team_id
 *
 * @property-read Season $season
 * @property-read Stage $stageIn
 * @property-read Stage $stageOut
 * @property-read Team $team
 */
class ParticipantLeague extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%participant_league}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['season_id', 'stage_in_id', 'team_id'], 'required'],
            [['stage_1', 'stage_2', 'stage_4', 'stage_8'], 'integer', 'min' => 0, 'max' => 9],
            [['stage_in_id', 'stage_out_id'], 'integer', 'min' => 1, 'max' => 99],
            [['season_id'], 'integer', 'min' => 1, 'max' => 999],
            [['team_id'], 'integer', 'min' => 1],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['stage_in_id'], 'exist', 'targetRelation' => 'stageIn'],
            [['stage_out_id'], 'exist', 'targetRelation' => 'stageOut'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
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
    public function getStageIn(): ActiveQuery
    {
        return $this->hasOne(Stage::class, ['id' => 'stage_in_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStageOut(): ActiveQuery
    {
        return $this->hasOne(Stage::class, ['id' => 'stage_out_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
