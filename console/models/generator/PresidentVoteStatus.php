<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\ElectionPresident;
use common\models\db\ElectionPresidentApplication;
use common\models\db\ElectionPresidentViceApplication;
use common\models\db\ElectionStatus;
use common\models\db\Federation;
use common\models\db\History;
use common\models\db\HistoryText;
use Exception;
use yii\db\ActiveQuery;

/**
 * Class PresidentVoteStatus
 * @package console\models\generator
 */
class PresidentVoteStatus
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $electionPresidentArray = ElectionPresident::find()
            ->where(['election_status_id' => ElectionStatus::CANDIDATES])
            ->andWhere(['<', 'date', time() - 129600])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($electionPresidentArray as $electionPresident) {
            /**
             * @var ElectionPresident $electionPresident
             */
            if (count($electionPresident->electionPresidentApplications)) {
                $this->toOpen($electionPresident);
            }
        }

        $electionPresidentArray = ElectionPresident::find()
            ->where(['election_status_id' => ElectionStatus::OPEN])
            ->andWhere(['<', 'date', time() - 216000])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($electionPresidentArray as $electionPresident) {
            /**
             * @var ElectionPresident $electionPresident
             */
            $this->toClose($electionPresident);
        }
    }

    /**
     * @param ElectionPresident $electionPresident
     * @return void
     * @throws Exception
     */
    private function toOpen(ElectionPresident $electionPresident): void
    {
        $model = new ElectionPresidentViceApplication();
        $model->election_president_vice_id = $electionPresident->id;
        $model->text = '-';
        $model->save();

        $electionPresident->election_status_id = ElectionStatus::OPEN;
        $electionPresident->date = time();
        $electionPresident->save(true, ['election_status_id', 'date']);
    }

    /**
     * @param ElectionPresident $electionPresident
     * @return void
     * @throws Exception
     */
    private function toClose(ElectionPresident $electionPresident): void
    {
        /**
         * @var ElectionPresidentApplication[] $electionPresidentApplicationArray
         */
        $electionPresidentApplicationArray = ElectionPresidentApplication::find()
            ->alias('epa')
            ->joinWith([
                'electionPresidentVotes' => function (ActiveQuery $query) {
                    return $query
                        ->groupBy(['election_president_application_id']);
                },
                'user',
            ])
            ->select(['epa.*', 'COUNT(election_president_application_id) AS vote'])
            ->where(['election_president_id' => $electionPresident->id])
            ->andWhere(['not', ['user_id' => null]])
            ->andWhere([
                'not',
                ['user_id' => Federation::find()->select(['president_user_id'])]
            ])
            ->orderBy([
                'vote' => SORT_DESC,
                'rating' => SORT_DESC,
                'date_register' => SORT_ASC,
                'election_president_application_id' => SORT_ASC,
            ])
            ->all();
        if ($electionPresidentApplicationArray) {
            if ($electionPresidentApplicationArray[0]->user_id) {
                /**
                 * @var Federation[] $federationViceArray
                 */
                $federationViceArray = Federation::find()
                    ->where(['vice_user_id' => $electionPresidentApplicationArray[0]->user_id])
                    ->all();
                foreach ($federationViceArray as $countryVice) {
                    History::log([
                        'history_text_id' => HistoryText::USER_VICE_PRESIDENT_OUT,
                        'federation_id' => $countryVice->id,
                        'user_id' => $electionPresidentApplicationArray[0]->user_id,
                    ]);

                    $countryVice->vice_user_id = null;
                    $countryVice->save(true, ['vice_user_id']);
                }

                History::log([
                    'history_text_id' => HistoryText::USER_PRESIDENT_IN,
                    'federation_id' => $electionPresident->federation_id,
                    'user_id' => $electionPresidentApplicationArray[0]->user_id,
                ]);

                if (isset($electionPresidentApplicationArray[1])) {
                    $check = Federation::find()
                        ->where(['vice_user_id' => $electionPresidentApplicationArray[1]->user_id])
                        ->count();
                    if (!$check) {
                        History::log([
                            'federation_id' => $electionPresident->federation_id,
                            'history_text_id' => HistoryText::USER_VICE_PRESIDENT_IN,
                            'user_id' => $electionPresidentApplicationArray[1]->user_id,
                        ]);
                    }
                }

                $electionPresident->federation->president_user_id = $electionPresidentApplicationArray[0]->user_id;
                $electionPresident->federation->vice_user_id = $electionPresidentApplicationArray[1]->user_id ?? 0;
                $electionPresident->federation->save(true, ['president_user_id', 'vice_user_id']);
            }
        }

        $electionPresident->election_status_id = ElectionStatus::CLOSE;
        $electionPresident->save(true, ['election_status_id']);
    }
}
