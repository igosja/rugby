<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class NewsComment
 * @package common\models\db
 *
 * @property int $id
 * @property int $check
 * @property int $date
 * @property int $news_id
 * @property string $text
 * @property int $user_id
 *
 * @property-read News $news
 * @property-read User $user
 */
class NewsComment extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%news_comment}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['news_id', 'text', 'user_id'], 'required'],
            [['check', 'news_id', 'user_id'], 'integer'],
            [['text'], 'trim'],
            [['text'], 'string'],
            [['news_id'], 'exist', 'targetRelation' => 'news',],
            [['user_id'], 'exist', 'targetRelation' => 'user',],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getNews(): ActiveQuery
    {
        return $this->hasOne(News::class, ['id' => 'news_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->cache();
    }
}
