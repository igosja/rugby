<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\helpers\Html;

/**
 * Class Physical
 * @package common\models\db
 *
 * @property int $physical_id
 * @property string $physical_name
 * @property int $physical_opposite
 * @property int $physical_value
 */
class Physical extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%physical}}';
    }

    /**
     * @return Physical
     */
    public static function getRandPhysical(): Physical
    {
        $physicalArray = self::find()
            ->select(['physical_id', 'physical_value'])
            ->all();
        return $physicalArray[array_rand($physicalArray)];
    }

    /**
     * @return string
     */
    public function image(): string
    {
        return Html::img(
            '/img/physical/' . $this->physical_id . '.png',
            [
                'alt' => $this->physical_name,
                'title' => $this->physical_name,
            ]
        );
    }
}
