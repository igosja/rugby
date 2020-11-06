<?php

namespace frontend\models\executors;

use common\components\helpers\ErrorHelper;
use common\components\interfaces\ExecuteInterface;
use common\models\db\NewsComment;
use common\models\db\User;
use common\models\db\UserBlock;
use common\models\db\UserBlockType;
use Exception;

/**
 * Class NewsCommentSaveExecutor
 * @package frontend\models\executors
 */
class NewsCommentSaveExecutor implements ExecuteInterface
{
    /**
     * @var array $data
     */
    private array $data;

    /**
     * @var NewsComment $model
     */
    private NewsComment $model;

    /**
     * @var User $user
     */
    private User $user;

    /**
     * NewsCommentSaveExecutor constructor.
     * @param User $user
     * @param NewsComment $model
     * @param array $data
     */
    public function __construct(User $user, NewsComment $model, array $data)
    {
        $this->data = $data;
        $this->model = $model;
        $this->user = $user;
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        if (!$this->user) {
            return false;
        }

        if (!$this->user->date_confirm) {
            return false;
        }

        /**
         * @var UserBlock $userBlock
         */
        $userBlock = $this->user->getUserBlock(UserBlockType::TYPE_COMMENT_NEWS)->one();
        if ($userBlock && $userBlock->date >= time()) {
            return false;
        }

        $userBlock = $this->user->getUserBlock(UserBlockType::TYPE_COMMENT)->one();
        if ($userBlock && $userBlock->date >= time()) {
            return false;
        }

        if (!$this->model->load($this->data)) {
            return false;
        }

        try {
            if (!$this->model->validate() || !$this->model->save()) {
                return false;
            }
        } catch (Exception $e) {
            ErrorHelper::log($e);
            return false;
        }

        return true;
    }
}