<?php

// TODO refactor

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;

/**
 * Class LogSearch
 * @package backend\models\search
 */
class LogSearch extends Model
{
    /**
     * @return array
     */
    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * @param string $chapter
     * @return ArrayDataProvider
     */
    public function search(string $chapter): ArrayDataProvider
    {
        return new ArrayDataProvider([
            'allModels' => $this->models($chapter),
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
    }

    /**
     * @param string $chapter
     * @return array
     */
    private function models(string $chapter): array
    {
        $path = Yii::getAlias($chapter);
        if (!file_exists($path . '/runtime/logs/app.log')) {
            return [];
        }

        $file = file_get_contents($path . '/runtime/logs/app.log');
        $fileData = explode(']' . PHP_EOL . '2', $file);
        $models = [];
        foreach ($fileData as $fileDatum) {
            $text = $fileDatum;
            if ('2' !== $text[0]) {
                $text = '2' . $text;
            }
            $models[] = ['text' => $text];
        }
        return array_reverse($models);
    }
}
