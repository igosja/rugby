<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use common\components\helpers\ErrorHelper;
use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;
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
            [['news_id', 'text', 'user_id'], 'required'],
            [['check', 'news_id', 'user_id'], 'integer'],
            [['text'], 'trim'],
            [['text'], 'string'],
            [['news_id'], 'exist', 'targetRelation' => 'news'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return bool
     */
    public function addComment(): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        /**
         * @var User $user
         */
        $user = Yii::$app->user->identity;

        if (!$user->date_confirm) {
            return false;
        }

        /**
         * @var UserBlock $userBlock
         */
        $userBlock = $user->getUserBlock(UserBlockType::TYPE_COMMENT_NEWS)->one();
        if ($userBlock && $userBlock->date >= time()) {
            return false;
        }

        $userBlock = $user->getUserBlock(UserBlockType::TYPE_COMMENT)->one();
        if ($userBlock && $userBlock->date >= time()) {
            return false;
        }

        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        try {
            if (!$this->save()) {
                return false;
            }
        } catch (Exception $e) {
            ErrorHelper::log($e);
            return false;
        }

        return true;
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
