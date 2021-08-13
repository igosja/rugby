<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\Building;
use common\models\db\Position;
use common\models\db\School;
use common\models\db\Special;
use common\models\db\Style;
use Exception;
use frontend\models\forms\SchoolForm;
use Throwable;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * Class SchoolController
 * @package frontend\controllers
 */
class SchoolController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string|Response
     */
    public function actionIndex()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        $model = new SchoolForm();
        if ($model->load(Yii::$app->request->post(), '')) {
            return $this->redirect($model->redirectUrl());
        }

        $schoolArray = School::find()
            ->where(['ready' => null, 'team_id' => $team->id])
            ->all();

        $this->setSeoTitle($team->fullName() . '. ' . Yii::t('frontend', 'controllers.school.index.title'));

        return $this->render('index', [
            'onBuilding' => $this->isOnBuilding(),
            'positionArray' => ArrayHelper::map(Position::find()->all(), 'id', 'name'),
            'schoolArray' => $schoolArray,
            'specialArray' => ArrayHelper::map(Special::find()->all(), 'id', 'text'),
            'styleArray' => ArrayHelper::map(Style::find()->where(['!=', 'id', Style::NORMAL])->all(), 'id', 'name'),
            'team' => $team,
        ]);
    }

    /**
     * @return bool
     */
    private function isOnBuilding(): bool
    {
        if (!$this->myTeam->buildingBase) {
            return false;
        }

        if (!in_array($this->myTeam->buildingBase->building_id, [Building::BASE, Building::SCHOOL], true)) {
            return false;
        }

        return true;
    }

    /**
     * @return string|Response
     */
    public function actionStart()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        if ($this->isOnBuilding()) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.school.start.error.on-building'));
            return $this->redirect(['index']);
        }

        if (!$team->availableSchool()) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.school.start.error.available'));
            return $this->redirect(['index']);
        }

        $school = School::find()
            ->where(['ready' => null, 'team_id' => $team->id])
            ->count();
        if ($school) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.school.start.error.school'));
            return $this->redirect(['index']);
        }

        $data = Yii::$app->request->get();

        $confirmData = [
            'position' => [],
            'special' => [],
            'style' => [],
        ];

        if (!$data['position_id']) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.school.start.error.position.empty'));
            return $this->redirect(['index']);
        }

        /**
         * @var Position $position
         */
        $position = Position::find()
            ->where(['id' => $data['position_id']])
            ->limit(1)
            ->one();
        if (!$position) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.school.start.error.position'));
            return $this->redirect(['index']);
        }

        $confirmData['position'] = [
            'id' => $position->id,
            'name' => $position->text,
        ];

        if ($data['special_id'] && $team->availableSchoolWithSpecial()) {
            $specialId = $data['special_id'];
        } else {
            $specialId = null;
        }

        /**
         * @var Special $special
         */
        $special = Special::find()
            ->andFilterWhere(['id' => $specialId])
            ->orderBy('RAND()')
            ->limit(1)
            ->one();
        if (!$special) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.school.start.error.special'));
            return $this->redirect(['index']);
        }

        $confirmData['special'] = [
            'id' => $special->id,
            'name' => $special->text,
            'with' => $specialId,
        ];

        if ($data['style_id'] && $team->availableSchoolWithStyle()) {
            $styleId = $data['style_id'];
        } else {
            $styleId = null;
        }

        /**
         * @var Style $style
         */
        $style = Style::find()
            ->andFilterWhere(['id' => $styleId])
            ->andWhere(['!=', 'id', Style::NORMAL])
            ->orderBy('RAND()')
            ->limit(1)
            ->one();
        if (!$style) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.school.start.error.style'));
            return $this->redirect(['index']);
        }

        $confirmData['style'] = [
            'id' => $style->id,
            'name' => $style->name,
            'with' => $styleId,
        ];

        if (Yii::$app->request->get('ok')) {
            try {
                $model = new School();
                $model->day = $team->baseSchool->school_speed;
                $model->position_id = $position->id;
                $model->season_id = $this->season->id;
                $model->special_id = $specialId;
                $model->style_id = $style->id;
                $model->team_id = $team->id;
                $model->is_with_special = (bool)$specialId;
                $model->is_with_special_request = (bool)$data['special_id'];
                $model->is_with_style = (bool)$styleId;
                $model->is_with_style_request = (bool)$data['style_id'];
                $model->save();

                $this->setSuccessFlash(Yii::t('frontend', 'controllers.school.start.success'));
            } catch (Exception $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash();
            }
            return $this->redirect(['index']);
        }

        $this->setSeoTitle($team->fullName() . '. ' . Yii::t('frontend', 'controllers.school.start.title'));

        return $this->render('start', [
            'confirmData' => $confirmData,
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     */
    public function actionCancel(int $id)
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        $school = School::find()
            ->where(['id' => $id, 'ready' => null, 'team_id' => $team->id])
            ->limit(1)
            ->one();
        if (!$school) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.school.cancel.error'));
            return $this->redirect(['index']);
        }

        if (Yii::$app->request->get('ok')) {
            try {
                $school->delete();

                $this->setSuccessFlash(Yii::t('frontend', 'controllers.school.cancel.success'));
            } catch (Throwable $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash();
            }
            return $this->redirect(['index']);
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.school.cancel.title') . ' ' . $team->fullName());

        return $this->render('cancel', [
            'id' => $id,
            'team' => $team,
            'school' => $school,
        ]);
    }
}
