<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class TranslateOption
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property string $text
 * @property int $translate_key_id
 * @property int $user_id
 *
 * @property-read TranslateKey $translateKey
 * @property-read TranslateVote[] $translateVotes
 * @property-read User $user
 */
class TranslateOption extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%translate_option}}';
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
            [['text', 'translate_key_id', 'user_id'], 'required'],
            [['text'], 'safe'],
            [['translate_key_id', 'user_id'], 'integer'],
            [['translate_key_id'], 'exist', 'targetRelation' => 'translateKey'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTranslateKey(): ActiveQuery
    {
        return $this->hasOne(TranslateKey::class, ['id' => 'translate_key_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTranslateVotes(): ActiveQuery
    {
        return $this->hasMany(TranslateVote::class, ['translate_option_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
