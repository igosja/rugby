<?php

// TODO refactor

namespace frontend\models\preparers;

use common\models\queries\SiteQuery;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class SitePrepare
 * @package frontend\models\preparers
 */
class SitePrepare
{
    /**
     * @return string
     * @throws InvalidConfigException
     */
    public static function getSiteVersion(): string
    {
        $siteData = SiteQuery::getSiteVersion();

        return 'Version ' . $siteData->version_1
            . '.' . $siteData->version_2
            . '.' . $siteData->version_3
            . ' / ' . Yii::$app->formatter->asDate($siteData->version_date);
    }
}