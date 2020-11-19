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
    public function progress(array $modelArray)
    {
        for ($i = 0, $countModel = count($modelArray); $i < $countModel; $i++) {
            $this->stdout('Starting ' . get_class($modelArray[$i]) . '...');
            $start = microtime(true);
            $modelArray[$i]->execute();
            $time = microtime(true) - $start;
            $this->stdout(' Ready. ' . round(($i + 1) / $countModel * 100, 1) . '% done (' . sprintf('%.3f', $time) . ' sec)' . PHP_EOL);
        }
    }
}
