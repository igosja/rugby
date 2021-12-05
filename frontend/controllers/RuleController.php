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

        $this->setSeoTitle(Yii::t('frontend', 'controllers.rule.index.title'));
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

        $this->setSeoTitle($rule->title . ' - ' . Yii::t('frontend', 'controllers.rule.view.title'));
        return $this->render('view', [
            'rule' => $rule,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionSearch()
    {
        $queryString = trim(Yii::$app->request->get('q'));
        if (!$queryString) {
            return $this->redirect(['index']);
        }

        $query = Rule::find()
            ->filterWhere(['like', 'text', $queryString])
            ->orderBy(['id' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.rule.search.title'));
        return $this->render('search', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
