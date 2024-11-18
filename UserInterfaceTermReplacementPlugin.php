<?php

/**
 * @file plugins/reports/userInterfaceTermReplacement/UserInterfaceTermReplacementPlugin.php
 *
 * Copyright (c) 2024 Lepidus Tecnologia
 * Copyright (c) 2024 SciELO
 * Distributed under the GNU GPL v3. For full terms see LICENSE or https://www.gnu.org/licenses/gpl-3.0.txt.
 *
 * @class UserInterfaceTermReplacementPlugin
 * @ingroup plugins_generic_userInterfaceTermReplacement
 *
 */

namespace APP\plugins\generic\userInterfaceTermReplacement;

use PKP\plugins\Hook;
use PKP\plugins\GenericPlugin;
use APP\core\Application;

class UserInterfaceTermReplacementPlugin extends GenericPlugin
{
    private const TERMS_FOR_REPLACEMENT = [
        'Preprint' => 'Postprint',
        'preprint' => 'postprint'
    ];

    public function register($category, $path, $mainContextId = null)
    {
        $success = parent::register($category, $path, $mainContextId);
        if (Application::isUnderMaintenance()) {
            return true;
        }

        if ($success && $this->getEnabled($mainContextId)) {
            Hook::add('Locale::translate', [$this, 'replaceTermOnLocaleTranslation']);
            Hook::add('TemplateManager::display', [$this, 'replaceBreadcrumbsTitle']);
        }

        return $success;
    }

    public function getDisplayName()
    {
        return __('plugins.generic.userInterfaceTermReplacement.displayName');
    }

    public function getDescription()
    {
        return __('plugins.generic.userInterfaceTermReplacement.description');
    }

    public function replaceTermOnLocaleTranslation($hookName, $params)
    {
        $localeValue = &$params[0];

        foreach (self::TERMS_FOR_REPLACEMENT as $term => $replacement) {
            if (str_contains($localeValue, $term)) {
                $localeValue = str_replace($term, $replacement, $localeValue);
            }
        }
    }

    public function replaceBreadcrumbsTitle($hookName, $params)
    {
        $templateMgr = $params[0];
        $template = $params[1];

        if ($template !== 'frontend/pages/preprint.tpl') {
            return Hook::CONTINUE;
        }

        $templateMgr->registerFilter("output", [$this, 'replaceTermOnTemplateOutput']);
    }

    public function replaceTermOnTemplateOutput($output, $templateMgr)
    {
        $term = 'Preprint';
        $replacement = self::TERMS_FOR_REPLACEMENT[$term];
        $startDelimiter = '<span aria-current="page">';
        $endDelimiter = '<article class="obj_preprint_details">';

        $startPos = strpos($output, $startDelimiter);
        $endPos = strpos($output, $endDelimiter);

        if ($startPos && $endPos) {
            $startPos += strlen($startDelimiter);
            $sectionToReplace = substr($output, $startPos, $endPos - $startPos);

            if (str_contains($sectionToReplace, $term)) {
                $sectionReplaced = str_replace($term, $replacement, $sectionToReplace);
                $output = substr($output, 0, $startPos) . $sectionReplaced . substr($output, $endPos);
                $templateMgr->unregisterFilter("output", [$this, 'replaceTermOnTemplateOutput']);
            }
        }

        return $output;
    }
}

if (!PKP_STRICT_MODE) {
    class_alias('\APP\plugins\generic\userInterfaceTermReplacement\UserInterfaceTermReplacementPlugin', '\UserInterfaceTermReplacementPlugin');
}
