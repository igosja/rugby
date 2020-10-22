<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class ElectionNationalPlayer
 * @package common\models\db
 *
 * @property int $id
 * @property int $election_national_application_id
 * @property int $player_id
 *
 * @property-read ElectionNationalApplication $electionNationalApplication
 * @property-read Player $player
 */
class ElectionNationalPlayer extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_national_player}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['election_national_application_id', 'player_id'], 'required'],
            [['election_national_application_id', 'player_id'], 'integer', 'min' => 1],
            [['election_national_application_id'], 'exist', 'targetRelation' => 'electionNationalApplication'],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getElectionNationalApplication(): ActiveQuery
    {
        return $this->hasOne(ElectionNationalApplication::class, ['id' => 'election_national_application_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayer(): ActiveQuery
    {
        return $this->hasOne(Player::class, ['id' => 'player_id']);
    }
}
