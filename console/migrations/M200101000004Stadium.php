<?php

namespace console\migrations;

use Yii;
use yii\db\Exception;
use yii\db\Migration;

/**
 * Class M200101000004Stadium
 * @package console\migrations
 */
class M200101000004Stadium extends Migration
{
    private const TABLE = '{{%stadium}}';

    /**
     * @return bool
     * @throws Exception
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'capacity' => $this->integer(5)->defaultValue(0),
                'city_id' => $this->integer(11)->notNull(),
                'date' => $this->integer(11)->notNull(),
                'maintenance' => $this->integer(11)->defaultValue(0),
                'name' => $this->string(255)->notNull(),
            ]
        );

        $this->addForeignKey('stadium_city_id', self::TABLE, 'city_id', '{{%city}}', 'id');

        $this->insert(
            self::TABLE,
            [
                'name' => 'League'
            ]
        );

        $this->update(self::TABLE, ['id' => 0], ['id' => 1]);

        Yii::$app->db->createCommand('ALTER TABLE ' . self::TABLE . ' AUTO_INCREMENT=1')->execute();

        $this->batchInsert(
            self::TABLE,
            [
                'capacity',
                'city_id',
                'date',
                'maintenance',
                'name',
            ],
            [
                [100, 1, time(), 8, 'Recreation Ground'],
                [100, 2, time(), 8, 'Ashton Gate'],
                [100, 3, time(), 8, 'Sandy'],
                [100, 4, time(), 8, 'Kingsholm'],
                [100, 5, time(), 8, 'Twickenham Stoop'],
                [100, 6, time(), 8, 'Welford'],
                [100, 7, time(), 8, 'Madejski'],
                [100, 8, time(), 8, 'Franklin\'s Gardens'],
                [100, 9, time(), 8, 'AJ Bell'],
                [100, 5, time(), 8, 'Allianz'],
                [100, 10, time(), 8, 'Ricoh'],
                [100, 11, time(), 8, 'Sixways'],
                [100, 12, time(), 8, 'Dillingham'],
                [100, 13, time(), 8, 'Goldington'],
                [100, 14, time(), 8, 'Mennaye Field'],
                [100, 10, time(), 8, 'Butts'],
                [100, 15, time(), 8, 'Castle'],
                [100, 16, time(), 8, 'Trailfinders'],
                [100, 17, time(), 8, 'Gillman'],
                [100, 18, time(), 8, 'Santander'],
                [100, 19, time(), 8, 'Athletic'],
                [100, 20, time(), 8, 'Kingston'],
                [100, 21, time(), 8, 'Lady Bay'],
                [100, 22, time(), 8, 'Headingley Carnegie'],
                [100, 23, time(), 8, 'Billesley Common'],
                [100, 24, time(), 8, 'Silver Leys'],
                [100, 25, time(), 8, 'Well Hall'],
                [100, 26, time(), 8, 'Grantchester'],
                [100, 27, time(), 8, 'Marine Travel'],
                [100, 28, time(), 8, 'Kingsey'],
                [100, 29, time(), 8, 'Dockham'],
                [100, 30, time(), 8, 'Northern Echo'],
                [100, 31, time(), 8, 'Brantingham'],
                [100, 32, time(), 8, 'Old Bath'],
                [100, 33, time(), 8, 'Ravenhill'],
                [100, 34, time(), 8, 'Thomond'],
                [100, 35, time(), 8, 'RDS'],
                [100, 36, time(), 8, 'Galway'],
                [100, 37, time(), 8, 'Ballymacarn'],
                [100, 35, time(), 8, 'Castle Avenue'],
                [100, 38, time(), 8, 'Temple Hill'],
                [100, 35, time(), 8, 'College'],
                [100, 39, time(), 8, 'Dooradoyle'],
                [100, 35, time(), 8, 'Aviva'],
                [100, 35, time(), 8, 'Lakelands'],
                [100, 38, time(), 8, 'Mardyke'],
                [100, 35, time(), 8, 'UCD Bowl'],
                [100, 39, time(), 8, 'Tom Clifford'],
                [100, 40, time(), 8, 'Palace Grounds'],
                [100, 41, time(), 8, 'Rifle'],
                [100, 38, time(), 8, 'Woodleigh'],
                [100, 33, time(), 8, 'Gibson'],
                [100, 42, time(), 8, 'Forenaughts'],
                [100, 43, time(), 8, 'Balreask Old'],
                [100, 35, time(), 8, 'Anglesea'],
                [100, 35, time(), 8, 'Donnybrook'],
                [100, 39, time(), 8, 'Thomond'],
                [100, 35, time(), 8, 'Templeville'],
                [100, 44, time(), 8, 'Eaton'],
                [100, 45, time(), 8, 'Dubarry'],
                [100, 46, time(), 8, 'Spafield'],
                [100, 38, time(), 8, 'Musgrave'],
                [100, 47, time(), 8, 'Parsonstown'],
                [100, 48, time(), 8, 'New Ormond'],
                [100, 39, time(), 8, 'Rosbrien'],
                [100, 33, time(), 8, 'Dub Lane'],
                [100, 49, time(), 8, 'Hatrick'],
                [100, 39, time(), 8, 'Annacotty'],
                [100, 50, time(), 8, 'Murrayfield'],
                [100, 51, time(), 8, 'Scotstoun'],
                [100, 52, time(), 8, 'Millbrae'],
                [100, 50, time(), 8, 'Meggetland'],
                [100, 50, time(), 8, 'Goldenacre'],
                [100, 53, time(), 8, 'Greenyards'],
                [100, 54, time(), 8, 'Bridgehaugh'],
                [100, 50, time(), 8, 'Myreside'],
                [100, 55, time(), 8, 'Rubislaw'],
                [100, 56, time(), 8, 'Malleny'],
                [100, 50, time(), 8, 'Raeburn'],
                [100, 51, time(), 8, 'Balgray'],
                [100, 57, time(), 8, 'Braidholm'],
                [100, 58, time(), 8, 'Mansfield'],
                [100, 59, time(), 8, 'Riverside'],
                [100, 60, time(), 8, 'Fullerton'],
                [100, 61, time(), 8, 'Stoneyhill'],
                [100, 62, time(), 8, 'Philiphaugh'],
                [100, 63, time(), 8, 'Hartree Mill'],
                [100, 50, time(), 8, 'Heriots'],
                [100, 64, time(), 8, 'Canal'],
                [100, 53, time(), 8, 'Greenyards'],
                [100, 65, time(), 8, 'Netherdale'],
                [100, 54, time(), 8, 'Bridgehaugh'],
                [100, 50, time(), 8, 'New Myreside'],
                [100, 51, time(), 8, 'Dumbreck'],
                [100, 66, time(), 8, 'Poynder'],
                [100, 52, time(), 8, 'Millbrae'],
                [100, 50, time(), 8, 'Meggetland'],
                [100, 67, time(), 8, 'Mayfield'],
                [100, 68, time(), 8, 'Farm'],
                [100, 69, time(), 8, 'Horne'],
                [100, 55, time(), 8, 'Countesswells'],
                [100, 70, time(), 8, 'Beveridge'],
                [100, 71, time(), 8, 'Cardiff Arms'],
                [100, 72, time(), 8, 'Rodney Parade'],
                [100, 73, time(), 8, 'Liberty'],
                [100, 74, time(), 8, 'Scarlets'],
                [100, 71, time(), 8, 'Cardiff Arms'],
                [100, 75, time(), 8, 'Carmarthen'],
                [100, 76, time(), 8, 'Talbot Athletic'],
                [100, 77, time(), 8, 'Church Bank'],
                [100, 78, time(), 8, 'Sardis'],
                [100, 79, time(), 8, 'Wern'],
                [100, 80, time(), 8, 'Eirias'],
                [100, 72, time(), 8, 'Rodney Parade'],
                [100, 73, time(), 8, 'St Helen\'s'],
                [100, 74, time(), 8, 'Scarlets'],
                [100, 81, time(), 8, 'Eugene Cross'],
                [100, 82, time(), 8, 'Morganstone Brewery'],
                [100, 83, time(), 8, 'Pontypool'],
                [100, 84, time(), 8, 'Gelligaled'],
                [100, 85, time(), 8, 'Lewis Lloyd'],
                [100, 71, time(), 8, 'Cyncoed Campus'],
                [100, 86, time(), 8, 'Rec'],
                [100, 87, time(), 8, 'South Parade'],
                [100, 88, time(), 8, 'Park'],
                [100, 89, time(), 8, 'Welfare'],
                [100, 76, time(), 8, 'Margam'],
                [100, 90, time(), 8, 'Mount Pleasant'],
                [100, 91, time(), 8, 'Rec'],
                [100, 92, time(), 8, 'Dol Wiber'],
                [100, 93, time(), 8, 'Pugh'],
                [100, 94, time(), 8, 'Rec'],
                [100, 95, time(), 8, 'Ystrad Fawr'],
                [100, 96, time(), 8, 'Abernant'],
                [100, 97, time(), 8, 'Pandy'],
                [100, 98, time(), 8, 'Whitworth'],
                [100, 99, time(), 8, 'Canberra'],
                [100, 100, time(), 8, 'Melbourne'],
                [100, 101, time(), 8, 'Sydney'],
                [100, 102, time(), 8, 'Ballymore'],
                [100, 102, time(), 8, 'Ballymore'],
                [100, 99, time(), 8, 'Viking'],
                [100, 103, time(), 8, 'Caltex'],
                [100, 100, time(), 8, 'Casey Fields'],
                [100, 104, time(), 8, 'Bond Sports'],
                [100, 101, time(), 8, 'Woollahra Oval'],
                [100, 105, time(), 8, 'HBF'],
                [100, 100, time(), 8, 'Melbourne'],
                [100, 106, time(), 8, 'Adelaide'],
                [100, 107, time(), 8, 'Gold Coast'],
                [100, 108, time(), 8, 'Tweed Heads'],
                [100, 109, time(), 8, 'Newcastle'],
                [100, 110, time(), 8, 'Maitland'],
                [100, 111, time(), 8, 'Queanbeyan'],
                [100, 112, time(), 8, 'Sunshine Coast'],
                [100, 113, time(), 8, 'Wollongong'],
                [100, 114, time(), 8, 'Geelong'],
                [100, 115, time(), 8, 'Hobart'],
                [100, 116, time(), 8, 'Townsville'],
                [100, 117, time(), 8, 'Cairns'],
                [100, 118, time(), 8, 'Darwin'],
                [100, 119, time(), 8, 'Toowoomba'],
                [100, 120, time(), 8, 'Ballarat'],
                [100, 121, time(), 8, 'Bendigo'],
                [100, 122, time(), 8, 'Albury'],
                [100, 123, time(), 8, 'Wodonga'],
                [100, 124, time(), 8, 'Launceston'],
                [100, 125, time(), 8, 'Mackay'],
                [100, 126, time(), 8, 'Rockhampton'],
                [100, 127, time(), 8, 'Bunbury'],
                [100, 128, time(), 8, 'Tala'],
                [100, 129, time(), 8, 'Jose Amalfitani'],
                [100, 130, time(), 8, 'Boulogne'],
                [100, 131, time(), 8, 'San Isidro'],
                [100, 132, time(), 8, 'La Plata'],
                [100, 133, time(), 8, 'Las Delicias'],
                [100, 134, time(), 8, 'Gabriel Palou'],
                [100, 134, time(), 8, 'Brigido'],
                [100, 135, time(), 8, 'Virrey del Pino 3456'],
                [100, 133, time(), 8, 'Rosario'],
                [100, 134, time(), 8, 'Marcos Paz'],
                [100, 136, time(), 8, 'Francia'],
                [100, 137, time(), 8, 'Uru Cure'],
                [100, 129, time(), 8, 'Villa de Mayo'],
                [100, 138, time(), 8, 'Del Golf'],
                [100, 139, time(), 8, 'El Bosque'],
                [100, 140, time(), 8, 'Tortuguitas'],
                [100, 141, time(), 8, 'La Carrodilla'],
                [100, 134, time(), 8, 'El Salvador'],
                [100, 142, time(), 8, 'Unión Cordobesa'],
                [100, 133, time(), 8, 'GER'],
                [100, 142, time(), 8, 'Presidente Peron'],
                [100, 143, time(), 8, 'Burzaco'],
                [100, 144, time(), 8, 'Brother Timothy O\'Brien'],
                [100, 145, time(), 8, 'Mar del Plata'],
                [100, 133, time(), 8, 'Plaza Jewell'],
                [100, 132, time(), 8, 'Barrio Obrero'],
                [100, 146, time(), 8, 'San Fernando'],
                [100, 129, time(), 8, 'G.E.B.A.'],
                [100, 147, time(), 8, 'Salta'],
                [100, 148, time(), 8, 'Vicente Lopez'],
                [100, 149, time(), 8, 'Corrientes'],
                [100, 150, time(), 8, 'Pilar'],
                [100, 151, time(), 8, 'Resistencia'],
                [100, 152, time(), 8, 'Armandie'],
                [100, 153, time(), 8, 'Jean Dauger'],
                [100, 154, time(), 8, 'Chaban-Delmas'],
                [100, 155, time(), 8, 'Amedee-Domenech'],
                [100, 156, time(), 8, 'Pierre-Fabre'],
                [100, 157, time(), 8, 'Marcel Michelin'],
                [100, 158, time(), 8, 'Marcel-Deflandre'],
                [100, 159, time(), 8, 'Gerland'],
                [100, 160, time(), 8, 'Altrad'],
                [100, 161, time(), 8, 'Hameau'],
                [100, 162, time(), 8, 'Paris La Defense'],
                [100, 163, time(), 8, 'Jean-Bouin'],
                [100, 164, time(), 8, 'Mayol'],
                [100, 165, time(), 8, 'Ernest-Wallon'],
                [100, 166, time(), 8, 'Jean Alric'],
                [100, 167, time(), 8, 'Mediterranée'],
                [100, 168, time(), 8, 'Sports Aguiléra'],
                [100, 169, time(), 8, 'Albert Domec'],
                [100, 170, time(), 8, 'Michel Bendichou'],
                [100, 171, time(), 8, 'Alpes'],
                [100, 172, time(), 8, 'Guy Boniface'],
                [100, 173, time(), 8, 'Sapiac'],
                [100, 174, time(), 8, 'Pre Fleuri'],
                [100, 175, time(), 8, 'Charles-Mathon'],
                [100, 176, time(), 8, 'Aime Giral'],
                [100, 177, time(), 8, 'Maurice David'],
                [100, 178, time(), 8, 'Jean-Mermoz'],
                [100, 179, time(), 8, 'Chanzy'],
                [100, 180, time(), 8, 'Marcel Guillermoz'],
                [100, 181, time(), 8, 'Rabine'],
                [100, 182, time(), 8, 'Grangettes'],
                [100, 183, time(), 8, 'Nicois'],
                [100, 184, time(), 8, 'Noel'],
                [100, 185, time(), 8, 'Pierre Rajon'],
                [100, 186, time(), 8, 'Monigo'],
                [100, 187, time(), 8, 'Sergio Lanfranchi '],
                [100, 188, time(), 8, 'Romolo Pacifici'],
                [100, 189, time(), 8, 'San Michele'],
                [100, 190, time(), 8, 'Gino Maini'],
                [100, 191, time(), 8, 'Polizia di Stato'],
                [100, 191, time(), 8, 'Giulio Onesti'],
                [100, 192, time(), 8, 'Mario Lodigiani'],
                [100, 193, time(), 8, 'Beltrametti'],
                [100, 194, time(), 8, 'Maurizio Quaggia'],
                [100, 195, time(), 8, 'Plebiscito'],
                [100, 196, time(), 8, 'Mario Battaglini'],
                [100, 197, time(), 8, 'Mirabello'],
                [100, 198, time(), 8, 'Luigi Zaffanella'],
                [100, 187, time(), 8, 'Ivan Francescato'],
                [100, 199, time(), 8, 'Milano'],
                [100, 200, time(), 8, 'Recco'],
                [100, 201, time(), 8, 'Torino'],
                [100, 202, time(), 8, 'Parabiago'],
                [100, 203, time(), 8, 'Genova'],
                [100, 204, time(), 8, 'Biella'],
                [100, 201, time(), 8, 'Torino'],
                [100, 199, time(), 8, 'Milano'],
                [100, 205, time(), 8, 'Noceto'],
                [100, 186, time(), 8, 'San Paolo'],
                [100, 206, time(), 8, 'Memo Geremia'],
                [100, 207, time(), 8, 'Valpolicella'],
                [100, 208, time(), 8, 'Paese'],
                [100, 209, time(), 8, 'Badia'],
                [100, 210, time(), 8, 'Udine'],
                [100, 211, time(), 8, 'Vicenza'],
                [100, 212, time(), 8, 'Brescia'],
                [100, 191, time(), 8, 'Unione'],
                [100, 213, time(), 8, 'Santa Maria Goretti'],
                [100, 214, time(), 8, 'Eden'],
                [100, 215, time(), 8, 'Waikato'],
                [100, 216, time(), 8, 'Rugby League'],
                [100, 217, time(), 8, 'Forsyth Barr'],
                [100, 218, time(), 8, 'Westpac'],
                [100, 214, time(), 8, 'Eden'],
                [100, 216, time(), 8, 'AMI'],
                [100, 219, time(), 8, 'ECOLight'],
                [100, 214, time(), 8, 'QBE'],
                [100, 220, time(), 8, 'Trafalgar'],
                [100, 215, time(), 8, 'Waikato'],
                [100, 218, time(), 8, 'Westpac'],
                [100, 221, time(), 8, 'Rotorua'],
                [100, 222, time(), 8, 'McLean'],
                [100, 223, time(), 8, 'Energy Trust'],
                [100, 224, time(), 8, 'Toll'],
                [100, 217, time(), 8, 'Forsyth Barr'],
                [100, 225, time(), 8, 'Forsyth Barr'],
                [100, 226, time(), 8, 'Yarrow'],
                [100, 227, time(), 8, 'Victoria Square'],
                [100, 228, time(), 8, 'Whakarua'],
                [100, 229, time(), 8, 'Levin Domain'],
                [100, 230, time(), 8, 'Owen Delany'],
                [100, 231, time(), 8, 'Ashburton'],
                [100, 232, time(), 8, 'Whitestone'],
                [100, 233, time(), 8, 'More FM Rugby'],
                [100, 234, time(), 8, 'Alpine Energy'],
                [100, 235, time(), 8, 'Paeroa Domain'],
                [100, 236, time(), 8, 'Memorial'],
                [100, 237, time(), 8, 'Cooks Gardens'],
                [100, 238, time(), 8, 'Rugby'],
                [100, 239, time(), 8, 'Tauranga'],
                [100, 240, time(), 8, 'Lower Hutt'],
                [100, 241, time(), 8, 'Upper Hutt'],
                [100, 242, time(), 8, 'Loftus Versfeld'],
                [100, 243, time(), 8, 'Emirates Airline'],
                [100, 244, time(), 8, 'Kings Park'],
                [100, 245, time(), 8, 'Newlands'],
                [100, 246, time(), 8, 'Free State'],
                [100, 247, time(), 8, 'Nelson Mandela Bay'],
                [100, 242, time(), 8, 'Pretoria'],
                [100, 248, time(), 8, 'Wellington'],
                [100, 249, time(), 8, 'East London'],
                [100, 247, time(), 8, 'Port Elizabeth'],
                [100, 250, time(), 8, 'Kempton Park'],
                [100, 246, time(), 8, 'Bloemfontein'],
                [100, 243, time(), 8, 'Johannesburg'],
                [100, 251, time(), 8, 'Welkom'],
                [100, 252, time(), 8, 'Kimberley'],
                [100, 253, time(), 8, 'Potchefstroom'],
                [100, 254, time(), 8, 'Mbombela'],
                [100, 244, time(), 8, 'Durban'],
                [100, 255, time(), 8, 'George'],
                [100, 245, time(), 8, 'Cape Town'],
                [100, 256, time(), 8, 'Vereeniging'],
                [100, 257, time(), 8, 'Soshanguve'],
                [100, 258, time(), 8, 'Pietermaritzburg'],
                [100, 246, time(), 8, 'CUT'],
                [100, 259, time(), 8, 'Danie Craven'],
                [100, 253, time(), 8, 'Fanie du Toit'],
                [100, 245, time(), 8, 'UCT'],
                [100, 246, time(), 8, 'Shimla Park'],
                [100, 243, time(), 8, 'Wits'],
                [100, 242, time(), 8, 'LC de Villiers'],
                [100, 242, time(), 8, 'TUT'],
                [100, 260, time(), 8, 'Rhodes'],
                [100, 261, time(), 8, 'Davidson'],
                [100, 258, time(), 8, 'Peter Booysen'],
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
