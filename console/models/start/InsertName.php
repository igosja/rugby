<?php

namespace console\models\start;

use common\components\helpers\ErrorHelper;
use common\models\db\Country;
use common\models\db\Name;
use common\models\db\NameCountry;
use Exception;
use Yii;

/**
 * Class InsertName
 * @package console\models\start
 */
class InsertName
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $engName = [
            'Andrew',
            'Anthony',
            'Arthur',
            'Brian',
            'Carl',
            'Charles',
            'Christopher',
            'Daniel',
            'Danny',
            'David',
            'Dennis',
            'Donald',
            'Douglas',
            'Edward',
            'Eric',
            'Frank',
            'George',
            'Gregory',
            'Harold',
            'Henry',
            'James',
            'Jason',
            'Jeffrey',
            'Jerry',
            'John',
            'Jose',
            'Joseph',
            'Joshua',
            'Kenneth',
            'Kevin',
            'Larry',
            'Mark',
            'Matthew',
            'Michael',
            'Patrick',
            'Paul',
            'Peter',
            'Raymond',
            'Richard',
            'Robert',
            'Roger',
            'Ronald',
            'Ryan',
            'Scott',
            'Stephen',
            'Steven',
            'Thomas',
            'Timothy',
            'Walter',
            'William',
        ];
        $fraName = [
            'Alain',
            'Alexandre',
            'Bernard',
            'Christian',
            'Christophe',
            'Claude',
            'Daniel',
            'David',
            'Didier',
            'Dominique',
            'Eric',
            'Francois',
            'Frederic',
            'Gerard',
            'Jacques',
            'Jean',
            'Julien',
            'Laurent',
            'Michel',
            'Nicolas',
            'Olivier',
            'Pascal',
            'Patrick',
            'Philippe',
            'Pierre',
            'Sebastien',
            'Stephane',
            'Thierry',
            'Thomas',
            'Vincent',
        ];
        $argName = [
            'Nicolаs',
            'Matias',
            'Lucas',
            'Martin',
            'Juan',
            'Manuel',
            'Franco',
            'Sebastian',
            'Agustin',
            'Ivan',
            'Javier',
            'Santiago',
            'Marcos',
            'Diego',
            'Federico',
            'Leo',
            'Brian',
            'Facundo',
            'Cristian',
            'Rodrigo',
            'Alan',
            'Julian',
            'Fernando',
            'Gonzalo',
            'Ignacio',
            'David',
            'Luis',
            'Dylan',
            'Esteban',
            'Luciano',
            'Ariel',
            'Daniel',
            'Gastуn',
            'Andres',
            'Fabian',
            'Tomas',
            'Joaquyn',
            'Francisco',
            'Nahuel',
            'Rafael',
            'Leandro',
            'Aalexis',
            'Marco',
            'Guillermo',
            'Adrian',
            'Maxi',
            'Mariano',
            'German',
            'Pedro',
            'Messi',
        ];
        $itaName = [
            'Francesco',
            'Alessandro',
            'Alessio',
            'Andrea',
            'Angelo',
            'Antonio',
            'Christian',
            'Cristian',
            'Daniel',
            'Daniele',
            'Davide',
            'Diego',
            'Domenico',
            'Edoardo',
            'Elia',
            'Emanuele',
            'Federico',
            'Filippo',
            'Gabriel',
            'Gabriele',
            'Giacomo',
            'Gioele',
            'Giorgio',
            'Giovanni',
            'Giulio',
            'Giuseppe',
            'Jacopo',
            'Leonardo',
            'Lorenzo',
            'Luca',
            'Luigi',
            'Manuel',
            'Marco',
            'Matteo',
            'Mattia',
            'Michele',
            'Nicola',
            'Nicolo',
            'Paolo',
            'Pietro',
            'Raffaele',
            'Riccardo',
            'Salvatore',
            'Samuel',
            'Samuele',
            'Simone',
            'Stefano',
            'Thomas',
            'Tommaso',
            'Vincenzo',
        ];

        $nameArray = [
            [
                'country' => 'England',
                'list' => $engName,
            ],
            [
                'country' => 'Ireland',
                'list' => $engName,
            ],
            [
                'country' => 'Scotland',
                'list' => $engName,
            ],
            [
                'country' => 'Wales',
                'list' => $engName,
            ],
            [
                'country' => 'Australia',
                'list' => $engName,
            ],
            [
                'country' => 'Argentina',
                'list' => $argName,
            ],
            [
                'country' => 'France',
                'list' => $fraName,
            ],
            [
                'country' => 'Italy',
                'list' => $itaName,
            ],
            [
                'country' => 'New Zealand',
                'list' => $engName,
            ],
            [
                'country' => 'South Africa',
                'list' => $engName,
            ],
        ];

        $data = [];
        foreach ($nameArray as $country) {
            $countryId = Country::find()
                ->select(['country_id'])
                ->where(['country_name' => $country['country']])
                ->limit(1)
                ->scalar();

            $country['list'] = array_unique($country['list']);

            $nameCountryList = Name::find()
                ->where(['name_name' => $country['list']])
                ->indexBy(['name_name'])
                ->all();
            foreach ($country['list'] as $item) {
                if (isset($nameCountryList[$item])) {
                    $data[] = [$countryId, $nameCountryList[$item]->name_id];
                    continue;
                }

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $name = new Name();
                    $name->name_name = $item;
                    $name->save();
                    $transaction->commit();
                    $data[] = [$countryId, $name->name_id];
                } catch (Exception $e) {
                    $transaction->rollBack();
                    ErrorHelper::log($e);
                }
            }
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                NameCountry::tableName(),
                ['name_country_country_id', 'name_country_name_id'],
                $data
            )
            ->execute();
    }
}
