<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Site;

/**
 * Class UpdateCronDate
 * @package console\models\generator
 */
class UpdateCronDate
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Site::updateAll(['date_cron' => time()], ['id' => 1]);
    }
}