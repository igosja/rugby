<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Season
 * @package common\models\db
 *
 * @property int $id
 * @property bool $is_future
 */
class Season extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%season}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['is_future'], 'boolean'],
        ];
    }

    /**
     * @return array
     */
    public static function getSeasonArray(): array
    {
        $result = self::find()
            ->orderBy(['id' => SORT_DESC])
            ->all();
        return ArrayHelper::map($result, 'id', 'id');
    }

    /**
     * @return int
     */
    public static function getCurrentSeason(): int
    {
        return self::find()->andWhere(['is_future' => false])->max('id');
    }
}
