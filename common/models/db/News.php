<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class News
 * @package common\models\db
 *
 * @property int $id
 * @property int $check
 * @property int $federation_id
 * @property int $date
 * @property string $text
 * @property string $title
 * @property int $user_id
 *
 * @property-read Federation $federation
 * @property-read NewsComment[] $newsComments
 * @property-read User $user
 */
class News extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%news}}';
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
     * @return array
     */
    public function rules(): array
    {
        return [
            [['text', 'title', 'user_id'], 'required'],
            [['check', 'federation_id', 'user_id'], 'integer'],
            [['text', 'title'], 'trim'],
            [['title'], 'string', 'max' => 255],
            [['text'], 'string'],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getFederation(): ActiveQuery
    {
        return $this->hasOne(Federation::class, ['id' => 'federation_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getNewsComments(): ActiveQuery
    {
        return $this->hasMany(NewsComment::class, ['news_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->cache();
    }
}
