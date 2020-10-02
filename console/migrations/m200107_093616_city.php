<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_093616_city
 * @package console\migrations
 */
class m200107_093616_city extends Migration
{
    private const TABLE = '{{%city}}';

    /**
     * @return bool|void
     * @throws \yii\db\Exception
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'city_id' => $this->primaryKey(11),
            'city_country_id' => $this->integer(3)->defaultValue(0),
            'city_name' => $this->string(255),
        ]);

        $this->createIndex('city_country_id', self::TABLE, 'city_country_id');

        $this->insert(self::TABLE, [
            'city_name' => 'League'
        ]);

        $this->update(self::TABLE, ['city_id' => 0], ['city_id' => 1]);

        Yii::$app->db->createCommand('ALTER TABLE ' . self::TABLE . ' AUTO_INCREMENT=1')->execute();

        $this->batchInsert(self::TABLE, ['city_country_id', 'city_name'], [
            [54, 'Bath'],
            [54, 'Bristol'],
            [54, 'Exeter'],
            [54, 'Gloucester'],
            [54, 'London'],
            [54, 'Leicester'],
            [54, 'Reading'],
            [54, 'Northampton'],
            [54, 'Salford'],
            [54, 'Coventry'],
            [54, 'Worcester'],
            [54, 'Ampthill'],
            [54, 'Bedford'],
            [54, 'Penzance'],
            [54, 'Doncaster'],
            [54, 'West Ealing'],
            [54, 'Hartpury'],
            [54, 'Saint Peter'],
            [54, 'Richmond'],
            [54, 'Newcastle'],
            [54, 'Nottingham'],
            [54, 'Leeds'],
            [54, 'Birmingham'],
            [54, 'Bishop\'s Stortford'],
            [54, 'Eltham'],
            [54, 'Cambridge'],
            [54, 'Canterbury'],
            [54, 'Thame'],
            [54, 'Cinderford'],
            [54, 'Darlington'],
            [54, 'Brantingham'],
            [54, 'Sonning'],
            [81, 'Belfast'],
            [81, 'Limerick Cork'],
            [81, 'Dublin'],
            [81, 'Galway'],
            [81, 'Down'],
            [81, 'Cork'],
            [81, 'Limerick'],
            [81, 'Armagh'],
            [81, 'Banbridge'],
            [81, 'Naas'],
            [81, 'Navan'],
            [81, 'Antrim'],
            [81, 'Athlone'],
            [81, 'Cashel'],
            [81, 'Leixlip'],
            [81, 'Nenagh'],
            [81, 'Magherafelt'],
            [154, 'Edinburgh'],
            [154, 'Glasgow'],
            [154, 'Ayr'],
            [154, 'Melrose'],
            [154, 'Stirling'],
            [154, 'Aberdeen'],
            [154, 'Balerno'],
            [154, 'Giffnock'],
            [154, 'Hawick'],
            [154, 'Jedburgh'],
            [154, 'Troon'],
            [154, 'Musselburgh'],
            [154, 'Selkirk'],
            [154, 'Biggar'],
            [154, 'Inverness'],
            [154, 'Galashiels'],
            [154, 'Kelso'],
            [154, 'Dundee'],
            [154, 'Dumfries'],
            [154, 'Falkirk'],
            [154, 'Kirkcaldy'],
            [194, 'Cardiff'],
            [194, 'Newport'],
            [194, 'Swansea'],
            [194, 'Llanelli'],
            [194, 'Carmarthen'],
            [194, 'Port Talbot'],
            [194, 'Llandovery'],
            [194, 'Pontypridd'],
            [194, 'Merthyr Tydfil'],
            [194, 'Colwyn Bay'],
            [194, 'Ebbw Vale'],
            [194, 'Bridgend'],
            [194, 'Pontypool'],
            [194, 'Ystrad Rhondda'],
            [194, 'Narberth'],
            [194, 'Bedlinog'],
            [194, 'Maesteg'],
            [194, 'Trebanos'],
            [194, 'Newbridge'],
            [194, 'Beddau'],
            [194, 'Rhydyfelin'],
            [194, 'Newcastle Emlyn'],
            [194, 'Brecon'],
            [194, 'Brynmawr'],
            [194, 'Ystrad Mynach'],
            [194, 'Glynneath'],
            [194, 'Aberkenfig'],
            [194, 'Tonmawr'],
            [9, 'Canberra'],
            [9, 'Melbourne'],
            [9, 'Sydney'],
            [9, 'Brisbane'],
            [9, 'Dubbo'],
            [9, 'Robina'],
            [9, 'Perth'],
            [9, 'Adelaide'],
            [9, 'Gold Coast'],
            [9, 'Tweed Heads'],
            [9, 'Newcastle'],
            [9, 'Maitland'],
            [9, 'Queanbeyan'],
            [9, 'Sunshine Coast'],
            [9, 'Wollongong'],
            [9, 'Geelong'],
            [9, 'Hobart'],
            [9, 'Townsville'],
            [9, 'Cairns'],
            [9, 'Darwin'],
            [9, 'Toowoomba'],
            [9, 'Ballarat'],
            [9, 'Bendigo'],
            [9, 'Albury'],
            [9, 'Wodonga'],
            [9, 'Launceston'],
            [9, 'Mackay'],
            [9, 'Rockhampton'],
            [9, 'Bunbury'],
            [7, 'Cardoba'],
            [7, 'Buenos Aires'],
            [7, 'Boulogne'],
            [7, 'San Isidro'],
            [7, 'La Plata'],
            [7, 'Rosario'],
            [7, 'San Miguel de Tucuman'],
            [7, 'Belgrano'],
            [7, 'Bella Vista'],
            [7, 'Los Tordos'],
            [7, 'Don Torcuato'],
            [7, 'Manuel B. Gonnet'],
            [7, 'Tortuguitas'],
            [7, 'Mendoza'],
            [7, 'Córdoba'],
            [7, 'Burzaco'],
            [7, 'Benavidez'],
            [7, 'Mar del Plata'],
            [7, 'San Fernando'],
            [7, 'Salta'],
            [7, 'Vicente Lopez'],
            [7, 'Corrientes'],
            [7, 'Pilar'],
            [7, 'Resistencia'],
            [61, 'Agen'],
            [61, 'Bayonne'],
            [61, 'Bordeaux'],
            [61, 'Brive-la-Gaillarde'],
            [61, 'Castres'],
            [61, 'Clermont-Ferrand'],
            [61, 'La Rochelle'],
            [61, 'Lyon'],
            [61, 'Montpellier'],
            [61, 'Pau'],
            [61, 'Nanterre'],
            [61, 'Paris'],
            [61, 'Toulon'],
            [61, 'Toulouse'],
            [61, 'Aurillac'],
            [61, 'Beziers'],
            [61, 'Biarritz'],
            [61, 'Carcassonne'],
            [61, 'Colomiers'],
            [61, 'Grenoble'],
            [61, 'Mont-de-Marsan'],
            [61, 'Montauban'],
            [61, 'Nevers'],
            [61, 'Oyonnax'],
            [61, 'Perpignan'],
            [61, 'Aix-en-Provence'],
            [61, 'Rouen'],
            [61, 'Angouleme'],
            [61, 'Romans-sur-Isere'],
            [61, 'Vannes'],
            [61, 'Rumilly'],
            [61, 'Nice'],
            [61, 'Graulhet'],
            [61, 'Bourgoin-Jallieu'],
            [83, 'Treviso'],
            [83, 'Parma'],
            [83, 'San Dona'],
            [83, 'Calvisano'],
            [83, 'Colorno'],
            [83, 'Rome'],
            [83, 'Florence'],
            [83, 'Piacenza'],
            [83, 'Mogliano'],
            [83, 'Padua'],
            [83, 'Rovigo'],
            [83, 'Reggio Emilia'],
            [83, 'Viadana'],
            [83, 'Milan'],
            [83, 'Recco'],
            [83, 'Turin'],
            [83, 'Parabiago'],
            [83, 'Genoa'],
            [83, 'Biella'],
            [83, 'Noceto'],
            [83, 'Padova'],
            [83, 'Valpolicella'],
            [83, 'Paese'],
            [83, 'Badia Polesine'],
            [83, 'Udine'],
            [83, 'Vicenza'],
            [83, 'Brescia'],
            [83, 'Catania'],
            [124, 'Auckland'],
            [124, 'Hamilton'],
            [124, 'Christchurch'],
            [124, 'Dunedin'],
            [124, 'Wellington'],
            [124, 'Pukekohe'],
            [124, 'Nelson'],
            [124, 'Mount Maunganui'],
            [124, 'Napier'],
            [124, 'Palmerston North'],
            [124, 'Whangarei'],
            [124, 'Invercargill'],
            [124, 'New Plymouth'],
            [124, 'Westport'],
            [124, 'Ruatoria'],
            [124, 'Levin'],
            [124, 'Taupo'],
            [124, 'Ashburton'],
            [124, 'Oamaru'],
            [124, 'Gisborne'],
            [124, 'Timaru'],
            [124, 'Paeroa'],
            [124, 'Masterton'],
            [124, 'Whanganui'],
            [124, 'Greymouth'],
            [124, 'Tauranga'],
            [124, 'Lower Hutt'],
            [124, 'Upper Hutt'],
            [164, 'Pretoria'],
            [164, 'Johannesburg'],
            [164, 'Durban'],
            [164, 'Cape Town'],
            [164, 'Bloemfontein'],
            [164, 'Port Elizabeth'],
            [164, 'Wellington'],
            [164, 'East London'],
            [164, 'Kempton Park'],
            [164, 'Welkom'],
            [164, 'Kimberley'],
            [164, 'Potchefstroom'],
            [164, 'Mbombela'],
            [164, 'George'],
            [164, 'Vereeniging'],
            [164, 'Soshanguve'],
            [164, 'Pietermaritzburg'],
            [164, 'Stellenbosch'],
            [164, 'Grahamstown'],
            [164, 'Alice'],
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
