<?php

namespace Meisterwerk\BqUtils\MW;

use Meisterwerk\Core\TranslationManager;

class MwBqTranslationManager
{
    private TranslationManager $translationManager;

    public function __construct(string $projectPath)
    {
        $this->translationManager = new TranslationManager($projectPath);
    }

    public function getText(string $keyString, Language $lang = Language::GERMAN, array $templateVars = []): string
    {
        return $this->translationManager->getText(
            $keyString,
            $lang->getIsoLanguageCode(),
            $templateVars
        );
    }
}