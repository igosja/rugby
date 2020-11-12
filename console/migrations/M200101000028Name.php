<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000028Name
 * @package console\migrations
 */
class M200101000028Name extends Migration
{
    private const TABLE = '{{%name}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'name' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name'],
            [
                ['Andrew'],
                ['Anthony'],
                ['Arthur'],
                ['Brian'],
                ['Carl'],
                ['Charles'],
                ['Christopher'],
                ['Daniel'],
                ['Danny'],
                ['David'],
                ['Dennis'],
                ['Donald'],
                ['Douglas'],
                ['Edward'],
                ['Eric'],
                ['Frank'],
                ['George'],
                ['Gregory'],
                ['Harold'],
                ['Henry'],
                ['James'],
                ['Jason'],
                ['Jeffrey'],
                ['Jerry'],
                ['John'],
                ['Jose'],
                ['Joseph'],
                ['Joshua'],
                ['Kenneth'],
                ['Kevin'],
                ['Larry'],
                ['Mark'],
                ['Matthew'],
                ['Michael'],
                ['Patrick'],
                ['Paul'],
                ['Peter'],
                ['Raymond'],
                ['Richard'],
                ['Robert'],
                ['Roger'],
                ['Ronald'],
                ['Ryan'],
                ['Scott'],
                ['Stephen'],
                ['Steven'],
                ['Thomas'],
                ['Timothy'],
                ['Walter'],
                ['William'],
                ['Nicolаs'],
                ['Matias'],
                ['Lucas'],
                ['Martin'],
                ['Juan'],
                ['Manuel'],
                ['Franco'],
                ['Sebastian'],
                ['Agustin'],
                ['Ivan'],
                ['Javier'],
                ['Santiago'],
                ['Marcos'],
                ['Diego'],
                ['Federico'],
                ['Leo'],
                ['Facundo'],
                ['Cristian'],
                ['Rodrigo'],
                ['Alan'],
                ['Julian'],
                ['Fernando'],
                ['Gonzalo'],
                ['Ignacio'],
                ['Luis'],
                ['Dylan'],
                ['Esteban'],
                ['Luciano'],
                ['Ariel'],
                ['Gastуn'],
                ['Andres'],
                ['Fabian'],
                ['Tomas'],
                ['Joaquyn'],
                ['Francisco'],
                ['Nahuel'],
                ['Rafael'],
                ['Leandro'],
                ['Aalexis'],
                ['Marco'],
                ['Guillermo'],
                ['Adrian'],
                ['Maxi'],
                ['Mariano'],
                ['German'],
                ['Pedro'],
                ['Messi'],
                ['Alain'],
                ['Alexandre'],
                ['Bernard'],
                ['Christian'],
                ['Christophe'],
                ['Claude'],
                ['Didier'],
                ['Dominique'],
                ['Francois'],
                ['Frederic'],
                ['Gerard'],
                ['Jacques'],
                ['Jean'],
                ['Julien'],
                ['Laurent'],
                ['Michel'],
                ['Nicolas'],
                ['Olivier'],
                ['Pascal'],
                ['Philippe'],
                ['Pierre'],
                ['Sebastien'],
                ['Stephane'],
                ['Thierry'],
                ['Vincent'],
                ['Francesco'],
                ['Alessandro'],
                ['Alessio'],
                ['Andrea'],
                ['Angelo'],
                ['Antonio'],
                ['Daniele'],
                ['Davide'],
                ['Domenico'],
                ['Edoardo'],
                ['Elia'],
                ['Emanuele'],
                ['Filippo'],
                ['Gabriel'],
                ['Gabriele'],
                ['Giacomo'],
                ['Gioele'],
                ['Giorgio'],
                ['Giovanni'],
                ['Giulio'],
                ['Giuseppe'],
                ['Jacopo'],
                ['Leonardo'],
                ['Lorenzo'],
                ['Luca'],
                ['Luigi'],
                ['Matteo'],
                ['Mattia'],
                ['Michele'],
                ['Nicola'],
                ['Nicolo'],
                ['Paolo'],
                ['Pietro'],
                ['Raffaele'],
                ['Riccardo'],
                ['Salvatore'],
                ['Samuel'],
                ['Samuele'],
                ['Simone'],
                ['Stefano'],
                ['Tommaso'],
                ['Vincenzo'],
            ]
        );

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown(): bool
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
