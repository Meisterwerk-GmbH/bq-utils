<?php

namespace Meisterwerk\BqUtils\MW;

use Meisterwerk\Core\TranslationManager;

class MwBqTranslationManager
{
    private TranslationManager $translationManager;

    public function __construct(string $projectPath)
    {
        $languages = array_map(
            fn(Language $lang): string => $lang->getIsoLanguageCode(),
            Language::cases()
        );
        $this->translationManager = new TranslationManager($projectPath, $languages);
    }

    public function getText(Language $lang, string $keyString, array $templateVars = []): string
    {
        return $this->translationManager->getText(
            $lang->getIsoLanguageCode(),
            $keyString,
            $templateVars
        );
    }
}