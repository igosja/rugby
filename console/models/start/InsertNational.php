<?php

// TODO refactor

namespace console\models\start;

use common\models\db\City;
use common\models\db\National;
use common\models\db\NationalType;
use Yii;
use yii\db\Exception;

/**
 * Class InsertNational
 * @package console\models\start
 */
class InsertNational
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $nationalTypeArray = NationalType::find()
            ->each();
        $cityArray = City::find()
            ->where(['!=', 'city_country_id', 0])
            ->groupBy('city_country_id')
            ->orderBy(['city_country_id' => SORT_ASC])
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
                $data[] = [$nationalType->national_type_id, $city->city_country_id];
            }
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                National::tableName(),
                ['national_national_type_id', 'national_country_id'],
                $data
            )
            ->execute();
    }
}
