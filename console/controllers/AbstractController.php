<?php

// TODO refactor

namespace console\controllers;

use yii\console\Controller;

/**
 * Class AbstractController
 * @package console\controllers
 */
abstract class AbstractController extends Controller
{
    /**
     * @param array $modelArray
     */
    public function progress(array $modelArray): void
    {
        $this->stdout('Start.' . PHP_EOL);
        $globalStart = microtime(true);
        $countModel = count($modelArray);
        foreach ($modelArray as $i => $model) {
            $this->stdout('Starting ' . get_class($model) . '...');
            $start = microtime(true);
            $model->execute();
            $time = microtime(true) - $start;
            $this->stdout(' Ready. ' . round(($i + 1) / $countModel * 100, 1) . '% done (' . sprintf('%.3f', $time) . ' sec)' . PHP_EOL);
        }
        $globalTime = microtime(true) - $globalStart;
        $this->stdout('Done. (' . sprintf('%.3f', $globalTime) . ' sec)' . PHP_EOL);
    }
}
