<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Site
 * @package common\models\db
 *
 * @property int $id
 * @property int $date_cron
 * @property bool $status
 * @property int $version_1
 * @property int $version_2
 * @property int $version_3
 * @property int $version_date
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
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['status'], 'boolean'],
            [['version_1', 'version_2', 'version_3'], 'integer', 'min' => 0, 'max' => 99],
            [['date_cron', 'version_date'], 'integer', 'min' => 0],
        ];
    }

    /**
     * @return bool
     */
    public static function switchStatus(): bool
    {
        $model = self::find()
            ->where(['id' => 1])
            ->one();
        $model->status = 1 - $model->status;
        $model->save(true, ['status']);

        return true;
    }
}
