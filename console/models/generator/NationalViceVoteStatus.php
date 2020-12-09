<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\ElectionNationalVice;
use common\models\db\ElectionNationalViceApplication;
use common\models\db\ElectionStatus;
use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\National;
use Exception;
use yii\db\ActiveQuery;

/**
 * Class NationalViceVoteStatus
 * @package console\models\generator
 */
class NationalViceVoteStatus
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $electionNationalViceArray = ElectionNationalVice::find()
            ->where(['election_status_id' => ElectionStatus::CANDIDATES])
            ->andWhere(['<', 'date', time() - 129600])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($electionNationalViceArray as $electionNationalVice) {
            /**
             * @var ElectionNationalVice $electionNationalVice
             */
            if (count($electionNationalVice->electionNationalViceApplications)) {
                $this->toOpen($electionNationalVice);
            }
        }

        $electionNationalViceArray = ElectionNationalVice::find()
            ->where(['election_status_id' => ElectionStatus::OPEN])
            ->andWhere(['<', 'date', time() - 216000])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($electionNationalViceArray as $electionNationalVice) {
            /**
             * @var ElectionNationalVice $electionNationalVice
             */
            $this->toClose($electionNationalVice);
        }
    }

    /**
     * @param ElectionNationalVice $electionNationalVice
     * @throws Exception
     */
    private function toOpen(ElectionNationalVice $electionNationalVice): void
    {
        $model = new ElectionNationalViceApplication();
        $model->election_national_vice_id = $electionNationalVice->id;
        $model->text = '-';
        $model->save();

        $electionNationalVice->date = time();
        $electionNationalVice->election_status_id = ElectionStatus::OPEN;
        $electionNationalVice->save(true, ['date', 'election_status_id']);
    }

    /**
     * @param ElectionNationalVice $electionNationalVice
     * @throws Exception
     */
    private function toClose(ElectionNationalVice $electionNationalVice): void
    {
        /**
         * @var ElectionNationalViceApplication $electionNationalViceApplication
         */
        $electionNationalViceApplication = ElectionNationalViceApplication::find()
            ->alias('ena')
            ->joinWith([
                'electionNationalViceVotes' => function (ActiveQuery $query) {
                    return $query
                        ->groupBy(['election_national_vice_application_id']);
                },
                'user',
            ])
            ->select([
                'ena.*',
                'COUNT(election_national_vice_application_id) AS vote'
            ])
            ->where(['election_national_vice_id' => $electionNationalVice->id])
            ->andWhere(['not', ['user_id' => null]])
            ->andWhere([
                'not',
                ['user_id' => National::find()->select(['user_id'])]
            ])
            ->andWhere([
                'not',
                ['user_id' => National::find()->select(['vice_user_id'])]
            ])
            ->orderBy([
                'vote' => SORT_DESC,
                'rating' => SORT_DESC,
                'date_register' => SORT_ASC,
                'election_national_vice_application_id' => SORT_ASC,
            ])
            ->limit(1)
            ->one();
        if ($electionNationalViceApplication) {
            History::log([
                'history_text_id' => HistoryText::USER_VICE_NATIONAL_IN,
                'national_id' => $electionNationalVice->national->id,
                'user_id' => $electionNationalViceApplication->user_id,
            ]);

            $electionNationalVice->national->vice_user_id = $electionNationalViceApplication->user_id;
            $electionNationalVice->national->save(true, ['vice_user_id']);
        }

        $electionNationalVice->election_status_id = ElectionStatus::CLOSE;
        $electionNationalVice->save(true, ['election_status_id']);
    }
}
