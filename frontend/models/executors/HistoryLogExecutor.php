<?php

// TODO refactor

namespace frontend\models\executors;

use common\components\interfaces\ExecuteInterface;
use common\models\db\History;
use common\models\db\Season;

/**
 * Class HistoryLogExecutor
 * @package frontend\models\executors
 */
class HistoryLogExecutor implements ExecuteInterface
{
    /**
     * @var array $data
     */
    private array $data;

    /**
     * HistoryLogExecutor constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        $history = new History();
        $history->setAttributes($this->data);
        $history->season_id = Season::find()
            ->select(['id'])
            ->andWhere(['is_future' => false])
            ->orderBy(['id' => SORT_DESC])
            ->scalar();
        return $history->save();
    }
}