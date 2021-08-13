<?php

// TODO refactor

namespace frontend\models\executors;

use common\components\helpers\ErrorHelper;
use common\components\interfaces\ExecuteInterface;
use common\models\db\TeamRequest;
use Exception;
use yii\base\InvalidArgumentException;

/**
 * Class TeamRequestSaveExecutor
 * @package frontend\models\executors
 */
class TeamRequestSaveExecutor implements ExecuteInterface
{
    /**
     * @var int|null
     */
    private $leaveId;

    /**
     * @var int $teamId
     */
    private $teamId;

    /**
     * @var int $userId
     */
    private $userId;

    /**
     * TeamRequestSaveExecutor constructor.
     * @param int $teamId
     * @param int $userId
     * @param int|null $leaveId
     */
    public function __construct(int $teamId, int $userId, int $leaveId = null)
    {
        $this->leaveId = $leaveId;
        $this->teamId = $teamId;
        $this->userId = $userId;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function execute(): bool
    {
        $model = new TeamRequest();
        $model->leave_team_id = $this->leaveId;
        $model->team_id = $this->teamId;
        $model->user_id = $this->userId;
        if (!$model->save()) {
            throw new InvalidArgumentException(
                ErrorHelper::modelErrorsToString($model)
            );
        }
        return true;
    }
}