<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;

/**
 * Class SurnameCountry
 * @package common\models\db
 *
 * @property int $country_id
 * @property int $surname_id
 *
 * @property-read Country $country
 * @property-read Surname $surname
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
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['country_id', 'surname_id'], 'required'],
            [
                ['surname_id'],
                'unique',
                'filter' => function (Query $query): Query {
                    return $query->andWhere(['country_id' => $this->country_id]);
                }
            ],
            [['country_id'], 'integer', 'min' => 1, 'max' => 999],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
            [['surname_id'], 'exist', 'targetRelation' => 'surname'],
        ];
    }

    /**
     * @param integer $countryId
     * @param string $andWhere
     * @return false|null|string
     */
    public static function getRandSurnameId(int $countryId, $andWhere = '1=1')
    {
        return self::find()
            ->joinWith(['surname'])
            ->select(['surname.id'])
            ->where(['country_id' => $countryId])
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
            ->select(['name' => new Expression('SUBSTRING(`name`, 1, ' . $length . ')')])
            ->where(['team_id' => $teamId])
            ->orderBy(['player.id' => SORT_ASC])
            ->column();

        if (!count($teamSurnameArray)) {
            $surnameId = self::getRandSurnameId($countryId);
        } else {
            $surnameId = self::getRandSurnameId(
                $countryId,
                ['not', ['SUBSTRING(`name`, 1, ' . $length . ')' => $teamSurnameArray]]
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
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSurname(): ActiveQuery
    {
        return $this->hasOne(Surname::class, ['id' => 'surname_id']);
    }
}
