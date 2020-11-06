<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class RatingFederation
 * @package common\models\db
 *
 * @property int $id
 * @property int $auto_place
 * @property int $federation_id
 * @property int $league_place
 * @property int $stadium_place
 *
 * @property-read Federation $federation
 */
class RatingFederation extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%rating_federation}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['federation_id'], 'required'],
            [['auto_place', 'federation_id', 'league_place', 'stadium_place'], 'integer', 'min' => 0, 'max' => 999],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getFederation(): ActiveQuery
    {
        return $this->hasOne(Federation::class, ['id' => 'federation_id']);
    }
}
