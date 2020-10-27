<?php

namespace console\migrations;

use Yii;
use yii\db\Exception;
use yii\db\Migration;

/**
 * Class M200101000002Country
 * @package console\migrations
 */
class M200101000002Country extends Migration
{
    private const TABLE = '{{%country}}';

    /**
     * @return bool
     * @throws Exception
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(3),
                'name' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->insert(
            self::TABLE,
            [
                'name' => 'League',
            ]
        );

        $this->update(self::TABLE, ['id' => 0], ['id' => 1]);

        Yii::$app->db->createCommand('ALTER TABLE ' . self::TABLE . ' AUTO_INCREMENT=1')->execute();

        $this->batchInsert(
            self::TABLE,
            ['name'],
            [
                ['Afghanistan'],
                ['Albania'],
                ['Algeria'],
                ['Andorra'],
                ['Angola'],
                ['Antigua and Barbuda'],
                ['Argentina'],
                ['Armenia'],
                ['Australia'],
                ['Austria'],
                ['Azerbaijan'],
                ['Bahamas'],
                ['Bahrain'],
                ['Bangladesh'],
                ['Barbados'],
                ['Belarus'],
                ['Belgium'],
                ['Belize'],
                ['Benin'],
                ['Bolivia'],
                ['Bosnia and Herzegovina'],
                ['Botswana'],
                ['Brazil'],
                ['Brunei'],
                ['Bulgaria'],
                ['Burkina Faso'],
                ['Burundi'],
                ['Butane'],
                ['Cambodia'],
                ['Cameroon'],
                ['Canada'],
                ['Cape Verde'],
                ['Central African Republic'],
                ['Chad'],
                ['Chile'],
                ['China'],
                ['Colombia'],
                ['Comoros'],
                ['Congo'],
                ['Costa Rica'],
                ['Cote d\'Ivoire'],
                ['Croatia'],
                ['Cuba'],
                ['Cyprus'],
                ['Czech'],
                ['Denmark'],
                ['Djibouti'],
                ['Dominica'],
                ['Dominican Republic'],
                ['DR Congo'],
                ['East Timor'],
                ['Ecuador'],
                ['Egypt'],
                ['England'],
                ['Equatorial Guinea'],
                ['Eritrea'],
                ['Estonia'],
                ['Ethiopia'],
                ['Fiji'],
                ['Finland'],
                ['France'],
                ['Gabon'],
                ['Gambia'],
                ['Georgia'],
                ['Germany'],
                ['Ghana'],
                ['Greece'],
                ['Grenada'],
                ['Guatemala'],
                ['Guinea'],
                ['Guinea bissau'],
                ['Guyana'],
                ['Haiti'],
                ['Honduras'],
                ['Hungary'],
                ['Iceland'],
                ['India'],
                ['Indonesia'],
                ['Iran'],
                ['Iraq'],
                ['Ireland'],
                ['Israel'],
                ['Italy'],
                ['Jamaica'],
                ['Japan'],
                ['Jordan'],
                ['Kazakhstan'],
                ['Kenya'],
                ['Kiribati'],
                ['Kosovo'],
                ['Kuwait'],
                ['Kyrgyzstan'],
                ['Laos'],
                ['Latvia'],
                ['Lebanon'],
                ['Lesotho'],
                ['Liberia'],
                ['Libya'],
                ['Liechtenstein'],
                ['Lithuania'],
                ['Luxembourg'],
                ['Madagascar'],
                ['Malawi'],
                ['Malaysia'],
                ['Maldives'],
                ['Mali'],
                ['Malta'],
                ['Marshall Islands'],
                ['Mauritania'],
                ['Mauritius'],
                ['Mexico'],
                ['Micronesia'],
                ['Moldova'],
                ['Monaco'],
                ['Mongolia'],
                ['Montenegro'],
                ['Morocco'],
                ['Mozambique'],
                ['Myanmar'],
                ['Namibia'],
                ['Nauru'],
                ['Nepal'],
                ['Netherlands'],
                ['New Zealand'],
                ['Nicaragua'],
                ['Niger'],
                ['Nigeria'],
                ['North Korea'],
                ['North Macedonia'],
                ['Northern Ireland'],
                ['Norway'],
                ['Oman'],
                ['Pakistan'],
                ['Palau'],
                ['Panama'],
                ['Papua New Guinea'],
                ['Paraguay'],
                ['Peru'],
                ['Philippines'],
                ['Poland'],
                ['Portugal'],
                ['Qatar'],
                ['Romania'],
                ['Russia'],
                ['Rwanda'],
                ['Saint Kitts and Nevis'],
                ['Saint lucia'],
                ['Saint Vincent and the Grenadines'],
                ['Salvador'],
                ['Samoa'],
                ['San marino'],
                ['Sao Tome and Principe'],
                ['Saudi Arabia'],
                ['Scotland'],
                ['Senegal'],
                ['Serbia'],
                ['Seychelles'],
                ['Sierra leone'],
                ['Singapore'],
                ['Slovakia'],
                ['Slovenia'],
                ['Solomon islands'],
                ['Somalia'],
                ['South Africa'],
                ['South Korea'],
                ['South Sudan'],
                ['Spain'],
                ['Sri Lanka'],
                ['Sudan'],
                ['Suriname'],
                ['Swaziland'],
                ['Sweden'],
                ['Switzerland'],
                ['Syria'],
                ['Tajikistan'],
                ['Tanzania'],
                ['Thailand'],
                ['Togo'],
                ['Tonga'],
                ['Trinidad and Tobago'],
                ['Tunisia'],
                ['Turkey'],
                ['Turkmenistan'],
                ['Tuvalu'],
                ['Uganda'],
                ['Ukraine'],
                ['United Arab Emirates'],
                ['Uruguay'],
                ['USA'],
                ['Uzbekistan'],
                ['Vanuatu'],
                ['Venezuela'],
                ['Vietnam'],
                ['Wales'],
                ['Yemen'],
                ['Zambia'],
                ['Zimbabwe'],
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
