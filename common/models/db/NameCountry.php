<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class NameCountry
 * @package common\models\db
 *
 * @property int $name_country_country_id
 * @property int $name_country_name_id
 */
class NameCountry extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%name_country}}';
    }

    /**
     * @param int $countryId
     * @return int
     */
    public static function getRandNameId(int $countryId): int
    {
        return self::find()
            ->select(['name_country_name_id'])
            ->where(['name_country_country_id' => $countryId])
            ->orderBy('RAND()')
            ->limit(1)
            ->scalar();
    }
}
