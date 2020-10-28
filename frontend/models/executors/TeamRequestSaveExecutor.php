<?php

namespace frontend\models\executors;

use common\components\helpers\ErrorHelper;
use common\components\interfaces\ExecuteInterface;
use common\models\db\TeamRequest;
use Exception;

/**
 * Class TeamRequestSaveExecutor
 * @package frontend\models\executors
 *
 * @property-read int $leaveId
 * @property-read int $teamId
 * @property-read int $userId
 */
class TeamRequestSaveExecutor implements ExecuteInterface
{
    /**
     * @var int $leaveId
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
     * TeamAskRequestExecute constructor.
     * @param int $teamId
     * @param int $userId
     * @param int $leaveId
     */
    public function __construct(int $teamId, int $userId, int $leaveId = 0)
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
        $model->team_request_leave_id = $this->leaveId;
        $model->team_request_team_id = $this->teamId;
        $model->team_request_user_id = $this->userId;
        if (!$model->save()) {
            ErrorHelper::log(
                new Exception(
                    ErrorHelper::modelErrorsToString($model)
                )
            );
            return false;
        }
        return true;
    }
}