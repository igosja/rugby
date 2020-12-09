<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Site;

/**
 * Class SiteClose
 * @package console\models\generator
 */
class SiteClose
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Site::updateAll(['status' => false], ['id' => 1]);
    }
}