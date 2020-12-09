<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Site;
use DateTime;
use DateTimeZone;
use Exception;
use Yii;

/**
 * Class CheckCronDate
 * @package console\models\generator
 */
class CheckCronDate
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $dateCron = Site::find()->select(['date_cron'])->where(['id' => 1])->limit(1)->scalar();

        if (!$dateCron) {
            Yii::$app->end();
        }

        if (date('Y-m-d', $dateCron) === date('Y-m-d')) {
            Yii::$app->end();
        }

        $formattedTime = (new DateTime('now', new DateTimeZone('UTC')))->format('H:i');
        if (!in_array($formattedTime, ['08:57', '08:58', '08:59', '09:00', '09:01', '09:02', '09:03'])) {
            Yii::$app->end();
        }
    }
}