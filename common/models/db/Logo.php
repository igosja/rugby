<?php

// TODO refactor

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class Logo
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $team_id
 * @property string $text
 * @property int $user_id
 *
 * @property-read Team $team
 * @property-read User $user
 */
class Logo extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%logo}}';
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
            [['text'], 'required'],
            [['team_id'], AtLeastValidator::class, 'in' => ['team_id', 'user_id']],
            [['text'], 'string'],
            [['team_id', 'user_id'], 'integer', 'min' => 1],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return bool
     */
    public function beforeDelete(): bool
    {
        if ($this->team_id) {
            $file = Yii::getAlias('@frontend') . '/web/upload/img/team/125/' . $this->team_id . '.png';
        } else {
            $file = Yii::getAlias('@frontend') . '/web/upload/img/user/125/' . $this->user_id . '.png';
        }

        if (file_exists($file)) {
            unlink($file);
        }

        return parent::beforeDelete();
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
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
