<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\City;
use common\models\db\National;
use common\models\db\NationalType;
use Yii;
use yii\db\Exception;

/**
 * Class InsertNational
 * @package console\models\newSeason
 */
class InsertNational
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $nationalTypeArray = NationalType::find()
            ->each();
        $cityArray = City::find()
            ->andWhere(['!=', 'country_id', 0])
            ->andWhere(['not', ['country_id' => National::find()->joinWith(['federation'])->select(['country_id'])]])
            ->groupBy('country_id')
            ->orderBy(['country_id' => SORT_ASC])
            ->each();

        $data = [];
        foreach ($cityArray as $city) {
            /**
             * @var City $city
             */
            foreach ($nationalTypeArray as $nationalType) {
                /**
                 * @var NationalType $nationalType
                 */
                $data[] = [$nationalType->id, $city->country->federation->id];
            }
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                National::tableName(),
                ['national_type_id', 'federation_id'],
                $data
            )
            ->execute();
    }
}
