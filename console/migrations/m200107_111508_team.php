<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_111508_team
 * @package console\migrations
 */
class m200107_111508_team extends Migration
{
    private const TABLE = '{{%team}}';

    /**
     * @return bool|void
     * @throws \yii\db\Exception
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'team_id' => $this->primaryKey(11),
            'team_age' => $this->decimal(5, 3)->defaultValue(0),
            'team_attitude_national' => $this->integer(1)->defaultValue(2),
            'team_attitude_president' => $this->integer(1)->defaultValue(2),
            'team_attitude_u19' => $this->integer(1)->defaultValue(2),
            'team_attitude_u21' => $this->integer(1)->defaultValue(2),
            'team_auto' => $this->integer(1)->defaultValue(0),
            'team_base_id' => $this->integer(2)->defaultValue(2),
            'team_base_medical_id' => $this->integer(2)->defaultValue(1),
            'team_base_physical_id' => $this->integer(2)->defaultValue(1),
            'team_base_school_id' => $this->integer(2)->defaultValue(1),
            'team_base_scout_id' => $this->integer(2)->defaultValue(1),
            'team_base_training_id' => $this->integer(2)->defaultValue(1),
            'team_finance' => $this->integer(2)->defaultValue(10000000),
            'team_friendly_status_id' => $this->integer(1)->defaultValue(2),
            'team_free_base' => $this->integer(1)->defaultValue(5),
            'team_mood_rest' => $this->integer(1)->defaultValue(3),
            'team_mood_super' => $this->integer(1)->defaultValue(3),
            'team_name' => $this->string(255),
            'team_news_id' => $this->integer(11)->defaultValue(0),
            'team_player' => $this->integer(3)->defaultValue(30),
            'team_power_c_15' => $this->integer(5)->defaultValue(0),
            'team_power_c_19' => $this->integer(5)->defaultValue(0),
            'team_power_c_24' => $this->integer(5)->defaultValue(0),
            'team_power_s_15' => $this->integer(5)->defaultValue(0),
            'team_power_s_19' => $this->integer(5)->defaultValue(0),
            'team_power_s_24' => $this->integer(5)->defaultValue(0),
            'team_power_v' => $this->integer(5)->defaultValue(0),
            'team_power_vs' => $this->integer(5)->defaultValue(0),
            'team_price_base' => $this->integer(11)->defaultValue(0),
            'team_price_player' => $this->integer(11)->defaultValue(0),
            'team_price_stadium' => $this->integer(11)->defaultValue(0),
            'team_price_total' => $this->integer(11)->defaultValue(0),
            'team_salary' => $this->integer(7)->defaultValue(0),
            'team_stadium_id' => $this->integer(11)->defaultValue(0),
            'team_user_id' => $this->integer(11)->defaultValue(0),
            'team_vice_id' => $this->integer(11)->defaultValue(0),
            'team_visitor' => $this->integer(3)->defaultValue(100),
        ]);

        $this->createIndex('team_friendly_status_id', self::TABLE, 'team_friendly_status_id');
        $this->createIndex('team_stadium_id', self::TABLE, 'team_stadium_id');
        $this->createIndex('team_user_id', self::TABLE, 'team_user_id');
        $this->createIndex('team_vice_id', self::TABLE, 'team_vice_id');

        $this->insert(self::TABLE, [
            'team_name' => 'Free team',
        ]);

        $this->update(self::TABLE, ['team_id' => 0], ['team_id' => 1]);

        Yii::$app->db->createCommand('ALTER TABLE ' . self::TABLE . ' AUTO_INCREMENT=1')->execute();

        $this->batchInsert(
            self::TABLE,
            [
                'team_name',
                'team_power_c_15',
                'team_power_c_19',
                'team_power_c_24',
                'team_power_s_15',
                'team_power_s_19',
                'team_power_s_24',
                'team_power_v',
                'team_power_vs',
                'team_stadium_id',
            ],
            [
                ['Bath', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 1],
                ['Bristol Bears', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 2],
                ['Exeter Chiefs', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 3],
                ['Gloucester', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 4],
                ['Harlequins', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 5],
                ['Leicester Tigers', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 6],
                ['London Irish', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 7],
                ['Northampton Saints', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 8],
                ['Sale Sharks', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 9],
                ['Saracens', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 10],
                ['Wasps', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 11],
                ['Worcester Warriors', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 12],
                ['Ampthill', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 13],
                ['Bedford Blues', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 14],
                ['Cornish Pirates', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 15],
                ['Coventry', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 16],
                ['Doncaster Knights', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 17],
                ['Ealing Trailfinders', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 18],
                ['Hartpury College', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 19],
                ['Jersey Reds', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 20],
                ['London Scottish', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 21],
                ['Newcastle Falcons', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 22],
                ['Nottingham', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 23],
                ['Yorkshire Carnegie', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 24],
                ['Birmingham Moseley', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 25],
                ['Bishop\'s Stortford', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 26],
                ['Blackheath', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 27],
                ['Cambridge', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 28],
                ['Canterbury', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 29],
                ['Chinnor', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 30],
                ['Cinderford', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 31],
                ['Darlington Mowden', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 32],
                ['Hull Ionians', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 33],
                ['Rams', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 34],
                ['Ulster', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 35],
                ['Munster', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 36],
                ['Leinster', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 37],
                ['Connacht', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 38],
                ['Ballynahinch', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 39],
                ['Clontarf', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 40],
                ['Cork Constitution', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 41],
                ['Dublin University', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 42],
                ['Garryowen', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 43],
                ['Lansdowne', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 44],
                ['Terenure College', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 45],
                ['UCC', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 46],
                ['UCD', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 47],
                ['Young Munster', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 48],
                ['Armagh', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 49],
                ['Banbridge', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 50],
                ['Highfield', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 51],
                ['Malone', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 52],
                ['Naas', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 53],
                ['Navan', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 54],
                ['Old Belvedere', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 55],
                ['Old Wesley', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 56],
                ['Shannon', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 57],
                ['St Mary\'s College', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 58],
                ['Ballymena', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 59],
                ['Buccaneers', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 60],
                ['Cashel', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 61],
                ['Dolphin', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 62],
                ['MU Barnhall', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 63],
                ['Nenagh Ormond', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 64],
                ['Old Crescent', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 65],
                ['Queen\'s University', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 66],
                ['Rainey Old Boys', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 67],
                ['UL Bohemians', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 68],
                ['Edinburgh', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 69],
                ['Glasgow Warriors', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 70],
                ['Ayrshire Bulls', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 71],
                ['Boroughmuir', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 72],
                ['Heriot\'s', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 73],
                ['Southern Knights', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 74],
                ['Stirling County', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 75],
                ['Watsonian', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 76],
                ['Aberdeen GSFP', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 77],
                ['Currie', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 78],
                ['Edinburgh Academicals', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 79],
                ['Glasgow Hawks', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 80],
                ['Glasgow Hutchesons Aloysians', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 81],
                ['Hawick', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 82],
                ['Jed-Forest', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 83],
                ['Marr', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 84],
                ['Musselburgh', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 85],
                ['Selkirk', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 86],
                ['Biggar', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 87],
                ['Heriots', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 88],
                ['Highland', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 89],
                ['Melrose', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 90],
                ['Gala', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 91],
                ['Stirling County', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 92],
                ['Watsonians', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 93],
                ['Cartha Queens Park', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 94],
                ['Kelso', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 95],
                ['Ayr', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 96],
                ['Boroughmuir', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 97],
                ['Dundee', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 98],
                ['Dumfries Saints', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 99],
                ['Falkirk', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 100],
                ['Gordonians', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 101],
                ['Kirkcaldy', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 102],
                ['Cardiff Blues', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 103],
                ['Dragons', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 104],
                ['Ospreys', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 105],
                ['Scarlets', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 106],
                ['Cardiff', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 107],
                ['Carmarthen Quins', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 108],
                ['Aberavon', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 109],
                ['Llandovery', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 110],
                ['Pontypridd', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 111],
                ['Merthyr', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 112],
                ['RGC 1404', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 113],
                ['Newport', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 114],
                ['Swansea', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 115],
                ['Llanelli', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 116],
                ['Ebbw Vale', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 117],
                ['Bridgend Ravens', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 118],
                ['Pontypool', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 119],
                ['Ystrad Rhondda', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 120],
                ['Narberth', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 121],
                ['Cardiff MU', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 122],
                ['Bedlinog', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 123],
                ['Maesteg Harlequins', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 124],
                ['Trebanos', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 125],
                ['Newbridge', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 126],
                ['Tata Steel', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 127],
                ['Beddau', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 128],
                ['Rhydyfelin', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 129],
                ['Newcastle Emlyn', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 130],
                ['Brecon', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 131],
                ['Brynmawr', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 132],
                ['Penallta', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 133],
                ['Glynneath', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 134],
                ['Tondu', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 135],
                ['Tonmawr', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 136],
                ['Brumbies', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 137],
                ['Melbourne Rebels', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 138],
                ['NSW Waratahs', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 139],
                ['Queensland Reds', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 140],
                ['Brisbane City', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 141],
                ['Canberra Vikings', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 142],
                ['NSWC Eagles', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 143],
                ['Melbourne Rising', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 144],
                ['Queensland Country', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 145],
                ['Sydney', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 146],
                ['Western Force', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 147],
                ['Melbourne', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 148],
                ['Adelaide', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 149],
                ['Gold Coast', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 150],
                ['Tweed Heads', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 151],
                ['Newcastle', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 152],
                ['Maitland', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 153],
                ['Queanbeyan', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 154],
                ['Sunshine Coast', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 155],
                ['Wollongong', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 156],
                ['Geelong', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 157],
                ['Hobart', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 158],
                ['Townsville', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 159],
                ['Cairns', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 160],
                ['Darwin', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 161],
                ['Toowoomba', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 162],
                ['Ballarat', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 163],
                ['Bendigo', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 164],
                ['Albury', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 165],
                ['Wodonga', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 166],
                ['Launceston', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 167],
                ['Mackay', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 168],
                ['Rockhampton', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 169],
                ['Bunbury', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 170],
                ['Ceibos', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 171],
                ['Jaguares', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 172],
                ['San Isidro', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 173],
                ['CASI', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 174],
                ['San Luis', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 175],
                ['Duendes', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 176],
                ['Natacion y Gimnasia', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 177],
                ['Los Tarcos', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 178],
                ['Belgrano', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 179],
                ['Jockey Rosario', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 180],
                ['Tucuman', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 181],
                ['Regatas', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 182],
                ['Uru Cure', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 183],
                ['Universitario', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 184],
                ['Hindu', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 185],
                ['La Plata', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 186],
                ['Alumni', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 187],
                ['Marista', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 188],
                ['Tucuman LT', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 189],
                ['Jockey Cordoba', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 190],
                ['GER', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 191],
                ['Cordoba Atletico', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 192],
                ['Pucara', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 193],
                ['Newman', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 194],
                ['Mar del Plata', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 195],
                ['Atletico del Rosario', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 196],
                ['Los Tilos', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 197],
                ['Buenos Aires', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 198],
                ['Gimnasia y Esgrima', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 199],
                ['Salta', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 200],
                ['Vicente Lopez', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 201],
                ['Corrientes', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 202],
                ['Pilar', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 203],
                ['Resistencia', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 204],
                ['Sporting Union Agen', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 205],
                ['Aviron Bayonnais', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 206],
                ['Union Bordeaux Bègles', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 207],
                ['Athletique Brive', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 208],
                ['Castres Olympique', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 209],
                ['ASM Clermont Auvergne', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 210],
                ['Stade Rochelais', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 211],
                ['Lyon Olympique Universitaire', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 212],
                ['Montpellier Hérault', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 213],
                ['Section Paloise', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 214],
                ['Racing 92', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 215],
                ['Stade Francais', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 216],
                ['RC Toulonnais', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 217],
                ['Toulouse', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 218],
                ['Aurillac', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 219],
                ['Beziers', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 220],
                ['Biarritz', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 221],
                ['Carcassonne', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 222],
                ['Colomiers', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 223],
                ['Grenoble', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 224],
                ['Mont-de-Marsan', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 225],
                ['Montauban', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 226],
                ['Nevers', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 227],
                ['Oyonnax', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 228],
                ['Perpignan', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 229],
                ['Provence', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 230],
                ['Rouen', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 231],
                ['Soyaux Angouleme', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 232],
                ['Valence Romans', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 233],
                ['Vannes', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 234],
                ['Rumilly', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 235],
                ['Nice', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 236],
                ['Graulhet', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 237],
                ['Bourgoin-Jallieu', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 238],
                ['Benetton', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 239],
                ['Zebre', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 240],
                ['San Dona', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 241],
                ['Calvisano', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 242],
                ['Colorno', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 243],
                ['Fiamme Oro', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 244],
                ['S.S. Lazio', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 245],
                ['I Medicei', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 246],
                ['Lyons Piacenza', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 247],
                ['Mogliano', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 248],
                ['Petrarca', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 249],
                ['Rovigo Delta', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 250],
                ['Valorugby Emilia', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 251],
                ['Viadana', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 252],
                ['Accademia Francescato', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 253],
                ['As Milano', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 254],
                ['Recco', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 255],
                ['Cus Torino', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 256],
                ['Parabiago', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 257],
                ['Genova', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 258],
                ['Biella', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 259],
                ['VII Torino', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 260],
                ['Cus Milano', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 261],
                ['Noceto', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 262],
                ['Ruggers Tarvisium', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 263],
                ['Petrarca Cadetta', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 264],
                ['Valpolicella', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 265],
                ['Paese', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 266],
                ['Badia', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 267],
                ['Udine', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 268],
                ['Vicenza', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 269],
                ['Brescia', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 270],
                ['Capitolina', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 271],
                ['Catania', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 272],
                ['Blues', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 273],
                ['Chiefs', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 274],
                ['Crusaders', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 275],
                ['Highlanders', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 276],
                ['Hurricanes', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 277],
                ['Auckland', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 278],
                ['Canterbury', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 279],
                ['Counties Manukau', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 280],
                ['North Harbour', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 281],
                ['Tasman', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 282],
                ['Waikato', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 283],
                ['Wellington', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 284],
                ['Bay of Plenty', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 285],
                ['Hawke\'s Bay', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 286],
                ['Manawatu', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 287],
                ['Northland', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 288],
                ['Otago', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 289],
                ['Southland', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 290],
                ['Taranaki', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 291],
                ['Buller', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 292],
                ['East Coast', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 293],
                ['Horowhenua-Kapiti', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 294],
                ['King Country', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 295],
                ['Mid Canterbury', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 296],
                ['North Otago', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 297],
                ['Poverty Bay', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 298],
                ['South Canterbury', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 299],
                ['Thames Valley', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 300],
                ['Wairarapa Bush', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 301],
                ['Wanganui', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 302],
                ['West Coast', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 303],
                ['Tauranga', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 304],
                ['Lower Hutt', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 305],
                ['Upper Hutt', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 306],
                ['Bulls', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 307],
                ['Lions', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 308],
                ['Sharks', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 309],
                ['Stormers', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 310],
                ['Cheetahs', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 311],
                ['Southern Kings', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 312],
                ['Blue Bulls', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 313],
                ['Boland Cavaliers', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 314],
                ['Border Bulldogs', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 315],
                ['Eastern Province Kings', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 316],
                ['Falcons', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 317],
                ['Free State Cheetahs', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 318],
                ['Golden Lions', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 319],
                ['Griffons', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 320],
                ['Griquas', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 321],
                ['Leopards', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 322],
                ['Pumas', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 323],
                ['Sharks', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 324],
                ['SWD Eagles', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 325],
                ['Western Province', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 326],
                ['Vereeniging', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 327],
                ['Soshanguve', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 328],
                ['Pietermaritzburg', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 329],
                ['CUT Ixias', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 330],
                ['Maties', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 331],
                ['NWU Pukke', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 332],
                ['UCT Ikey Tigers', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 333],
                ['UFS Shimlas', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 334],
                ['Wits', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 335],
                ['UP Tuks', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 336],
                ['TUT Vikings', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 337],
                ['Rhodes', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 338],
                ['UFH Blues', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 339],
                ['UKZN Impi', 880, 1072, 1290, 880, 1072, 1290, 838, 838, 340],
            ]
        );
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
