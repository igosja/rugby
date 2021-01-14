<?php

// TODO refactor

namespace common\components;

use yii\i18n\MissingTranslationEvent;

/**
 * Class TranslationEventHandler
 * @package common\components
 */
class TranslationEventHandler
{
    /**
     * @param MissingTranslationEvent $event
     */
    public static function handleMissingTranslation(MissingTranslationEvent $event): void
    {
        $event->translatedMessage = "<strong class='red'>@MISSING: {$event->category}.{$event->message} FOR LANGUAGE {$event->language}@</strong>";
    }
}