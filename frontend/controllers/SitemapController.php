<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\Team;
use Yii;
use yii\helpers\Url;
use yii\web\Response;

/**
 * Class SitemapController
 * @package frontend\controllers
 */
class SitemapController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $arr = [];

        $teamArray = Team::find()
            ->where(['!=', 'id', 0])
            ->each();
        foreach ($teamArray as $team) {
            /**
             * @var Team $team
             */
            $arr[] = [
                'loc' => Url::to(['team/view', 'id' => $team->id]),
                'lastmod' => date(DATE_W3C),
                'priority' => '0.50',
            ];
        }

        $xml_array = $this->renderPartial('index', [
            'host' => Yii::$app->request->hostInfo,
            'urls' => $arr,
        ]);

        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');

        return $xml_array;
    }
}
