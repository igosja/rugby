<?php

// TODO refactor

namespace common\models\queries;

use common\models\db\Site;

/**
 * Class SiteQuery
 * @package common\models\queries
 */
class SiteQuery
{
    /**
     * @return Site
     */
    public static function getSiteVersion(): Site
    {
        /**
         * @var Site $site
         */
        $site = Site::find()
            ->andWhere(['id' => 1])
            ->limit(1)
            ->one();
        return $site;
    }

    /**
     * @return bool
     */
    public static function getStatus(): bool
    {
        return Site::find()
                ->select(['status'])
                ->andWhere(['id' => 1])
                ->limit(1)
                ->scalar() ?? false;
    }
}
