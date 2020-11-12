<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\Rule;
use frontend\models\queries\RuleQuery;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Class RuleController
 * @package frontend\controllers
 */
class RuleController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $ruleArray = RuleQuery::getRuleList();

        $this->setSeoTitle('Правила');
        return $this->render('index', [
            'ruleArray' => $ruleArray,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        /**
         * @var Rule $rule
         */
        $rule = RuleQuery::getRuleById($id);
        $this->notFound($rule);

        $this->setSeoTitle($rule->title . ' - Правила');
        return $this->render('view', [
            'rule' => $rule,
        ]);
    }

    /**
     * @return string
     */
    public function actionSearch(): string
    {
        $query = Rule::find()
            ->filterWhere(['like', 'text', Yii::$app->request->get('q')])
            ->orderBy(['id' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $this->setSeoTitle('Результаты поиска - Правила');
        return $this->render('search', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
