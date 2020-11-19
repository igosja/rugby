<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\ElectionPresidentVice;
use common\models\db\ElectionPresidentViceApplication;
use common\models\db\ElectionStatus;
use common\models\db\Federation;
use common\models\db\History;
use common\models\db\HistoryText;
use Exception;
use yii\db\ActiveQuery;

/**
 * Class PresidentViceVoteStatus
 * @package console\models\generator
 */
class PresidentViceVoteStatus
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $electionPresidentViceArray = ElectionPresidentVice::find()
            ->where(['election_status_id' => ElectionStatus::CANDIDATES])
            ->andWhere(['<', 'date', time() - 129600])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($electionPresidentViceArray as $electionPresidentVice) {
            /**
             * @var ElectionPresidentVice $electionPresidentVice
             */
            if (count($electionPresidentVice->electionPresidentViceApplications)) {
                $this->toOpen($electionPresidentVice);
            }
        }

        $electionPresidentViceArray = ElectionPresidentVice::find()
            ->where(['election_status_id' => ElectionStatus::OPEN])
            ->andWhere(['<', 'date', time() - 216000])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($electionPresidentViceArray as $electionPresidentVice) {
            /**
             * @var ElectionPresidentVice $electionPresidentVice
             */
            $this->toClose($electionPresidentVice);
        }
    }

    /**
     * @param ElectionPresidentVice $electionPresidentVice
     * @throws Exception
     */
    private function toOpen(ElectionPresidentVice $electionPresidentVice): void
    {
        $model = new ElectionPresidentViceApplication();
        $model->election_president_vice_id = $electionPresidentVice->id;
        $model->text = '-';
        $model->save();

        $electionPresidentVice->date = time();
        $electionPresidentVice->election_status_id = ElectionStatus::OPEN;
        $electionPresidentVice->save(true, ['date', 'election_status_id']);
    }

    /**
     * @param ElectionPresidentVice $electionPresidentVice
     * @throws Exception
     */
    private function toClose(ElectionPresidentVice $electionPresidentVice): void
    {
        /**
         * @var ElectionPresidentViceApplication $electionPresidentViceApplication
         */
        $electionPresidentViceApplication = ElectionPresidentViceApplication::find()
            ->alias('epa')
            ->joinWith([
                'electionPresidentViceVotes' => function (ActiveQuery $query) {
                    return $query
                        ->groupBy(['election_president_vice_application_id']);
                },
                'user',
            ])
            ->select([
                'epa.*',
                'COUNT(election_president_vice_application_id) AS vote'
            ])
            ->where(['election_president_vice_id' => $electionPresidentVice->id])
            ->andWhere(['not', ['user_id' => 0]])
            ->andWhere([
                'not',
                ['user_id' => Federation::find()->select(['president_user_id'])]
            ])
            ->andWhere([
                'not',
                ['user_id' => Federation::find()->select(['vice_user_id'])]
            ])
            ->orderBy([
                'vote' => SORT_DESC,
                'rating' => SORT_DESC,
                'date_register' => SORT_ASC,
                'election_president_vice_application_id' => SORT_ASC,
            ])
            ->limit(1)
            ->one();
        if ($electionPresidentViceApplication) {
            History::log([
                'federation_id' => $electionPresidentVice->federation_id,
                'history_text_id' => HistoryText::USER_VICE_PRESIDENT_IN,
                'user_id' => $electionPresidentViceApplication->user_id,
            ]);

            $electionPresidentVice->federation->vice_user_id = $electionPresidentViceApplication->user_id;
            $electionPresidentVice->federation->save(true, ['vice_user_id']);
        }

        $electionPresidentVice->election_status_id = ElectionStatus::CLOSE;
        $electionPresidentVice->save(true, ['election_status_id']);
    }
}
