<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class StatisticChapter
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 * @property int $order
 *
 * @property-read StatisticType[] $statisticTypes
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
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name', 'order'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 10],
            [['order'], 'integer', 'min' => 1, 'max' => 9],
            [['name', 'order'], 'unique'],
        ];
    }
    /**
     * @return array
     */
    public static function selectOptions(): array
    {
        /**
         * @var StatisticChapter[] $typesArray
         */
        $typesArray = self::find()
            ->with(['statisticTypes'])
            ->orderBy(['order' => SORT_ASC])
            ->all();

        $result = [];
        foreach ($typesArray as $item) {
            $result[$item->name] = ArrayHelper::map(
                $item->statisticTypes,
                'id',
                'name'
            );
        }

        return $result;
    }

    /**
     * @return ActiveQuery
     */
    public function getStatisticTypes(): ActiveQuery
    {
        return $this->hasMany(StatisticType::class, ['statistic_chapter_id' => 'id']);
    }
}
