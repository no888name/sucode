<?php

namespace App\Templates;

use wizard\Helper;

class Template
{
    public static function renderManifest($data)
    {
        $str = file_get_contents(__DIR__ . '/manifest.php');

        return strtr($str, $data);
    }

    public static function renderHelper($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/general/ModuleHelper.php');

        return $str;
    }

    public static function renderReadyManifest($installdefs, $manifest)
    {
        $str = file_get_contents(__DIR__ . '/ready_manifest.php');

        return strtr($str, [
            ':installdefs' => Helper::var_export_short($installdefs, true),
            ':manifest' => Helper::var_export_short($manifest, true),
        ]);
    }

    public static function renderOneToManyVardef($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/Extension/modules/modulename/Ext/Vardefs/template_relationship.php');

        return strtr($str, $data);
    }

    public static function renderManyToManyVardef($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/Extension/modules/modulename/Ext/Vardefs/template_relationship_many.php');

        return strtr($str, $data);
    }

    public static function renderApi($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/clients/base/api/SampleApi.php');

        return strtr($str, $data);
    }

    public static function renderCustomJobScheduler($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/jobs/CustomJobScheduler.php');

        return strtr($str, $data);
    }

    public static function renderLayoutDef($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/clients/base/layouts/template-layout/template-layout.php');

        return strtr($str, $data);
    }

    public static function renderLayoutViewJs($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/clients/base/views/template-layout-view/template-layout-view.js');

        return strtr($str, $data);
    }
    public static function renderLayoutViewHbs($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/clients/base/views/template-layout-view/template-layout-view.hbs');

        return strtr($str, $data);
    }

    public static function renderAdminLayoutDef($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/Extension/modules/Administration/Ext/Administration/template-layout.php');

        return strtr($str, $data);
    }
    public static function renderAdminLayoutLang($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/Extension/modules/Administration/Ext/Language/en_us.template-layout-name.php');

        return strtr($str, $data);
    }

    public static function renderSchedulerLang($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/Extension/modules/Schedulers/Ext/Language/en_us.template.php');

        return strtr($str, $data);
    }

    public static function renderSchedulerFile($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/Extension/modules/Schedulers/Ext/ScheduledTasks/task_template.php');

        return strtr($str, $data);
    }


    public static function renderJsGroupingsPhp($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/Extension/application/Ext/JSGroupings/template.php');

        return strtr($str, $data);
    }

    public static function renderJsGroupingsJs($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/include/js_gouppings_template.js');

        return strtr($str, $data);
    }

    public static function renderManyToManyMetadata($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/metadata/module1_module2MetaData.php');

        return strtr($str, $data);
    }

    public static function renderTableDictionary($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/Extension/application/Ext/TableDictionary/module1_module2.php');

        return strtr($str, $data);
    }

    public static function renderSubpanelFile($items)
    {
        $str = file_get_contents(__DIR__ . '/custom/Extension/modules/modulename/Ext/clients/base/layouts/subpanels/template_subpanel.php');

        return strtr($str, $items);
    }

    public static function renderLabelsFile($items)
    {
//        var_dump($items);

        $content = '<?php' . PHP_EOL;

        foreach ($items as $item) {
            $content .= '$mod_strings["' . $item['label'] . '"] = "' . $item['translation'] . '";' . PHP_EOL;
        }

        return $content;
    }

    public static function renderListFile($name, $values)
    {
//        var_dump($items);

        $content = '<?php' . PHP_EOL;
        $content .= '$app_list_strings["' . $name . '"] = ' . Helper::var_export_short($values, true) .';'. PHP_EOL;
        return $content;
    }

    public static function renderHooks($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/Extension/modules/modulename/Ext/LogicHooks/template_hooks.php');

        return strtr($str, $data);
    }

    public static function renderHooksClass($data)
    {
        $str = file_get_contents(__DIR__ . '/custom/hooks/UniversalClass.php');

        return strtr($str, $data);
    }

    public static function renderZipper()
    {
        return file_get_contents(__DIR__ . '/zipper.php');
    }

    public static function renderPostInstall()
    {
        return file_get_contents(__DIR__ . '/post_install.php');
    }

    public static function renderPhpCs()
    {
        $str = file_get_contents(__DIR__ . '/.php_cs');

        return $str;
    }

    public static function renderAnonScript($data)
    {
        $str = file_get_contents(__DIR__ . '/anon_script.php');

        return strtr($str, $data);
    }
    public static function renderFaker($data)
    {
        $str = file_get_contents(__DIR__ . '/Faker.php');

        return strtr($str, $data);
    }

    public static function renderLicence()
    {
        $str = <<<LICENCE

Copyright (C) MyCRM.de Inc. All rights reserved.
LICENCE;

        return $str;
    }

    public static function renderGitignore()
    {
        $str = <<<GITIGNORE
*.zip
zipper.php
.idea/
.php_cs.cache
.php_cs

GITIGNORE;

        return $str;
    }
}
