<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * Class SurnameCountry
 * @package common\models\db
 *
 * @property int $surname_country_country_id
 * @property int $surname_country_surname_id
 *
 * @property Surname $surname
 */
class SurnameCountry extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%surname_country}}';
    }

    /**
     * @param int $countryId
     * @param string $andWhere
     * @return int
     */
    public static function getRandSurnameId(int $countryId, $andWhere = '1=1'): int
    {
        return self::find()
            ->joinWith(['surname'])
            ->select(['surname_country_surname_id'])
            ->where(['surname_country_country_id' => $countryId])
            ->andWhere($andWhere)
            ->orderBy('RAND()')
            ->limit(1)
            ->scalar();
    }

    /**
     * @param int $teamId
     * @param int $countryId
     * @param int $length
     * @return int
     */
    public static function getRandFreeSurnameId(int $teamId, int $countryId, int $length = 1): int
    {
        $teamSurnameArray = Surname::find()
            ->joinWith(['players'])
            ->select(['surname_name' => new Expression('SUBSTRING(`surname_name`, 1, ' . $length . ')')])
            ->where(['player_team_id' => $teamId])
            ->orderBy(['player_id' => SORT_ASC])
            ->column();

        if (0 == count($teamSurnameArray)) {
            $surnameId = self::getRandSurnameId($countryId);
        } else {
            $surnameId = self::getRandSurnameId(
                $countryId,
                ['not', ['SUBSTRING(`surname_name`, 1, ' . $length . ')' => $teamSurnameArray]]
            );

            if (!$surnameId) {
                $length++;
                $surnameId = self::getRandFreeSurnameId($teamId, $countryId, $length);
            }
        }

        return $surnameId;
    }

    /**
     * @return ActiveQuery
     */
    public function getSurname(): ActiveQuery
    {
        return $this->hasOne(Surname::class, ['surname_id' => 'surname_country_surname_id'])->cache();
    }
}
