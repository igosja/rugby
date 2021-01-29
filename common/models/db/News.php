<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use Throwable;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;

/**
 * Class News
 * @package common\models\db
 *
 * @property int $id
 * @property int $check
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
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function beforeDelete(): bool
    {
        foreach ($this->newsComments as $newsComment) {
            $newsComment->delete();
        }
        Team::updateAll(['federation_news_id' => null], ['federation_news_id' => $this->id]);
        User::updateAll(['news_id' => null], ['news_id' => $this->id]);
        return parent::beforeDelete();
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
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
