<?php

// TODO refactor

namespace frontend\modules\federation\controllers;

use common\models\db\Federation;
use frontend\controllers\AbstractController as ParentController;
use frontend\models\queries\FederationQuery;
use yii\web\NotFoundHttpException;

/**
 * Class DefaultController
 * @package frontend\modules\federation\controllers
 */
abstract class AbstractController extends ParentController
{
    /**
     * @param int $id
     * @return Federation
     * @throws NotFoundHttpException
     */
    protected function getFederation(int $id): Federation
    {
        $federation = FederationQuery::getFederationById($id);
        $this->notFound($federation);

        return $federation;
    }
}
