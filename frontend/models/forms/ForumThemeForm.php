<?php

// TODO refactor

namespace frontend\models\forms;

use common\models\db\ForumMessage;
use common\models\db\ForumTheme;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * Class ForumThemeForm
 * @package frontend\models
 *
 * @property string $name
 * @property string $text
 * @property int $themeId
 */
class ForumThemeForm extends Model
{
    public $name;
    public $text;
    private $themeId;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'text'], 'trim'],
            [['name', 'text'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function create($id)
    {
        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
            $theme = new ForumTheme();
            $theme->forum_group_id = $id;
            $theme->name = $this->name;
            $theme->user_id = Yii::$app->user->id;
            $theme->save();

            $message = new ForumMessage();
            $message->forum_theme_id = $theme->id;
            $message->text = $this->text;
            $message->user_id = Yii::$app->user->id;
            $message->save();

            if ($transaction) {
                $transaction->commit();
            }

            $this->themeId = $theme->id;

        return true;
    }

    /**
     * @return int
     */
    public function getThemeId()
    {
        return $this->themeId;
    }
}
