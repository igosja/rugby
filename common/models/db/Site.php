<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use Exception;

/**
 * Class Site
 * @package common\models\db
 *
 * @property int $site_id
 * @property int $site_date_cron
 * @property int $site_status
 * @property int $site_version_1
 * @property int $site_version_2
 * @property int $site_version_3
 * @property int $site_version_date
 */
class Site extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%site}}';
    }

    /**
     * @return string
     */
    public static function version(): string
    {
        $version = '0.0.0';
        $date = time();

        /**
         * @var self $site
         */
        $site = self::find()
            ->select(['site_version_1', 'site_version_2', 'site_version_3', 'site_version_date'])
            ->where(['site_id' => 1])
            ->one();

        if ($site) {
            $version = implode('.', [$site->site_version_1, $site->site_version_2, $site->site_version_3]);
            $date = $site->site_version_date;
        }

        try {
            $date = FormatHelper::asDate($date);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        return 'Версия ' . $version . ' от ' . $date;
    }

    /**
     * @return int
     */
    public static function status(): int
    {
        return self::find()
            ->select(['site_status'])
            ->where(['site_id' => 1])
            ->limit(1)
            ->scalar();
    }
}
