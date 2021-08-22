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
        if (!in_array($formattedTime, ['11:57', '11:58', '11:59', '12:00', '12:01', '12:02', '12:03'])) {
            Yii::$app->end();
        }
    }
}