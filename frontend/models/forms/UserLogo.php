<?php

// TODO refactor

namespace frontend\models\forms;

use common\models\db\Logo;
use Exception;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class UserLogo
 * @package frontend\models
 *
 * @property UploadedFile $file
 * @property int $userId
 */
class UserLogo extends Model
{
    public $file;
    public $userId;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [
                ['file'],
                'image',
                'extensions' => 'png',
                'maxHeight' => 125,
                'maxWidth' => 100,
                'minHeight' => 125,
                'minWidth' => 100
            ],
            [['file'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'file' => 'Фото',
        ];
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function upload(): bool
    {
        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }
        $this->file = UploadedFile::getInstance($this, 'file');
        if (!$this->validate()) {
            return false;
        }

        $model = Logo::find()
            ->where(['team_id' => null, 'user_id' => $this->userId])
            ->limit(1)
            ->one();
        if (!$model) {
            $model = new Logo();
            $model->user_id = $this->userId;
        }
        $model->text = '-';
        $model->save();

        $this->file->saveAs(Yii::getAlias('@webroot') . '/upload/img/user/125/' . $this->userId . '.' . $this->file->extension);

        return true;
    }
}
