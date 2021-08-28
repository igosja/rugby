<?php

// TODO refactor

namespace console\controllers;

use common\models\db\TranslateKey;
use common\models\db\TranslateOption;
use common\models\db\User;
use Yii;

/**
 * Insert translates from files into database table for user voting.
 *
 * Class TranslateController
 * @package console\controllers
 */
class TranslateController extends AbstractController
{
    /**
     * Insert translates from files into database table for user voting.
     *
     * @return bool
     */
    public function actionIndex(): bool
    {
        $translates = [];
        $languages = ['en', 'ru'];
        foreach ($languages as $language) {
            $path = Yii::getAlias('@common') . '/messages/' . $language . '/';
            $files = scandir($path);
            unset($files[0], $files[1]);

            foreach ($files as $file) {
                $key = explode('.', $file);
                $translates[$language][$key[0]] = require Yii::getAlias('@common') . '/messages/' . $language . '/' . $file;
            }
        }

        foreach ($translates['en'] as $category => $messages) {
            foreach ($messages as $message => $text) {
                $translateKey = TranslateKey::find()
                    ->andWhere([
                        'category' => $category,
                        'message' => $message
                    ])
                    ->limit(1)
                    ->all();
                if (!$translateKey) {
                    $translateKey = new TranslateKey();
                    $translateKey->category = $category;
                    $translateKey->message = $message;
                    $translateKey->text = $translates['ru'][$category][$message];
                    $translateKey->save();
                }

                $translateOption = TranslateOption::find()
                    ->andWhere([
                        'text' => $text,
                        'translate_key_id' => $translateKey->id,
                        'user_id' => User::ADMIN_USER_ID,
                    ])
                    ->limit(1)
                    ->one();
                if (!$translateOption) {
                    $translateOption = new TranslateOption();
                    $translateOption->text = $text;
                    $translateOption->translate_key_id = $translateKey->id;
                    $translateOption->user_id = User::ADMIN_USER_ID;
                    $translateOption->save();
                }
            }
        }

        return true;
    }
}
