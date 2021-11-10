<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use rmrevin\yii\fontawesome\FAS;
use rmrevin\yii\fontawesome\FontAwesome;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * Class ForumMessage
 * @package common\models\db
 *
 * @property int $id
 * @property int $check
 * @property int $date
 * @property int $date_blocked
 * @property int $date_update
 * @property int $forum_theme_id
 * @property string $text
 * @property int $user_id
 *
 * @property-read Complaint[] $complaints
 * @property-read ForumTheme $forumTheme
 * @property-read User $user
 */
class ForumMessage extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%forum_message}}';
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
            [['forum_theme_id', 'text', 'user_id'], 'required'],
            [['check', 'date_blocked', 'date_update', 'forum_theme_id', 'user_id'], 'integer', 'min' => 0],
            [['text'], 'trim'],
            [['forum_theme_id'], 'exist', 'targetRelation' => 'forumTheme'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return bool
     */
    public function beforeDelete(): bool
    {
        foreach ($this->complaints as $complaint) {
            $complaint->delete();
        }
        return parent::beforeDelete();
    }

    /**
     * @return bool
     */
    public function addMessage(): bool
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
        $userBlock = $user->getUserBlock(UserBlockType::TYPE_FORUM)->one();
        if ($userBlock && $userBlock->date >= time()) {
            return false;
        }

        /**
         * @var UserBlock $userBlock
         */
        $userBlock = $user->getUserBlock(UserBlockType::TYPE_COMMENT)->one();
        if ($userBlock && $userBlock->date >= time()) {
            return false;
        }

        $this->user_id = $user->id;
        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        $this->save();

        return true;
    }

    /**
     * @return string
     */
    public function links(): string
    {
        /**
         * @var User $user
         */
        $user = Yii::$app->user->identity;
        if (!$user) {
            return '';
        }

        $isUser = (UserRole::USER === $user->user_role_id);
        $linkArray = [
            Html::a(
                FAS::icon(FontAwesome::_QUOTE_RIGHT),
                'javascript:',
                ['class' => 'forum-quote', 'data' => ['text' => $this->text], 'title' => Yii::t('common', 'models.db.forum-message.links.quote')]
            ),
        ];

        if (($user->id === $this->user_id && !$this->date_blocked) || !$isUser) {
            $linkArray[] = Html::a(
                FAS::icon(FontAwesome::_PENCIL_ALT),
                ['message/edit', 'id' => $this->id],
                ['title' => Yii::t('common', 'models.db.forum-message.links.edit')]
            );
        }

        if ($user->id === $this->user_id || !$isUser) {
            $linkArray[] = Html::a(
                FAS::icon(FontAwesome::_TRASH),
                ['message/delete', 'id' => $this->id],
                ['title' => Yii::t('common', 'models.db.forum-message.links.delete')]
            );
        }

        if (!$this->complaints) {
            $linkArray[] = Html::a(
                FAS::icon(FontAwesome::_EXCLAMATION_TRIANGLE),
                ['message/complaint', 'id' => $this->id],
                ['title' => Yii::t('common', 'models.db.forum-message.links.complaint')]
            );
        }

        if (!$isUser) {
            $linkArray[] = Html::a(
                FAS::icon(FontAwesome::_ARROW_CIRCLE_RIGHT),
                ['message/move', 'id' => $this->id],
                ['title' => Yii::t('common', 'models.db.forum-message.links.move')]
            );

            if (!$this->date_blocked) {
                $text = Yii::t('common', 'models.db.forum-message.links.block');
                $icon = FontAwesome::_LOCK_OPEN;
            } else {
                $text = Yii::t('common', 'models.db.forum-message.links.unblock');
                $icon = FontAwesome::_LOCK;
            }
            $linkArray[] = Html::a(
                FAS::icon($icon),
                ['message/block', 'id' => $this->id],
                ['title' => $text]
            );
        }

        return implode(' ', $linkArray);
    }

    /**
     * @return ActiveQuery
     */
    public function getComplaints(): ActiveQuery
    {
        return $this->hasMany(Complaint::class, ['forum_message_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getForumTheme(): ActiveQuery
    {
        return $this->hasOne(ForumTheme::class, ['id' => 'forum_theme_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
