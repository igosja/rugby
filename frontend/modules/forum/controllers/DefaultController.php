<?php

// TODO refactor

namespace frontend\modules\forum\controllers;

use common\models\db\ForumChapter;
use common\models\db\ForumMessage;
use frontend\controllers\AbstractController;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class DefaultController
 * @package frontend\modules\forum\controllers
 */
class DefaultController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $forumChapterArray = ForumChapter::find()
            ->orderBy(['order' => SORT_ASC])
            ->all();

        $myFederationArray = [];
        foreach ($this->myTeamArray as $team) {
            $myFederationArray[] = $team->stadium->city->country->federation->id;
        }

        $this->setSeoTitle(Yii::t('frontend', 'modules.forum.controllers.default.index.title'));
        return $this->render('index', [
            'forumChapterArray' => $forumChapterArray,
            'myFederationArray' => $myFederationArray,
        ]);
    }

    /**
     * @return string
     */
    public function actionSearch(): string
    {
        $query = ForumMessage::find()
            ->filterWhere(['like', 'text', Yii::$app->request->get('q')]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeForum']
            ]
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'modules.forum.controllers.default.search.title'));
        return $this->render('search', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
