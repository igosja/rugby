<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Style
 * @package common\models\db
 *
 * @property int $style_id
 * @property string $style_name
 */
class Style extends AbstractActiveRecord
{
    const NORMAL = 1;
    const DOWN_THE_MIDDLE = 2;
    const CHAMPAGNE = 3;
    const MAN_10 = 4;
    const MAN_15 = 5;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%style}}';
    }

    /**
     * @param array|null $notIn
     * @return int
     */
    public static function getRandStyleId(array $notIn = null): int
    {
        return self::find()
            ->select(['style_id'])
            ->where(['!=', 'style_id', self::NORMAL])
            ->andFilterWhere(['not', ['style_id' => $notIn]])
            ->orderBy('RAND()')
            ->limit(1)
            ->scalar();
    }
}
