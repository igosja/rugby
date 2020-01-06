<?php

namespace frontend\components\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class Alert
 * @package frontend\components\widgets
 */
class Alert extends Widget
{
    /**
     * @return string|void
     */
    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $flash) {
            foreach ((array)$flash as $i => $message) {
                print Html::tag(
                    'div',
                    Html::tag(
                        'div',
                        $message,
                        ['class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert ' . $type]
                    ),
                    ['class' => 'row margin-top']
                );
            }

            $session->removeFlash($type);
        }
    }
}
