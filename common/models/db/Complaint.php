<?php

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class Complaint
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $chat_id
 * @property int $forum_message_id
 * @property int $game_comment_id
 * @property int $loan_comment_id
 * @property int $news_id
 * @property int $news_comment_id
 * @property int $ready
 * @property string $text
 * @property int $transfer_comment_id
 * @property int $user_id
 *
 * @property-read Chat $chat
 * @property-read ForumMessage $forumMessage
 * @property-read GameComment $gameComment
 * @property-read LoanComment $loanComment
 * @property-read News $news
 * @property-read NewsComment $newsComment
 * @property-read TransferComment $transferComment
 */
class Complaint extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%complaint}}';
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
            [['text', 'user_id'], 'required'],
            [['text'], 'trim'],
            [['text'], 'string'],
            [
                [
                    'chat_id',
                    'forum_message_id',
                    'game_comment_id',
                    'loan_comment_id',
                    'news_id',
                    'news_comment_id',
                    'ready',
                    'transfer_comment_id',
                ],
                'integer',
                'min' => 1,
            ],
            [
                ['chat_id'],
                AtLeastValidator::class,
                'in' => [
                    'chat_id',
                    'forum_message_id',
                    'game_comment_id',
                    'loan_comment_id',
                    'news_id',
                    'news_comment_id',
                    'transfer_comment_id',
                ],
            ],
            [['chat_id'], 'exist', 'targetRelation' => 'chat'],
            [['forum_message_id'], 'exist', 'targetRelation' => 'forumMessage'],
            [['game_comment_id'], 'exist', 'targetRelation' => 'gameComment'],
            [['loan_comment_id'], 'exist', 'targetRelation' => 'loanComment'],
            [['news_id'], 'exist', 'targetRelation' => 'news'],
            [['news_comment_id'], 'exist', 'targetRelation' => 'newsComment'],
            [['transfer_comment_id'], 'exist', 'targetRelation' => 'transferComment'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getChat(): ActiveQuery
    {
        return $this->hasOne(Chat::class, ['id' => 'chat_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getForumMessage(): ActiveQuery
    {
        return $this->hasOne(ForumMessage::class, ['id' => 'forum_message_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGameComment(): ActiveQuery
    {
        return $this->hasOne(GameComment::class, ['id' => 'game_comment_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLoanComment(): ActiveQuery
    {
        return $this->hasOne(LoanComment::class, ['id' => 'loan_comment_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNews(): ActiveQuery
    {
        return $this->hasOne(News::class, ['id' => 'news_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNewsComment(): ActiveQuery
    {
        return $this->hasOne(NewsComment::class, ['id' => 'news_comment_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTransferComment(): ActiveQuery
    {
        return $this->hasOne(TransferComment::class, ['id' => 'transfer_comment_id']);
    }
}
