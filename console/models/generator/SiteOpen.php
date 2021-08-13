<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Site;

/**
 * Class SiteOpen
 * @package console\models\generator
 */
class SiteOpen
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Site::updateAll(['status' => true], ['id' => 1]);
    }
}
