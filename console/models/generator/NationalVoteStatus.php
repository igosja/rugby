<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\ElectionNational;
use common\models\db\ElectionNationalApplication;
use common\models\db\ElectionStatus;
use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\National;
use Exception;
use yii\db\ActiveQuery;

/**
 * Class NationalVoteStatus
 * @package console\models\generator
 */
class NationalVoteStatus
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $electionNationalArray = ElectionNational::find()
            ->where(['election_status_id' => ElectionStatus::CANDIDATES])
            ->andWhere(['<', 'date', time() - 129600])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($electionNationalArray as $electionNational) {
            /**
             * @var ElectionNational $electionNational
             */
            if (count($electionNational->electionNationalApplications)) {
                $this->toOpen($electionNational);
            }
        }

        $electionNationalArray = ElectionNational::find()
            ->where(['election_status_id' => ElectionStatus::OPEN])
            ->andWhere(['<', 'date', time() - 216000])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($electionNationalArray as $electionNational) {
            /**
             * @var ElectionNational $electionNational
             */
            $this->toClose($electionNational);
        }
    }

    /**
     * @param ElectionNational $electionNational
     * @throws Exception
     */
    private function toOpen(ElectionNational $electionNational): void
    {
        $model = new ElectionNationalApplication();
        $model->election_national_id = $electionNational->id;
        $model->text = '-';
        $model->save();

        $electionNational->date = time();
        $electionNational->election_status_id = ElectionStatus::OPEN;
        $electionNational->save(true, ['date', 'election_status_id']);
    }

    /**
     * @param ElectionNational $electionNational
     * @throws Exception
     */
    private function toClose(ElectionNational $electionNational): void
    {
        /**
         * @var ElectionNationalApplication[] $electionNationalApplicationArray
         */
        $electionNationalApplicationArray = ElectionNationalApplication::find()
            ->alias('ena')
            ->joinWith([
                'electionNationalVotes' => function (ActiveQuery $query) {
                    return $query
                        ->groupBy(['election_national_application_id']);
                },
                'user',
            ])
            ->select(['ena.*', 'COUNT(election_national_application_id) AS vote'])
            ->where(['election_national_id' => $electionNational->id])
            ->andWhere(['not', ['user_id' => null]])
            ->andWhere([
                'not',
                ['user_id' => National::find()->select(['user_id'])]
            ])
            ->orderBy([
                'vote' => SORT_DESC,
                'rating' => SORT_DESC,
                'date_register' => SORT_ASC,
                'election_national_application_id' => SORT_ASC,
            ])
            ->all();
        if ($electionNationalApplicationArray) {
            if ($electionNationalApplicationArray[0]->user_id) {
                /**
                 * @var National[] $nationalViceArray
                 */
                $nationalViceArray = National::find()
                    ->where(['vice_user_id' => $electionNationalApplicationArray[0]->user_id])
                    ->all();
                foreach ($nationalViceArray as $nationalVice) {
                    History::log([
                        'history_text_id' => HistoryText::USER_VICE_NATIONAL_OUT,
                        'national_id' => $nationalVice->id,
                        'user_id' => $electionNationalApplicationArray[0]->user_id,
                    ]);

                    $nationalVice->vice_user_id = null;
                    $nationalVice->save(true, ['vice_user_id']);
                }

                History::log([
                    'history_text_id' => HistoryText::USER_MANAGER_NATIONAL_IN,
                    'national_id' => $electionNational->national->id,
                    'user_id' => $electionNationalApplicationArray[0]->user_id,
                ]);

                if (isset($electionNationalApplicationArray[1])) {
                    $check = National::find()
                        ->where(['vice_user_id' => $electionNationalApplicationArray[1]->user_id])
                        ->count();
                    if (!$check) {
                        History::log([
                            'history_text_id' => HistoryText::USER_VICE_NATIONAL_IN,
                            'national_id' => $electionNational->national->id,
                            'user_id' => $electionNationalApplicationArray[1]->user_id,
                        ]);
                    }
                }

                $electionNational->national->user_id = $electionNationalApplicationArray[0]->user_id;
                $electionNational->national->vice_user_id = $electionNationalApplicationArray[1]->user_id ?? 0;
                $electionNational->national->save(true, ['user_id', 'vice_user_id']);

                foreach ($electionNationalApplicationArray[0]->electionNationalPlayers as $electionNationalPlayer) {
                    $electionNationalPlayer->player->national_id = $electionNational->national->id;
                    $electionNationalPlayer->player->save(true, ['national_id']);
                }
            }
        }

        $electionNational->election_status_id = ElectionStatus::CLOSE;
        $electionNational->save(true, ['election_status_id']);
    }
}
