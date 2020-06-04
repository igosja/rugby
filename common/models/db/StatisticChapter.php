<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class StatisticChapter
 * @package common\models\db
 *
 * @property int $statistic_chapter_id
 * @property string $statistic_chapter_name
 * @property int $statistic_chapter_order
 *
 * @property StatisticType[] $statisticTypes
 */
class StatisticChapter extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%statistic_chapter}}';
    }

    /**
     * @return array
     */
    public static function selectOptions(): array
    {
        /**
         * @var self[] $typesArray
         */
        $typesArray = self::find()
            ->with(['statisticTypes'])
            ->orderBy(['statistic_chapter_order' => SORT_ASC])
            ->all();

        $result = [];
        foreach ($typesArray as $item) {
            $result[$item->statistic_chapter_name] = ArrayHelper::map(
                $item->statisticTypes,
                'statistic_type_id',
                'statistic_type_name'
            );
        }

        return $result;
    }

    /**
     * @return ActiveQuery
     */
    public function getStatisticTypes(): ActiveQuery
    {
        return $this->hasMany(StatisticType::class, ['statistic_type_statistic_chapter_id' => 'statistic_chapter_id']);
    }
}
