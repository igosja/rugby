<?php

// TODO refactor

namespace console\models\generator;

use Yii;

/**
 * Class DumpDatabase
 * @package console\models\generator
 */
class DumpDatabase
{
    /**
     * @return void
     */
    public function execute(): void
    {
        $dbName = null;
        if (preg_match('/dbname=([^;]*)/', Yii::$app->db->dsn, $match)) {
            $dbName = $match[1];
        }
        exec('mysqldump -u '
            . Yii::$app->db->username
            . ' -p\''
            . Yii::$app->db->password
            . '\' '
            . $dbName
            . ' | gzip > `date +'
            . Yii::getAlias('@common')
            . '/../../dump.\%Y\%m\%d.sql.gz`');
    }
}
