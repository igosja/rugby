<?php

// TODO refactor

namespace console\migrations;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\Migration;
use yii\log\DbTarget;

/**
 * Class M191231000000Log
 * @package console\migrations
 */
class M191231000000Log extends Migration
{
    /**
     * @var DbTarget[]
     */
    private $dbTargets = [];

    /**
     * @return bool|void|null
     * @throws InvalidConfigException
     */
    public function up(): ?bool
    {
        foreach ($this->getDbTargets() as $target) {
            $this->db = $target->db;

            $tableOptions = null;
            if ($this->db->driverName === 'mysql') {
                $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
            }

            $this->createTable($target->logTable, [
                'id' => $this->bigPrimaryKey(),
                'level' => $this->integer(),
                'category' => $this->string(),
                'log_time' => $this->double(),
                'prefix' => $this->text(),
                'message' => $this->text(),
            ], $tableOptions);

            $this->createIndex('level', $target->logTable, 'level');
            $this->createIndex('category', $target->logTable, 'category');
        }

        return true;
    }

    /**
     * @return DbTarget[]
     * @throws InvalidConfigException
     */
    protected function getDbTargets(): array
    {
        if ($this->dbTargets === []) {
            $log = Yii::$app->getLog();

            $usedTargets = [];
            foreach ($log->targets as $target) {
                if ($target instanceof DbTarget) {
                    $currentTarget = [
                        $target->db,
                        $target->logTable,
                    ];
                    if (!in_array($currentTarget, $usedTargets, true)) {
                        $usedTargets[] = $currentTarget;
                        $this->dbTargets[] = $target;
                    }
                }
            }
        }

        if ($this->dbTargets === []) {
            throw new InvalidConfigException('You should configure "log" component to use one or more database targets before executing this migration.');
        }

        return $this->dbTargets;
    }

    /**
     * @return bool|void|null
     * @throws InvalidConfigException
     */
    public function down(): ?bool
    {
        foreach ($this->getDbTargets() as $target) {
            $this->db = $target->db;

            $this->dropTable($target->logTable);
        }

        return true;
    }
}
